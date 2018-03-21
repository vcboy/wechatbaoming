<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_Qiandao extends Controller_Main
{
	

	
	function actionAccesstoken(){	
		$access_token = $this->jssdk->getAccessToken();
		var_dump($access_token);
	}

	function actionIndex(){
		$code = $this->_context->code;
		if($code){
			$res = $this->jssdk->getopenid($code);
			$wxuser = json_decode($res);
			var_dump($wxuser->openid);
		}else{
			return $this->_redirect($this->url);
		}			
	}

	/**
	 * 
	 * @return [type] [description]
	 */
	function actionLocation(){
		$this->_view['title'] = '地理位置';
		$signPackage = $this->jssdk->getSignPackage();
		$this->_view['signPackage'] = $signPackage;
	}


	
}

