<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_Baoming extends Controller_Main
{
	//private $order_no = '';

	function actionIndex(){
		$this->_view['title'] = '地理位置';
		$signPackage = $this->jssdk->getSignPackage();
		$this->_view['signPackage'] = $signPackage;

	}

	/**
	 * 活动列表
	 * @return [type] [description]
	 */
	function actionPlanlist(){
		$list = Plan::find("is_delete = 0 ")->order('id desc')->limit(0, 5)->getAll();
		$this->_view['list'] = $list;
		$this->_view['title'] = '活动报名';
	}

	/**
	 * 活动详情
	 */
	function actionPlandetail(){
		$id = intval($this->_context->id);
		$userid = intval($this->_context->userid);
		$plandata = Plan::find('id = ?',$id)->getOne();
		$this->_view['plandata'] = $plandata;
		$this->_view['userid'] = $userid;
	}

	/**
	 * 动态加载
	 * @return [type] [description]
	 */
	function actionGetmore(){
		$page = intval($this->_context->page);
		$start = 6 * $page;
		$morelist = Plan::find("is_delete = 0")->order('id desc')->limit($start,5)->getAll();
		$this->_view['list'] = $morelist;
	}

	function actionUploadpic(){
		$api = $_GET['api'];
		if($this->_context->isPOST() && $api == 'upload'){
			$mediaId = $_POST['media_id'];
		    $file = $this->upload($mediaId);
		    if ($file) {
		      exit ($this->toJson(200, array('url' => $file)));
		    } else {
		      exit ($this->toJson(400, null, 'error'));
		    }
		}
	}


	/**
	 * 上传图片
	 * @param media_id
	 */
	function upload($media_id) {
	  //$access_token = get_access_token();
	  $access_token = $this->jssdk->getAccessToken();
	  if (!$access_token) return false;
	  $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
	  if (!file_exists('./upload/')) {
	      mkdir('./upload/', 0775, true); //将图片保存到upload目录
	  }
	  $fileName = date('YmdHis').rand(1000,9999).'.jpg';
	  $targetName = './upload/'. $fileName;

	  $ch = curl_init($url); 
	  $fp = fopen($targetName, 'wb'); 
	  curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_exec($ch);
	  curl_close($ch);
	  fclose($fp);

	  	/*$fp = fopen('./upload/aaa.txt', "w");
    	fwrite($fp, "<?php exit();?>" . $url);
    	fclose($fp);*/
	  return '/upload/'.$fileName; //输出文件名
	}

	/**
	 * 输出json
	 */
	function toJson ($code = 200, $data = array(), $message = 'success') {
	  return json_encode(array('code' => $code, 'data' => $data, 'message' => $message));
	}


	/*if (isset($_GET['api'])) {
	  $api = $_GET['api'];
	  //上传
	  if ($api == 'upload') {
	    $mediaId = $_POST['media_id'];
	    $file = upload($mediaId);
	    if ($file) {
	      exit (toJson(200, array('url' => $file)));
	    } else {
	      exit (toJson(400, null, 'error'));
	    }
	  }
	}*/

	function actionTest(){

		exit( Q::ini ( 'app_config/ROOT_DIR' ));
	}


	/**
	 * 表单提交
	 * @return [type] [description]
	 * 1: 职业资格鉴定
	 * 2: 商务委电子商务专业人才鉴定申请
	 * 3: 商务委电子商务培训报名
	 * 4: 教育局企业职工报名
	 */
	function actionSignup(){
		$this->_view['title'] = '报名';
		
		$tabletype = $this->_context->tabletype;
		$id = $this->_context->id;
		$userid = intval($this->_context->userid);
		$mid = $zsid = 0;
		$name = $mobile = '';
		if($this->_context->isPOST()){
			$name = $this->_context->name;
			$mobile = $this->_context->mobile;

			//同步插入member表
			$member = Member::find('tel = ?',$mobile)->getOne();
			$mid = $member['id'];
			//插入会员
			if(empty($mid)){
				$memberData = array('name'=>$name,'tel'=>$mobile,'source'=>$tabletype,'datetime'=>time());
				$member = new Member($memberData);
				$member->save();
				$mid = $member->id;
			}
			//插入招生信息
			//$zsid = 0;
			if($userid){
				$zsinfoData = array('plan_id'=>$id,'source'=>2,'zs_id'=>$userid,'mid'=>$mid);
				$zsinfo = new Zsinfo($zsinfoData);
				$zsinfo->save();
				$zsid = $zsinfo->id;
			}
			//设置登录状态
			/*$userarr = array(
				'id'   => $mid,
				'name' => $post['name'],
				'cid'  => $post['sfz'],
				'username'	=> $post['sfz'],
			);
			$userstring = json_encode($userarr);
			setcookie('user',$userstring,time()+3600*24*30*12,'/');*/
			$this->_view['name'] = $name;
			$this->_view['tel'] = $mobile;
			$this->_view['zsid'] = $zsid;
			$this->_view['mid'] = $mid;			
		}
		$userstr = @$_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
			if($user['id']){
				$member = Member::find('id = ?',$user['id'])->getOne();		
				$this->_view['member'] = $member;
				$cid = $member['cid'];
				$this->getSigupByCid($tabletype,$cid);
			}
		}        

		switch ($tabletype) {
			case '1':
				$this->table1();
				break;
			case '2':
				$this->table2($id,'plan');
				break;
			case '3':
				$this->table3();
				break;
			case '4':
				$this->table4();
				break;
			default:
				# code...
				$this->table1();
				break;
		}
		
		$signPackage = $this->jssdk->getSignPackage();
		$this->_view['signPackage'] = $signPackage;
		$this->_viewname = 'signup'.$tabletype;
		$this->_view['tabletype'] = $tabletype;
		$this->_view['plan_id'] = $id;
		$this->_view['userid'] = $userid;
	}

	function actionSignupBak(){
		$this->_view['title'] = '报名';
		
		$tabletype = $this->_context->tabletype;
		$id = $this->_context->id;
		$userid = intval($this->_context->userid);

		$userstr = @$_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
			if($user['id']){
				$member = Member::find('id = ?',$user['id'])->getOne();		
				$this->_view['member'] = $member;
				$cid = $member['cid'];
				$this->getSigupByCid($tabletype,$cid);
			}
		}        

		switch ($tabletype) {
			case '1':
				$this->table1();
				break;
			case '2':
				$this->table2($id,'plan');
				break;
			case '3':
				$this->table3();
				break;
			case '4':
				$this->table4();
				break;
			default:
				# code...
				$this->table1();
				break;
		}
		
		$signPackage = $this->jssdk->getSignPackage();
		$this->_view['signPackage'] = $signPackage;
		$this->_viewname = 'signup'.$tabletype;
		$this->_view['tabletype'] = $tabletype;
		$this->_view['plan_id'] = $id;
		$this->_view['userid'] = $userid;
	}

	function table1(){
		$exam_sources = Q::ini('appini/exam_sources');
		$sb_sources = Q::ini('appini/sb_sources');
		
		$this->_view['exam_sources'] = $exam_sources;
	}


	function table2($id,$type){
		$user_sources = Q::ini('appini/use_sources');
		$year = date('Y');
		for($i=$year;$i>1950;$i--){
			$showyears[] = $i;
		}
		for($i=1;$i<13;$i++){
			$showmonth[] = $i;
		}
		for($i=1;$i<32;$i++){
			$showday[] = $i;
		}
		if($type == 'view'){
			$sdata = Signup::find('id = ?',$id)->getOne();
			$this->_view['sdata'] = $sdata;
		}else{
			$plandata = Plan::find('id = ?',$id)->getOne();
			$this->_view['plandata'] = $plandata;
		}
		$this->_view['user_sources'] = $user_sources;
		$this->_view['years'] = $showyears;
		$this->_view['month'] = $showmonth;
		$this->_view['days'] = $showday;

	}

	function getSigupByCid($tabletype,$cid){
		$sigupinfo = array();
		switch ($tabletype) {
			case '1':
				
				break;
			case '2':
				$sigupinfo = Signup::find('sfz = ?',$cid)->order('id desc')->getOne();
				$this->_view['sigupinfo'] = $sigupinfo;
				break;
			case '3':
				
				break;
			case '4':
				
				break;
			default:
				# code...
				
				break;
		}
	}



	/**
	 * 表单提交
	 */
	function actionSubmitform(){
		if($this->_context->isPOST()){
			$post = $this->_context->post();
			$signup = new Signup($post);
			$signup->save();
			$sid = $signup->id;
			$jf = new Jf();
			//$jfscore = $jf->jfconfig();
			$jfscore = $post['jf'];
			//同步插入member表
			//$member = Member::find('cid = ?',$post['sfz'])->getOne();
			//$mid = $member['id'];
			$mid = $post['mid'];
			//插入会员
			$pass = substr($post['sfz'], -6,6);
			if(empty($mid)){
				
				$memberData = array('name'=>$post['name'],'cid'=>$post['sfz'],'birthday'=>$post['birthday'],'sex'=>$post['sex'],'nation'=>$post['nation'],'tel'=>$post['tel'],'username'=>$post['sfz'],'pass'=>$pass,'jf'=>$jfscore,'source'=>2,'sid'=>$sid,'datetime'=>time());
				$member = new Member($memberData);
				$member->save();
				$mid = $member->id;
			}else{
				$member = Member::find('id = ?',$mid)->getOne();
				$member->sid = $sid;
				$member->cid = $post['sfz'];
				$member->birthday = $post['birthday'];
				$member->sex = $post['sex'];
				$member->nation = $post['nation'];
				$member->username = $post['sfz'];
				$member->jf = $jfscore;
				$member->pass = $pass;
				$member->save();
			}								
			
			//var_dump($memberData);			
			//echo $mid;
			//积分插入
			$jfData = array('mid'=>$mid,'jf'=>$jfscore,'way'=>'register','datetime'=>time());
			$jf->saveData($jfData);

			
			//插入订单信息
			$order_no = '';
			if($mid){
				$plandata = Plan::find('id = ?',$post['plan_id'])->getOne();
				$now = time();
				$mnow = microtime();
				//$rcode = substr(date('Y',$now),2,2).date('md',$now).date('His',$now).substr($mnow, 2, 2);//年限
				//$this->order_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
				$order_no = substr(date('Y',$now),2,2).date('md',$now).date('His',$now).substr($mnow, 2, 2);//年限
				$price = $plandata['fee'];
				$order_time = time();
				$orderData = array('order_no'=>$order_no,'price'=>$price,'order_time'=>$order_time,'state'=>0,'mid'=>$mid,'plan_id'=>$post['plan_id'],'source'=>2,'sid'=>$sid);
				$order = new Order($orderData);
				$order->save();
				$orderid = $order->id;

				//更新报名表
				$signup = Signup::find('id = ?',$sid)->getOne();
				$signup->orderid = $orderid;
				$signup->save();
			}
			
			

			//插入招生信息
			$zsid = $post['zsid'];
			if($zsid){
				/*$zsinfoData = array('plan_id'=>$post['plan_id'],'source'=>2,'sid'=>$sid,'zs_id'=>$post['zs_id'],'mid'=>$mid);
				$zsinfo = new Zsinfo($zsinfoData);*/
				$zsinfo = Zsinfo::find('id = ?',$zsid)->getOne();
				$zsinfo->sid = $sid;
				$zsinfo->save();
			}
			//设置登录状态
			$userarr = array(
				'id'   => $mid,
				'name' => $post['name'],
				'cid'  => $post['sfz'],
				'username'	=> $post['sfz'],
			);
			$userstring = json_encode($userarr);
			setcookie('user',$userstring,time()+3600*24*30*12,'/');
			$json_return = json_encode(array('result'=>'success','trade_no'=>$order_no,'orderid'=>$orderid));
			echo $json_return;
			//var_dump($post);
			exit();
		}else{
			exit('failure');
		}
	}


	function actionCity(){
		$this->_view['title'] = '地理位置';
	}

	/**
	 * 报名信息
	 */
	function actionHddetail(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        
		$id = $this->_context->id;
		$tabletype = $this->_context->tabletype;
		switch ($tabletype) {
			case '1':
				$this->table1();
				break;
			case '2':
				$this->table2($id,'view');
				break;
			case '3':
				$this->table3();
				break;
			case '4':
				$this->table4();
				break;
			default:
				# code...
				$this->table1();
				break;
		}
		$this->_view['id'] = $id;
		$this->_viewname = 'hddetail'.$tabletype;
	}

	function actionClip(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $member = Member::find('id = ?',$user['id'])->getOne();
        $this->_view['member'] = $member;
	}

	function actionClipzj(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $member = Member::find('id = ?',$user['id'])->getOne();
        $this->_view['member'] = $member;
	}


	function actionUploadpicture(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $member = Member::find('id = ?',$user['id'])->getOne();
        $cid = $member['cid'];
		$data = $this->_context->dataURL;
		$jsid = $this->_context->jsid;
		$sfzpath = './upload/sfz/';
		$zjpath = './upload/zj/';
		switch ($jsid) {
			case 'js-previewz':
				$filename = $sfzpath.$cid."_z.jpg";
				$member->sfz_path = $sfzpath.$cid;
				break;
			case 'js-previewf':
				$filename = $sfzpath.$cid."_f.jpg";
				$member->sfz_path = $sfzpath.$cid;
				break;
			case 'js-zj1':
				$filename = $zjpath.$cid."_1.jpg";
				$member->pic_path = $zjpath.$cid;
				break;
			case 'js-zj2':
				$filename = $zjpath.$cid.".jpg";
				$member->pic_path = $filename;
				break;
			default:
				# code...
				break;
		}
		$member->save();
	    $image = base64_decode( str_replace('data:image/jpeg;base64,', '',$data));
	    //$filename = time()."_".rand(1,100).".jpg";
		//$filepath = './upload/'.$filename;
	    $this->save_to_file($image,$filename);
	    exit ($this->toJson(200, array('url' => $filename)));
	}

	function save_to_file($image,$filepath){   		
	    $fp = fopen($filepath, 'w');
	    fwrite($fp, $image);
	    fclose($fp);
	}

	/**
	 * 支付页面
	 */
	function actionPay(){
		$id = intval($this->_context->id);
		//$sdata = Plan::find('id = ?',$id)->getOne();
		$orderData = Order::find('id = ?',$id)->getOne();
		$this->_view['trade_no'] = $orderData['order_no'];
		$this->_view['orderData'] = $orderData;
	}

	/**
	 * 支付回调页面
	 */
	function actionCallback(){
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/notify.php';
		$dbo = QDB::getConn ();
		/*$sql= "update exam_admin set pass = '".$newpassword."'
        		where username = '".$username."'and pass = '".$passwd."' ";
		$dbo->execute ($sql);*/
		$notify = new PayNotifyCallBack();
		$notify->dbo = $dbo;
		$notify->Handle(false);
		//$notify->testdb('2018032412048');
	}

	/**
	 * 身份证验证
	 */
	function actionidcard(){
		$idcard = $_REQUEST["value"];
		//echo $idcard;
		//$valid = $this->validateIdCard($idcard);
		echo json_encode(
	    array(
	      "value" => $_REQUEST["value"],
	      "valid" => preg_match('/^\d{17}[0-9xX]$/', $idcard),
	      "message" => "请输入正确的身份证号码"
	    ));
		//var_dump($this->validateIdCard($idcard));
	}


	function validateIdCard($value)
	{
	    if (!preg_match('/^\d{17}[0-9xX]$/', $value)) { //基本格式校验
	        return false;
	    }
	 
	    $parsed = date_parse(substr($value, 6, 8));
	    if (!(isset($parsed['warning_count'])
	        && $parsed['warning_count'] == 0)) { //年月日位校验
	        return false;
	    }
	 
	    $base = substr($value, 0, 17);
	 
	    $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
	 
	    $tokens = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
	 
	    $checkSum = 0;
	    for ($i=0; $i<17; $i++) {
	        $checkSum += intval(substr($base, $i, 1)) * $factor[$i];
	    }
	 
	    $mod = $checkSum % 11;
	    $token = $tokens[$mod];
	 
	    $lastChar = strtoupper(substr($value, 17, 1));
	 
	    return ($lastChar === $token); //最后一位校验位校验
	}


}