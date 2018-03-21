<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_Book extends Controller_Main
{
	/*public $webservice;
	function __construct($app){
		parent::__construct($app);
		if(@$_SESSION['user']){
			$user = $_SESSION['user'];
			$login = 1;
		}else{			
					
		}

   		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/webservice.php';
   		$this->webservice = new Webservice();
   		$_SESSION['user'] = array('openid'=>'1111','readercode'=>'888888');

   		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/jssdk.php';
   		$this->appid = "wxd8b0c84c1425ea57";
   		$this->appsecret = "a4264fb3f6eca52c34c6717e0d341e6c";
   		$this->jssdk = new JSSDK($this->appid, $this->appsecret);

   		$this->url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=http://live.mynep.com/weixin/index.php?controller=book&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
	}*/

	
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
	 * 卡券核销扫一扫
	 * @return [type] [description]
	 */
	function actionComsume(){
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$readercode = $user['readercode'];
		$reader = Readerinfo::find("szreaderid = ?",$readercode)->getOne();
		$ishx = 0;
		if(@$reader['ishx']){
			$ishx = 1;
		}
		$this->_view['ishx'] = $ishx;
		$this->_view['title'] = '卡券核销';
		$signPackage = $this->jssdk->getSignPackage();
		$this->_view['signPackage'] = $signPackage;
		//var_dump($signPackage);
		//exit();
	}

	/**
	 * 核销卡券ajax
	 * @return [type] [description]
	 */
	function actionComsumecode(){
		$code = $this->_context->code;
		$card_id = '';
		$res = $this->jssdk->getcardcode($card_id,$code);
		/*var_dump($res);
		exit();*/
		if(@$res->can_consume == true ){
			$cres = $this->jssdk->comsumecardcode($code);
			//return json_encode ( $cres );
			if($cres->errcode == 0)
				return '0';
			else
				return '1';
		}
		return '1';
		//var_dump($res);
		//return json_encode ( $res );
		//exit();
	}

	/**
	 * 图书续借通知，通过服务器计划调用book/reborrownotice来执行
	 *
	 */
	function actionReborrownotice(){
		$res = $this->webservice->OverRemind(5);
		//var_dump($res);
		if($res->OverRemindResult->Result){
			$over = $res->OverRemindResult->Books->Over;
			foreach ($over as $key => $value) {
				$openid[] = $value->OpenID;
			}
		}
		$result = array_unique($openid);
		$touser = 'o33Vtv9Oewm2dckRj-AkksuwGGTM';
		$result[] = $touser;

		$templateId = "NtVIOn-MJoDNh8mfj6aLj65gs4B8nUcIF9dq81zl6pU";
		$url = url('default/renew');
		$data = array(
           'con'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A'),
           'date'=>array('value'=>'"06月07日 19时24分', 'color'=>'#CCCCCC')
      	);

		foreach ($result as $key => $value) {
			$this->sendtemplatemessage($data,$value,$templateId,$url);
		}
		//$this->sendtemplatemessage($touser);
		exit('success');
	}

	/**
	 * 发送模板消息
	 * @return [type] [description]
	 */
	function sendtemplatemessage($data,$touser, $templateId, $url){
		
		$this->jssdk->sendTemplateMessage($data, $touser, $templateId, $url);
	}

	/**
	 * 图书过期缴费通知，通过服务器计划调用book/booksfinenotice来执行
	 *
	 */
	function actionBooksfinenotice(){
		$res = $this->webservice->BooksFine();
		//var_dump($res);exit();
		if($res->BooksFineResult->Result){
			$fine = $res->OverRemindResult->Books->Fine;
			foreach ($fine as $key => $value) {
				$openid[] = $value->OpenID;
			}
		}
		$result = array_unique($openid);
		$touser = 'o33Vtv9Oewm2dckRj-AkksuwGGTM';
		$result[] = $touser;

		$templateId = "NtVIOn-MJoDNh8mfj6aLj65gs4B8nUcIF9dq81zl6pU";
		$url = url('default/booksfine');
		$data = array(
           'con'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A'),
           'date'=>array('value'=>'"06月07日 19时24分', 'color'=>'#CCCCCC')
      	);
		foreach ($result as $key => $value) {
			$this->sendtemplatemessage($data,$value,$templateId,$url);
		}
		//$this->sendtemplatemessage($touser);
		exit('success');
	}

	/**
	 * 图书过期缴费页面
	 * 已过期
	 * @return [type] [description]
	 */
	function actionBooksfine(){
		$booksfine = array(
			array('name'=>'骆驼祥子','enddate'=>'2016/12/05','gqdays'=>38,'pay'=>12.5),
			array('name'=>'水浒','enddate'=>'2016/12/05','gqdays'=>38,'pay'=>12.5),
		);
		$root_dir = Q::ini ( 'app_config/ROOT_DIR' );
		$this->_view['root_dir'] = $root_dir;
		$this->_view['title'] = '图书过期缴费';
		$this->_view['list'] = $booksfine;
	}



	/*
	* 续借
	* 已作废
	*/
	function actionRenew()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			//$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://live.mynep.com/weixin/index.php?action=bind&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$readercode = $user['readercode'];
		$fail = array();
		$success = array();
		$note = array();
		if($this->_context->isPOST()){
			//$code_str = $this->_context->borrow_id;
			//$code_arr = explode(',', $code_str);
			//foreach ($code_arr as $key => $value) {
				$barcode = $this->_context->barcode;
				$res = $this->webservice->RenewBook($readercode,$openid,$barcode);
				$q = $res->RenewBookResult;
				foreach ($q as $k => $v) {
					if($v->szReturn=="True"){
						$remark = $v->szRemark;
						$note[$barcode] =  $remark;
						$success[] = $barcode;
					}else{
						$remark = $v->szRemark;
						$note[$barcode] =  $remark;
						$fail[] = $barcode;
					}
				}
			//}
			//$url = url('default/borrowsearch');
			//return $this->_redirect($url);
		}
		$this->_view['fail'] = $fail;
		$this->_view['success'] = $success;
		$this->_view['note'] = $note;
		//$res = $this->webservice->QueryBorrowed($openid);
		//$list = array();
		//查询指定书本
		
		$barcode = $this->_context->barcode;
		$res = $this->webservice->QueryBorrowed($readercode,$openid);
		$list = array();
		//查询所有已借书本
		$q = $res->QueryBorrowedResult;
		if($q){
			foreach ($q as $key => $value) {
				if($value->szBarcode == $barcode){
					$info['barcode'] = $value->szBarcode;
					$info['title'] = $value->szTitle;
					$info['startdate'] = $value->dtStartDate;
					$info['enddate'] = $value->dtEndDate;
				}else{
					$info['remark'] = '借阅中无此书';
				}
				/*if($value->szReturn=="True"){
					$list[$key]['barcode'] = $value->szBarcode;
					$list[$key]['title'] = $value->szTitle;
					$list[$key]['startdate'] = $value->dtStartDate;
					$list[$key]['enddate'] = $value->dtEndDate;
				}elseif($value->szReturn=="False"){
					$info['remark'] = $value->szRemark;
					break;
				}*/
			}
		}
		$this->_view['info'] = $info;
		$this->_view['title'] = "续借";
		# code...
	}



}

