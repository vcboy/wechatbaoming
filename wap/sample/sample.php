<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxd8b0c84c1425ea57", "a4264fb3f6eca52c34c6717e0d341e6c");
$signPackage = $jssdk->GetSignPackage();
//var_dump($signPackage);
//var_dump($jssdk->accessToken);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
</head>
<body>
  
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
  });
</script>
<?php


function httpPost($url,$data){
  //$data = array("name" => "Hagrid", "age" => "36");                                                                    
  $data_string = urlencode(json_encode($data));    

  echo $url;
  echo $data_string;                                                                                                                     
  $ch = curl_init($url);                                                                      
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Content-Length: ' . strlen($data_string))                                                                       
  );                                                                                                                   
  //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
  //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);                                                                                                                     
  $result = curl_exec($ch);
  return $result;

}

function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(trim(substr(file_get_contents("access_token.php"), 15)));
    /*if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $this->set_php_file("access_token.php", json_encode($data));
      }
    } else {
      $access_token = $data->access_token;
    }*/
    $access_token = $data->access_token;
    return $access_token;
}

function createMenu(){
  $accessToken = getAccessToken();
  $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
  $json = '{
    "button": [
        { 
            "name": "读者服务", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "读者绑定", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://live.mynep.com/weixin/index.php?action=bind&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
                {
                    "type": "view", 
                    "name": "电子证", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://live.mynep.com/weixin/index.php?action=card&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
                {
                    "type": "view", 
                    "name": "我要借书", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
                {
                    "type": "view", 
                    "name": "我的借阅", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
                {
                    "type": "view", 
                    "name": "转借他人", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }
            ]
        }, 
        {
            "name": "读者须知", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "开馆时间", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "办证须知", 
                    "url": "http://v.qq.com/"
                }, 
                {
                    "type": "view", 
                    "name": "我的权限", 
                    "url": "http://v.qq.com/"
                },
                {
                    "type": "view", 
                    "name": "查询图书", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
                {
                    "type": "view", 
                    "name": "定位图书", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }
            ]
        },
        {
            "type": "view", 
            "name": "在线交流", 
            "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://www.dynamicautoaccessories.com/wx/getopenid.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
        }
    ]
}';
  $data = json_decode($json,true);
  //var_dump($data);
  $res = httpPost($url,$data);
  var_dump($res);
}

function deletemenu(){
  $accessToken = getAccessToken();
  $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$accessToken;
  $res = httpGet($url);
}

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

//ccessToken = getAccessToken();
//r_dump($accessToken);
$a = $_GET['a'];
switch ($a) {
  case 'createmenu':
    //创建栏目
    $res = createMenu();
    var_dump($res);
    break;
  case 'deletemenu':
    //删除栏目
    deletemenu();
    break;
  default:
    # code...
    break;
}
echo $a;
?>

</html>
