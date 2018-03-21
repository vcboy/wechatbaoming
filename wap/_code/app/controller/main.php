<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */

class Controller_Main extends Controller_Abstract
{
	public $webservice;
    public $jssdk;
    public $url;
    public $weburl;
	function __construct($app){
		parent::__construct($app);
		
        if(@$_SESSION['user']){
            $user = $_SESSION['user'];
            $login = 1;
        }

   		/*require_once Q::ini ( 'app_config/LIB_DIR' ) . '/webservice.php';
   		$this->webservice = new Webservice();*/
   		/*require_once Q::ini ( 'app_config/LIB_DIR' ) . '/webcurl.php';
   		$this->webservice = new Webcurl();*/
   		
        $this->weburl = "http://libwx.app.nbpt.edu.cn/weixin/wap/index.php";
   		$this->siteurl = "http://libwx.app.nbpt.edu.cn";
        /*require_once Q::ini ( 'app_config/LIB_DIR' ) . '/webservice.php';
        $this->webservice = new Webservice();*/

   		$this->appid = "wx48adc6dd2a50e6c5";
   		$this->appsecret = "64276b7c52034216d1a7d604c02e8099";
   		$this->TOKEN = "8vEcHl8xQ4MHkYg5Fc799o1GfpMFwcOC";
   		$this->url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$this->weburl."?action=bind&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        $this->wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$this->weburl;
        require_once Q::ini ( 'app_config/LIB_DIR' ) . '/jssdk.php';
        $this->jssdk = new JSSDK($this->appid, $this->appsecret);

        //测试时打开
        //$_SESSION['user'] = array('openid'=>'o33Vtv9Oewm2dckRj-AkksuwGGTM','readercode'=>'800012');
   		//$this->ikeywords = Ikeywords::find()->getAll()->toHashMap("keyword","content");
	}

    

    public function checkLogin(){
        $user = $this->_app->currentUser();
        //var_dump($user);
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
    }

    /**
     * 根据openid获得后台用户信息
     * @return [type] [description]
     */
    function getUserinfoByOpenid($code){
        $wxuserjson = $this->getopenid($code);
        $wxuser = json_decode($wxuserjson);
        //$wxuser = (object)array('openid'=>'aaaa');
        //var_dump($wxuser);
        $userinfo = array();
        if(isset($wxuser->openid)){
            $openid = $wxuser->openid;
            /*$res = $this->webservice->CreatedQRCode($openid);
            foreach ($res->CreatedQRCodeResult as $key => $value) {
                if($value->szReturn=='True'){
                    $userinfo = array('openid'=>$openid,'readercode'=>$value->szReaderCode);
                }
            }*/
            $readerinfo = $this->getReaderByOpenid($openid);
            if($readerinfo){
                $userinfo = array('openid'=>$openid,'readercode'=>$readerinfo['szreaderid'],'readername'=>$readerinfo['szname']);
            }
        }else{
            $openid = '';
        }
        
        /*if($res->CreatedQRCodeResult->szReaderCode){
            $userinfo = array('openid'=>$openid,'readercode'=>$res->CreatedQRCodeResult->szReaderCode);
        }*/
        return $userinfo;
    }


    public function getopenid($code){
        $appid = 'wx48adc6dd2a50e6c5';
        $secret = '64276b7c52034216d1a7d604c02e8099';
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=$code&grant_type=authorization_code";
        //echo $url;
        $res = $this->httpGet($url);
        //var_dump($res);
        return $res;
        //var_dump($res);
    }

    public function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);

        //记录下，当openid取不到的时候可以看下CURLOPT_SSL_VERIFYPEER和CURLOPT_SSL_VERIFYHOST的设置，有时设置上面这样，有时设置下面这样
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }


    function LogSave($content="",$memo=""){
        $log = new Log();
        $log['content'] = $content;
        $log['memo'] = $memo;
        $log['time'] = time();
        $log['userid'] = empty($_SESSION['user'])?"":$_SESSION['user']['openid'];
        $log['readercode'] = empty($_SESSION['user']['readercode'])?"":$_SESSION['user']['readercode'];
        $log['readername'] = empty($_SESSION['user']['readername'])?"":$_SESSION['user']['readername'];
        $log->save();
    }

}

