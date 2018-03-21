<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_Default extends Controller_Main
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
   		$_SESSION['user'] = array('openid'=>'oubR8vyAe68-MkheWxwpbOxipVn4','readercode'=>'800001');
	}*/

	function actionIndex()
	{
        // 为 $this->_view 指定的值将会传递数据到视图中
		# $this->_view['text'] = 'Hello!';
		//$where = " isdelete = 0";
		//$news = News::find($where)->limit(0,15)->getAll();
		//$this->_view['news'] = "";
		//var_dump($_SESSION);
		//$res = $this->webservice->ReaderBinding('111','111','111');
		//$res = $this->webservice->Recommend('openid','荐购图书');
		//var_dump($res);
		var_dump(@$_SESSION['user']);
		exit();
	}

	function actionTest(){
		
   		$textXml = "<xml>
		<szName><![CDATA[标准日语上]]></szName>
		<szAuthor><![CDATA[]]></szAuthor>
		<szISBN></szISBN>
		<szbookid></szbookid>
		</xml>";
		//$ws = "http://125.64.92.82:8181/SWLibServie.asmx?WSDL";
		$ws = "http://125.64.92.82:8181/libservices.asmx?WSDL";
		//$ws = "http://121.40.224.47:81/WS_Library.asmx?WSDL";
   		$client = new SoapClient($ws);
		$textXml = '<?xml version="1.0" encoding="GB2312"?><root><tab><key>szName</key><value>IELTS</value></tab></root>';
		$textXml = addslashes($textXml);
		//echo $textXml;
   		//$res = $client->SearchBook("<root><tab><key>szName</key><value>IELTS</value></tab></root>");
   		/*$res = $client->SelectReader('<?xml version="1.0" encoding="GB2312"?><root><tab><key>szReaderID</key><value>888888</value></tab></root>');*/
   		//$res = $client->TestDataBase();
   		//$res = $client->StrReturn(array('str1'=>'asdfasdf'));
   		//$res = $client->SearchBook(array('barcode'=>'','title'=>'IELTS','author'=>'','isbn'=>''));
   		//var_dump($client->__getFunctions()); 
   		//var_dump($client-)
   		//var_dump($res);
   		var_dump($this->webservice->showFunction());
   		echo 'aa';
	}


	/*读者绑定*/
	function actionBind()
	{
		# code...
		//$res = $this->webservice->BookPosition('aaaa');
		$code = $this->_context->code;
		$type = $this->_context->type;
		$this->_view['title'] = '读者绑定';
		//unset($_SESSION['user']);
		var_dump(@$_SESSION['user']);

		//提交绑定
		$openid = '';
		$info = array('status'=>'0');
		if($this->_context->isPOST() && $type == 'bind'){
			$readercode = $this->_context->readercode;
			$pwd = $this->_context->pwd;
			$openid = $this->_context->openid;
			$res = $this->webservice->ReaderBinding($readercode,$pwd,$openid);
			/*var_dump($res);
			echo "<br>$readercode-$pwd-$openid";*/
			if($res->ReaderBindingResult->OperatorResult->szReturn=='True'){
				$_SESSION['user'] = array('openid'=>$openid,'readercode'=>$readercode);
				$info = array('status'=>'1');
			}else{
				$info = array('remark'=>$res->ReaderBindingResult->OperatorResult->szRemark,'status'=>'2');
			}

		}
		$this->_view['info'] = $info;

		//解除绑定
		if($this->_context->isPOST() && $type == 'removebind'){
			//调用webservice接口，解除绑定
			if(isset($_SESSION['user']['openid'])){
				$openid = $_SESSION['user']['openid'];
				$res = $this->webservice->ReaderRemoveBinding($openid);
				
				echo '解绑';
			}
			unset($_SESSION['user']);
		}
		echo @$openid;
		var_dump(@$_SESSION['user']);
		if(isset($_SESSION['user'])){
			$this->_view['readercode'] = $_SESSION['user']['readercode'];
			$this->_viewname = 'removebind';
		}else{
			if(!$openid && $code){
				//echo $code;
				$wxuserjson = $this->getopenid($code);
				//var_dump($wxuserjson);
				$wxuser = json_decode($wxuserjson);
				if(isset($wxuser)){					
					$openid = $wxuser->openid;
				}
			}
			// $openid = '1111';
			// $res = $this->webservice->CreatedQRCode($openid);
			// if($res->CreatedQRCodeResult->CreatedQRResult->szReturn=='True'){
			// 	$_SESSION['user'] = array('openid'=>$openid,'readercode'=>$res->CreatedQRCodeResult->CreatedQRResult->szReaderCode);
			// 	$info = array('status'=>'1');
			// }
			//echo $openid.$code;
			$this->_view['openid'] = $openid;
			$this->_viewname = 'bind';
		}
	}
	/*读者证*/
	function actionCard()
	{
		# code...
		//var_dump($_SESSION['user']);
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		//var_dump($bind);
		//var_dump($_SESSION['user']);
		if(!$bind){
			//$url = "&response_type=code&scope=SCOPE&state=STATE#wechat_redirect";
			//return $this->_redirect(url('default/bind',array('code'=>$code,'response_type'=>'code','scope'=>'SCOPE','state'=>'STATE#wechat_redirect')));
			//$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=http://live.mynep.com/weixin/index.php?action=bind&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
			//return $this->_redirect($url);
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$res = $this->webservice->CreatedQRCode($openid);
		$info['status'] = '0';
		$code = "";
		if($res->CreatedQRCodeResult->CreatedQRResult->szReturn=='True'){
			$info['name'] = $res->CreatedQRCodeResult->CreatedQRResult->szName;
			$info['readercode'] = $res->CreatedQRCodeResult->CreatedQRResult->szReaderCode;
			$info['status'] = '1';
			$time = time()+60;
			$code = $info['readercode'].'|'.$time;
		}else{
			$info['remark'] = $res->CreatedQRCodeResult->CreatedQRResult->szRemark;
		}
		$this->_view['info'] = $info;
		$this->_view['code'] = $code;
		$this->_view['title'] = '电子证';
	}
	/*借*/
	function actionBorrow()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$status = "";
		$text = "";
		$info = array();
		$list = array();
		$barcode = $this->_context->barcode;
		if($this->_context->isPOST()&&!$status){
			$res = $this->webservice->BorrowBook($openid,$barcode);
			foreach ($res->BorrowBookResult as $key => $value) {
				# code...
				if($value->szReturn=="True"){
					$status = '1';
					$text = '借书成功';
					$info['barcode'] = $value->szBarCode;
					$info['title'] = $value->szTitle;
					$info['author'] = $value->szAuthor;
					$info['enddate'] = $value->dtEndDate;
				}elseif($value->szReturn=="False"){
					$status = '2';
					if(!empty($value->szRemark)){
						$info['remark'] = $value->szRemark;
					}
					$text = '借书失败';
				}
			}
		}else{
			//echo $barcode;
			$res = $this->webservice->SearchBook($barcode,'','','');
			//如果返回的是多条数据
			$q = $res->SearchBookResult;
			if($q){
				foreach ($q as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['author'] = $value->szAuthor;
					}elseif($value->szReturn=="False"){
						$info['remark'] = $value->szRemark;
						break;
					}
				}
			}
		}
		# code...
		$this->_view['title'] = '我要借书';
		$this->_view['text'] = $text;
		$this->_view['list'] = $list;
		$this->_view['info'] = $info;
		$this->_view['status'] = $status;
		$this->_view['barcode'] = $barcode;
	}
	/*借阅查询（查询已借书本）*/
	function actionBorrowSearch()
	{
		//dump($user);
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$res = $this->webservice->QueryBorrowed($openid);
		//$list = $this->ObjectToArray($res);
		$list = array();
		$info = array('remark'=>'无数据');
		$q = $res->QueryBorrowedResult;
		//var_dump($q);
		if(!empty($q->QueryResult)){
			if(@$q->QueryResult->szReturn == 'True'){
				$list[0]['barcode'] = $q->QueryResult->szBarcode;
				$list[0]['title'] = $q->QueryResult->szTitle;
				$list[0]['startdate'] = $q->QueryResult->dtStartDate;
				$list[0]['enddate'] = $q->QueryResult->dtEndDate;
			}else{
				foreach ($q->QueryResult as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['startdate'] = $value->dtStartDate;
						$list[$key]['enddate'] = $value->dtEndDate;
					}/*elseif($value->szReturn=="False"){
						$info['remark'] = $value->szRemark;
						break;
					}*/
				}
			}
			
		}else{
			$info['remark'] = '暂无数据';
		}
		$this->_view['list'] = $list;
		$this->_view['info'] = $info;
		$this->_view['title'] = "借阅查询";
		# code...
	}
	/*续借*/
	function actionRenew()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$fail = array();
		$success = array();
		$note = array();
		if($this->_context->isPOST()){
			$code_str = $this->_context->borrow_id;
			$code_arr = explode(',', $code_str);
			foreach ($code_arr as $key => $value) {
				$res = $this->webservice->RenewBook($openid,$value);
				$q = $res->RenewBookResult;
				foreach ($q as $k => $v) {
					if($v->szReturn=="True"){
						$remark = $v->szRemark;
						$note[$value] =  $remark;
						$success[] = $value;
					}else{
						$remark = $v->szRemark;
						$note[$value] =  $remark;
						$fail[] = $value;
					}
				}
			}
		}
		$this->_view['fail'] = $fail;
		$this->_view['success'] = $success;
		$this->_view['note'] = $note;
		$res = $this->webservice->QueryBorrowed($openid);
		$list = array();
		//查询所有已借书本
		$q = $res->QueryBorrowedResult;
		if(!empty($q->QueryResult)){
			if(@$q->QueryResult->szReturn == 'True'){
				$list[0]['barcode'] = $q->QueryResult->szBarcode;
				$list[0]['title'] = $q->QueryResult->szTitle;
				$list[0]['startdate'] = $q->QueryResult->dtStartDate;
				$list[0]['enddate'] = $q->QueryResult->dtEndDate;
			}else{
				foreach ($q->QueryResult as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['startdate'] = $value->dtStartDate;
						$list[$key]['enddate'] = $value->dtEndDate;
					}/*elseif($value->szReturn=="False"){
						$info['remark'] = $value->szRemark;
						break;
					}*/
				}
			}		
		}else{
			$info['remark'] = '暂无数据';
		}
		$this->_view['list'] = $list;
		$this->_view['title'] = "续借";
		# code...
	}
	/*转借*/
	function actionLent()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$res = $this->webservice->QueryBorrowed($openid);
		$list = array();
		//选中书本转借
		if($this->_context->isPOST()){
			$barcode = $this->_context->barcode;
			return $this->_redirect(url('default/lentcode',array('barcode'=>$barcode)));
		}
		$info = array('remark'=>'无数据');
		//查询所有已借书本
		$q = $res->QueryBorrowedResult;
		//var_dump($q);
		if(!empty($q->QueryResult)){
			if(@$q->QueryResult->szReturn == 'True'){
				$list[0]['barcode'] = $q->QueryResult->szBarcode;
				$list[0]['title'] = $q->QueryResult->szTitle;
				$list[0]['startdate'] = $q->QueryResult->dtStartDate;
				$list[0]['enddate'] = $q->QueryResult->dtEndDate;
			}else{
				foreach ($q->QueryResult as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['startdate'] = $value->dtStartDate;
						$list[$key]['enddate'] = $value->dtEndDate;
					}/*elseif($value->szReturn=="False"){
						$info['remark'] = $value->szRemark;
						break;
					}*/
				}
			}		
		}else{
			$info['remark'] = '暂无数据';
		}
		
		$this->_view['info'] = $info;
		$this->_view['list'] = $list;
		$this->_view['title'] = "转借";
		# code...
	}
	/*转借二维码（点击转接，生成二维码，他人通过扫描二维码转借）*/
	function actionLentCode()
	{
		$code = $this->_context->code;
		$barcode = $this->_context->barcode;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$info['status'] = '0';
		$res = $this->webservice->CreatedQRCode($openid);
		$q = $res->CreatedQRCodeResult;
		foreach ($q as $key => $value) {
			if($value->szReturn=="True"){
				$info['readercode'] = $value->szReaderCode;
				$info['name'] = $value->szName;
				$info['status'] = '1';
			}else{
				$this->_view['remark'] = "没有查询到这本书";
			}
		}
		
		$time = time()+60;
		$this->_view['info'] = $info;
		$this->_view['barcode'] = $barcode;

		//$this->_view['code'] = '10.82.97.236'.url('default/RenewResult',array('barcode'=>$barcode));
		$this->_view['code'] = $barcode.'|'.$user['readercode'];
		$this->_view['title'] = "二维码";
		# code...
	}
	/*图书查询*/
	function actionSearch()
	{
		$this->_view['title'] = "图书查询";
		# code...
	}

	/*图书预约*/
	function actionSearch1()
	{
		$this->_view['title'] = "图书预约";
		# code...
	}
	/*图书查询结果*/
	function actionResult()
	{
		$code = $this->_context->code;
		$barcode = $this->_context->barcode;
		// $bind = $this->checkBind($code);
		// if(!$bind){
		// 	return $this->_redirect(url('default/bind',array('code'=>$code)));
		// }
		$barcode = $this->_context->code;
		$title = $this->_context->title;
		$author = $this->_context->author;
		$isbn = $this->_context->num;

		$res = $this->webservice->SearchBook($barcode,$title,$author,$isbn);
		$q = $res->SearchBookResult;
		//var_dump($res);
		$list = array();
		$info = array();
		//如果返回的是多条数据
		/*if(!empty($res->SearchBookResult)){
			$q = $res->SearchBookResult;
			foreach ($q as $key => $value) {
				$list[$key]['barcode'] = $value->BARCODE;
				$list[$key]['title'] = $value->TITLE;
				$list[$key]['author'] = $value->AUTHOR;
			}
		}*/
		if(!empty($q->BookInfo)){
			if(@$q->BookInfo->szReturn == 'True'){
				$list[0]['barcode'] = $q->BookInfo->szBarcode;
				$list[0]['title'] = $q->BookInfo->szTitle;
				$list[0]['author'] = $q->BookInfo->szAuthor;
			}else{
				foreach ($q->BookInfo as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['author'] = $value->szAuthor;
					}
				}
			}
			
		}else{
			$info['remark'] = '暂无数据';
		}

		/*foreach ($res->SearchBookResult as $key => $value) {
			if($value->szReturn=="True"){
				$list[$key]['barcode'] = $value->szBarcode;
				$list[$key]['title'] = $value->szTitle;
				$list[$key]['author'] = $value->szAuthor;
			}else{
				$info['remark'] = $value->szRemark;
				break;
			}
			# code...
		}*/
		$this->_view['title'] = "查询结果";
		$this->_view['list'] = $list;
		$this->_view['info'] = $info;
		# code...
	}

		/*图书预约查询结果*/
	function actionResult1()
	{
		$code = $this->_context->code;
		$barcode = $this->_context->barcode;
		// $bind = $this->checkBind($code);
		// if(!$bind){
		// 	return $this->_redirect(url('default/bind',array('code'=>$code)));
		// }
		$barcode = $this->_context->code;
		$title = $this->_context->title;
		$author = $this->_context->author;
		$isbn = $this->_context->num;

		$res = $this->webservice->SearchBook($barcode,$title,$author,$isbn);
		$list = array();
		$info = array();
		//如果返回的是多条数据
		/*if(!empty($res->SearchBookResult)){
			$q = $res->SearchBookResult;
			foreach ($q as $key => $value) {
				$list[$key]['barcode'] = $value->BARCODE;
				$list[$key]['title'] = $value->TITLE;
				$list[$key]['author'] = $value->AUTHOR;
			}
		}*/
		foreach ($res->SearchBookResult as $key => $value) {
			if($value->szReturn=="True"){
				$list[$key]['barcode'] = $value->szBarcode;
				$list[$key]['title'] = $value->szTitle;
				$list[$key]['author'] = $value->szAuthor;
			}else{
				$info['remark'] = $value->szRemark;
				break;
			}
			# code...
		}
		$this->_view['title'] = "查询结果";
		$this->_view['list'] = $list;
		$this->_view['info'] = $info;
		# code...
	}
	/*图书定位结果*/
	function actionPosition()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind'));
			return $this->_redirect($this->url);
		}
		$barcode = $this->_context->barcode;
		$status = "我要预约";
		if($_POST){
			$user = $_SESSION['user'];
			//$barcode = $this->_context->barcode;
			$openid = $user['openid'];
			$res = $this->webservice->BooksBespeaking($openid,$barcode);
			$status = $res->BooksBespeakingResult->Discription;
		}
		$res = $this->webservice->BookPosition($barcode);
		//var_dump($res);
		$info = array();
		if($res->BookPositionResult->PositionResult->szReturn == 'True'){
			$info = array(
				'barcode'=>$res->BookPositionResult->PositionResult->szBarCode,
				'title'=>$res->BookPositionResult->PositionResult->szTitle,
				'author'=>$res->BookPositionResult->PositionResult->szAuthor,
				'state'=>$res->BookPositionResult->PositionResult->szState,
				'position'=>$res->BookPositionResult->PositionResult->szPosition,
				'return'=>true
			);
		}else{
			$info = array('remark'=>$res->BookPositionResult->PositionResult->szRemark,'return'=>false);
		}
		$this->_view['info'] = $info; 
		$this->_view['title'] = "定位结果";
		$this->_view['status'] = $status;
		# code...
	}

	/*图书定位结果*/
	function actionPosition1()
	{
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind'));
			return $this->_redirect($this->url);
		}
		$barcode = $this->_context->barcode;
		$status = "我要预约";
		if($_POST){
			$user = $_SESSION['user'];
			//$barcode = $this->_context->barcode;
			$openid = $user['openid'];
			$res = $this->webservice->BooksBespeaking($openid,$barcode);
			$status = $res->BooksBespeakingResult->Discription;
		}
		$res = $this->webservice->BookPosition($barcode);
		//var_dump($res);
		$info = array();
		if($res->BookPositionResult->PositionResult->szReturn == 'True'){
			$info = array(
				'barcode'=>$res->BookPositionResult->PositionResult->szBarCode,
				'title'=>$res->BookPositionResult->PositionResult->szTitle,
				'author'=>$res->BookPositionResult->PositionResult->szAuthor,
				'state'=>$res->BookPositionResult->PositionResult->szState,
				'position'=>$res->BookPositionResult->PositionResult->szPosition,
				'return'=>true
			);
		}else{
			$info = array('remark'=>$res->BookPositionResult->PositionResult->szRemark,'return'=>false);
		}
		$this->_view['info'] = $info; 
		$this->_view['title'] = "定位结果";
		$this->_view['status'] = $status;
		# code...
	}

	/*转借结果*/
	function actionRenewResult()
	{
		$code = $this->_context->code;
		$barcode = $this->_context->barcode;
		$readercode = $this->_context->readercode;
		$bind = $this->checkBind($code);
		if(!$bind){
			//return $this->_redirect(url('default/bind',array('code'=>$code)));
			return $this->_redirect($this->url);
		}
		$user = $_SESSION['user'];
		$openid = $user['openid'];
		$remark = "请正确打开此链接";
		$info = array('remark'=>'请正确打开此链接','status'=>'2');
		$list = array();
		if($openid){
			$res = $this->webservice->Subtenancy($openid,$readercode,$barcode); 
			//var_dump($res);
			if($res->SubtenancyResult){
				foreach ($res->SubtenancyResult as $key => $value) {
					# code...
					$info['remark'] = $value->szRemark;
					if($value->szReturn=="True"){
						$info['status'] = '1';
					}else{
						$info['status'] = '0';
					}
				}
			}
			$res = $this->webservice->SearchBook($barcode,'','','');
			$q = $res->SearchBookResult;
			if($q){
				foreach ($q as $key => $value) {
					if($value->szReturn=="True"){
						$list[$key]['barcode'] = $value->szBarcode;
						$list[$key]['title'] = $value->szTitle;
						$list[$key]['author'] = $value->szAuthor;
					}elseif($value->szReturn=="False"){
						$info['remark'] = $value->szRemark;
						break;
					}
				}
			}
		}
		$this->_view['list'] = $list;
		$this->_view['info'] = $info;
		$this->_view['title'] = "转借结果";
		# code...
	}


	/**
	 * 根据获取的数据对象转换成数组
	 * @return [type] [description]
	 */
	function ObjectToArray($list){
		$arr = array();
		foreach ($list as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				foreach ($value2 as $key3 => $value3) {
					$v_rr = array();
					foreach ($value3 as $k => $v) {
						$key = strtolower($k);
						$v_rr[$key] = $v;
					}
					$arr[$key3] = (array)$v_rr;
				}
			}
		}
		return $arr;
	}



	function actionAa(){
		$ws = "http://121.40.224.47:81/WS_Library.asmx?WSDL";
   		$client = new SoapClient($ws);
   		dump($client);
   		$str = array('str'=>1);
		$res = $client->StrReturn($str);
		//echo 1;
		dump($res);
	}

	function actionAbout(){
		$title = '立芯RFID';
		$content = 'about 立芯';
		$this->_view['title'] = $title;
		$this->_view['content'] = $content;
	}
	//新书通报列表
	function actionNotice(){
		$list = Notice::find()->order('id desc')->getAll();
		$this->_view['title'] = '新书通报';
		$this->_view['list'] = $list;
	}
	//新书列表
	function actionBook(){
		$id = $this->_context->id;
		$book = Books::find('n_id=?',$id)->getAll();
		$this->_view['title'] = '书本列表';
		$this->_view['list'] = $book;
	}
	function actionBooklent(){
		$id = $this->_context->id;
		$book = Books::find('id=?',$id)->getOne();
		if($_POST){
			echo 1;
			exit;
		}
		$this->_view['title'] = '书本列表';
		$this->_view['book'] = $book;
	}
	//新书预约
	function actionInfo(){
		$id = $this->_context->id;
		$book = Books::find('id=?',$id)->getOne();
		$status = "我要预约";
		if($_POST){
			$user = $_SESSION['user'];
			$barcode = $this->_context->barcode;
			$openid = $user['openid'];
			$res = $this->webservice->BooksBespeaking($openid,$barcode);
			$status = $res->BooksBespeakingResult->Discription;
		}
		$this->_view['title'] = '新书详细';
		$this->_view['book'] = $book;
		$this->_view['status'] = $status;
	}
	//允许转借条件
	function actionTurnborrow(){
		$id = $this->_context->id;
		//$res = $this->webservice->BooksBespeaking($openid,$barcode);
		return '1';
	}


	/**
	 * 图书过期缴费页面
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

	//我的图书预约
	function actionBespeak(){
		$this->_view['title'] = '图书预约';
	}

	function actionCancel(){
		$barcode = $this->_context->barcode;
		return $this->_redirect(url('/bespeak'));
	}
	function actionArea(){
		$floot = $this->_context->floot;
		$floot = empty($floot)?"2":$floot;
		$list = array();
		/*$a = $this->webservice->QueryFloor();
		dump($a);*/
		$res = $this->webservice->QueryBlock($floot);
		if($res->QueryBlockResult->Result == 'true'){
			if(!empty($res->QueryBlockResult->Blocks)){
				$list = $res->QueryBlockResult->Blocks;
			}else{
				$list[] = $res->QueryBlockResult->Block;
			}
		}
		$this->_view['title'] = '图书馆';
		$this->_view['floot'] = $floot;
		$this->_view['list'] = $list;
	}
	function actionShelf()
	{
		$floot = $this->_context->floot;
		$blockno = $this->_context->blockno;
		if(empty($blockno)){
			return $this->_redirect(url('/area',array('floot'=>$floot)));
		}
		$floot = empty($floot)?"2":$floot;
		$list = array();
		//$res = $this->webservice->QueryFloor('8');
		$res = $this->webservice->QueryBookshelf($blockno);
		if($res->QueryBookshelfResult->Result == 'true'){
			if(!empty($res->QueryBookshelfResult->Bookshelfs)){
				$list = $res->QueryBookshelfResult->Bookshelfs;
			}else{
				$list[] = $res->QueryBookshelfResult->Bookshelf;
			}
		}
		$this->_view['title'] = '书架';
		$this->_view['floot'] = $floot;
		$this->_view['list'] = $list;
	}
	function actionShelfBooks()
	{
		$floot = $this->_context->floot;
		$shelfno = $this->_context->shelfno;
		if(empty($shelfno)){
			return $this->_redirect(url('/area',array('floot'=>$floot)));
		}
		$floot = empty($floot)?"2":$floot;
		$list = array();
		$res = $this->webservice->QueryBookshelfBooks($shelfno);
		//dump($res->QueryBookshelfBooksResult->Books->Book);
		if($res->QueryBookshelfBooksResult->Result == 'true'){
			if(!empty($res->QueryBookshelfBooksResult->Books)){
				$list = $res->QueryBookshelfBooksResult->Books->Book;
			}else{
				dump($res->QueryBookshelfBooksResult->Book);
				$list[] = $res->QueryBookshelfBooksResult->Book;
			}
		}
		$this->_view['title'] = '书架图书';
		$this->_view['floot'] = $floot;
		$this->_view['list'] = $list;
	}
}

