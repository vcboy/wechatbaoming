<?php
class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $root_dir = Q::ini('app_config/ROOT_DIR');
    $file_jt = $root_dir .'/sample/jsapi_ticket.php';
    //echo $file_jt;
    $data = json_decode($this->get_php_file($file_jt));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = isset($res->ticket)?$res->ticket:'';
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $this->set_php_file($file_jt, json_encode($data));
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  public function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    //file_at = 
    $root_dir = Q::ini('app_config/ROOT_DIR');
    //exit($root_dir);
    $file_at = $root_dir .'/sample/access_token.php';
    $data = json_decode($this->get_php_file($file_at));
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      //echo $url;
      $res = json_decode($this->httpGet($url));
      //var_dump($res);
      $access_token = isset($res->access_token)?$res->access_token:'';
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $this->set_php_file($file_at, json_encode($data));
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    /*curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);*/
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSLVERSION, 1);

    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    if($errno = curl_errno($curl)) {
        $error_message = curl_strerror($errno);
        echo "cURL error ({$errno}):\n {$error_message}";
    }
    curl_close($curl);

    return $res;
  }

  private  function httpPost($url, $query=array(), $is_urlcode=true) {
      if (is_array($query)) {
        foreach ($query as $key => $val) {  
          if($is_urlcode){
              $encode_key = urlencode($key);
          }else{
              $encode_key = $key;
          }
          if ($encode_key != $key) {  
            unset($query[$key]);  
          }
          if($is_urlcode){
              $query[$encode_key] = urlencode($val);
          }else{
              $query[$encode_key] = $val;
          }

        }  
      }
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_POST, true );
      curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($curl, CURLOPT_SSLVERSION, 1);
      $res = curl_exec($curl);
      curl_close($curl);
      return $res; 
  }

  private function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
  }
  private function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }


  public function getopenid($code){

    $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=$code&grant_type=authorization_code";
    //echo $url;
    $res = $this->httpGet($url);
    //var_dump($res);
    return $res;
    //var_dump($res);
  }

  /**
   * 获取卡券的apiticket
   */
  private function getApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $root_dir = Q::ini('app_config/ROOT_DIR');
    $file_at = $root_dir .'/sample/api_ticket.php';
    //echo $file_jt;
    $data = json_decode($this->get_php_file($file_at));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$accessToken."&type=wx_card";
      //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      //var_dump($res);
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->api_ticket = $ticket;
        $this->set_php_file($file_at, json_encode($data));
      }
    } else {
      $ticket = $data->api_ticket;
    }

    return $ticket;
  }

  /**
   * 获取卡券的signpackage
   */
  public function getCardSignPackage($card_id) {
    $apiTicket = $this->getApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    /*$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";*/

    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    $signdata = array();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    /*$string = "jsapi_ticket=$apiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);*/
    array_push($signdata, (string)$apiTicket);
    array_push($signdata, (string)$timestamp);
    array_push($signdata, (string)$card_id);
    array_push($signdata, (string)$nonceStr);

    $signature = $this->get_signature($signdata);
    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "card_id"       => $card_id,
      "signature" => $signature,
      "apiTicket" => $apiTicket
    );
    return $signPackage; 
  }

  function get_signature($signdata){
    sort( $signdata, SORT_STRING );
    return sha1( implode( $signdata ) );
  }


    /**
     * @descrpition 查询code，判断code是否有效
     * @param $card_id
     * @param $code 
     * @return string
     */
    public  function getcardcode($card_id, $code){
        //获取ACCESS_TOKEN
        //$accessToken = AccessToken::getAccessToken();
        $accessToken = $this->getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/card/code/get?access_token='.$accessToken;
        //开始
        $template = array(
            'card_id'=> $card_id,
            'code' => $code,
            'check_consume' => false
        );
        $template = json_encode($template);
        $res = json_decode($this->httpPost($queryUrl, $template, 1));
        return $res;
    }

    /**
     * [核销卡券]
     * @param  [type] $code       [description]
     * @return [type]             [description]
     */
    public  function comsumecardcode($code){
        //获取ACCESS_TOKEN
        //$accessToken = AccessToken::getAccessToken();
        $accessToken = $this->getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/card/code/consume?access_token='.$accessToken;
        //开始
        $template = array(
            'code' => $code
        );
        $template = json_encode($template);
        //return Curl::callWebServer($queryUrl, $template, 'POST', 1 , 0);
        $res = json_decode($this->httpPost($queryUrl, $template, 1));
        return $res;
    }

    /**
     * 获得模板ID
     * $templateIdShort 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     *
     * @return array("errcode"=>0, "errmsg"=>"ok", "template_id":"Doclyl5uP7Aciu-qZ7mJNPtWkbkYnWBWVja26EGbNyk")  "errcode"是0则表示没有出错
     */
    public  function getTemplateId($templateIdShort){
      $accessToken = $this->getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.$accessToken;
        $queryAction = 'POST';
        $template = array();
        $template['template_id_short'] = "$templateIdShort";
        $template = json_encode($template);
        $res = json_decode($this->httpPost($queryUrl, $template, 1));
        return $res;
        //return Curl::callWebServer($queryUrl, $template, $queryAction);
    }

    /**
     * 向用户推送模板消息
     * @param $data = array(
     *                  'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A')
     *                  'keynote1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC')
     *                  'keynote2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     * );
     * @param $touser 接收方的OpenId。
     * @param $templateId 模板Id。在公众平台线上模板库中选用模板获得ID
     * @param $url URL
     * @param string $topcolor 顶部颜色， 可以为空。默认是红色
     * @return array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332} "errcode"是0则表示没有出错
     *
     * 注意：推送后用户到底是否成功接受，微信会向公众号推送一个消息。
     */
    public  function sendTemplateMessage($data, $touser, $templateId, $url, $topcolor='#FF0000'){
      $accessToken = $this->getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$accessToken;
        $queryAction = 'POST';
        $template = array();
        $template['touser'] = $touser;
        $template['template_id'] = $templateId;
        $template['url'] = $url;
        $template['topcolor'] = $topcolor;
        $template['data'] = $data;
        $template = json_encode($template);
        $res = json_decode($this->httpPost($queryUrl, $template, 1));
        return $res;
        //return Curl::callWebServer($queryUrl, $template, $queryAction);
    }
}

