<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_User extends Controller_Main
{
	
	function actionRegister(){
		$error = "";
		if($this->_context->isPOST()){
			$phone = $this->_context->phone;
			$code = $this->_context->code;
			$user = $this->_context->user;
			if($code == $_SESSION['wehcat_code'] && $phone == $_SESSION['wehcat_phone']){

				return $this->_redirect(url('default/bind'));
			}else{
				$error = "绑定失败";
				return $this->_redirect(url('user/register',array('error'=>$error)));
			}
		}
		$this->_view['title'] = "绑定手机";
		$this->_view['error'] = $error;
	}

	function actionSend(){
		$phone = $this->_context->phone;

		/*$code = rand(1000,9999);
		$_SESSION['wehcat_code'] = $code;
   		$_SESSION['wehcat_phone'] = $phone;
		$return = array('code'=>$code);
		return json_encode($return);
		exit;*/
		
		if(!empty($phone)){
			$code = rand(1000,9999);
			$ws = "http://10.211.1.90:80/services/InfoManager?wsdl";
			//libxml_disable_entity_loader(false);
   			$msg = new SoapClient($ws);
   			$res = $msg->TeacherSend("","你的验证码是".$code." 【图书馆微信】",$phone,false,"","1","教师发送");
   			if($res){
   				$_SESSION['wehcat_code'] = $code;
   				$_SESSION['wehcat_phone'] = $phone;
   				$return = array('code'=>$code);
				return json_encode($return);
				exit;
   			}
		}else{
			$return = array('status'=>'fail');
			return json_encode($return);
			exit;
		}
		exit;
	}
}

