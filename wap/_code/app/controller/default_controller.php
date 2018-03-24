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
        $list = Plan::find("is_delete = 0 ")->order('id desc')->limit(0, 5)->getAll();
		$this->_view['list'] = $list;
		$this->_view['title'] = '活动报名';
	}

	/**
	 * 动态加载
	 * @return [type] [description]
	 */
	function actionGetmore(){
		$page = intval($this->_context->page);
		$start = 5 * $page;
		$morelist = Plan::find("is_delete = 0")->order('id desc')->limit($start,5)->getAll();
		$this->_view['list'] = $morelist;
	}

	/**
	 *  登录
	 * @return [type] [description]
	 */
	function actionLogin(){
		$error = array(
			'username' 	=> '',
			'password' 	=> '',
			'message'	=> '',
		);
		if($this->_context->isPOST()){
			try
			{
				$username = $this->_context->username;
				$password = $this->_context->password;
				$user = Member::meta()->validateLogin($username, $password);
				//$aclData = $user->aclData();
				//$this->_app->changeCurrentUser($aclData, 'MEMBER');
				$userarr = array(
					'id'   => $user['id'],
					'name' => $user['name'],
					'cid'  => $user['cid'],
					'username'	=> $user['username'],
				);
				$userstring = json_encode($userarr);
				//var_dump($userarr);
				//exit();
				setcookie('user',$userstring,time()+3600*24*30*12,'/');
				return $this->_redirect( url('default/usercenter'));
			}
			catch (AclUser_UsernameNotFoundException $ex)
			{
				$error['message'] = "您输入的用户名 {$username} 不存在";
			}
			catch (AclUser_WrongPasswordException $ex)
			{
				$error['message'] = "您输入的密码不正确";
			}

		}
		$this->_view['error'] = $error;
	}

	/**
	 * 退出登录
	 * @return [type] [description]
	 */
	function actionLogout(){
		//$this->_app->cleanCurrentUser();
		setcookie("user", "", time()-3600,'/');
		return $this->_redirect( url('default/login'));
	}

	/**
	 * 招生人员登录
	 * @return [type] [description]
	 */
	function actionZslogin(){
		$error = array(
			'username' 	=> '',
			'password' 	=> '',
			'message'	=> '',
		);
		if($this->_context->isPOST()){
			try
			{
				$username = $this->_context->username;
				$password = $this->_context->password;
				$user = Admin::meta()->validateLogin($username, $password);
				//$aclData = $user->aclData();
				//$this->_app->changeCurrentUser($aclData, 'MEMBER');
				$userarr = array(
					'id'   => $user['id'],
					'name' => $user['name'],
					'username'	=> $user['username'],
				);
				$userstring = json_encode($userarr);
				//var_dump($userarr);
				//exit();
				setcookie('zsuser',$userstring,time()+3600*24*30*12,'/');
				return $this->_redirect( url('default/sharelist'));
			}
			catch (AclUser_UsernameNotFoundException $ex)
			{
				$error['message'] = "您输入的用户名 {$username} 不存在";
			}
			catch (AclUser_WrongPasswordException $ex)
			{
				$error['message'] = "您输入的密码不正确";
			}

		}
		$this->_viewname = 'login';
		$this->_view['error'] = $error;
	}

	/**
	 * 活动分享列表
	 * @return [type] [description]
	 */
	function actionSharelist(){
		$userstr = $_COOKIE['zsuser'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
		$list = Plan::find("is_delete = 0 ")->order('id desc')->limit(0, 5)->getAll();
		$this->_view['list'] = $list;
		$this->_view['title'] = '活动报名';	
	}

	function check(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
		//var_dump($_COOKIE);
        //var_dump($user);
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
	}

	/**
	 * 用户中心
	 */
	function actionUsercenter(){
		//$this->checkLogin();
		//$user = $this->_app->currentUser();
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        //return  $this->check();
		$member = Member::find('id = ?',$user['id'])->getOne();
		$this->_view['member'] = $member;
		if($this->_context->isPOST()){
			$name = $this->_context->name;
			$tel = $this->_context->phone;
			$cid = $this->_context->sfz;
			$member->cid = $cid;
			$member->name = $name;
			$member->tel = $tel;
			$member->save();
			return $this->_redirect( url('default/usercenter'));
		}

	}

	/**
	 * 我的活动
	 */
	function actionMyhd(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $hdarr = array();
        $signup = Signup::find('sfz = ?',$user['cid'])->order('id desc')->getAll();
        if(isset($signup)){
        	foreach ($signup as $key => $value) {
        		$hdarr[] = array(
        			'id' => $value['id'],
        			'img' => $value['plan']['img'],
        			'name' => $value['plan']['name'],
        			'enddate' => $value['plan']['enddate'],
        			'tabletype' => $value['plan']['tabletype'],
        			'plan_id' => $value['plan_id'],
        		);
        	}
        }
        //var_dump($hdarr);
        $this->_view['list'] = $hdarr;
	}

	/**
	 * 我的成绩
	 */
	function actionMyscore(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $hdarr = array();
        $signup = Signup::find('sfz = ?',$user['cid'])->order('id desc')->getAll();
        if(isset($signup)){
        	foreach ($signup as $key => $value) {
        		$hdarr[] = array(
        			'id' => $value['id'],
        			'img' => $value['plan']['img'],
        			'name' => $value['plan']['name'],
        			'enddate' => $value['plan']['enddate'],
        			'tabletype' => $value['plan']['tabletype'],
        			'plan_id' => $value['plan_id'],
        			'score' => $value['score'],
        			'teacher_id' => $value['plan']['teacher_id'],
        			'course_id' => $value['plan']['course_id'],
        			'fee' => $value['plan']['fee'],
        			'is_pay' => $value['is_pay'],
        			'orderid' => $value['orderid'],
        		);
        	}
        }
        //var_dump($hdarr);
        $this->_view['list'] = $hdarr;
	}

	/**
	 * 课程评分
	 * @return [type] [description]
	 */
	function actionSetmark(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $teacher_id = $this->_context->teacher_id;
		$course_id = $this->_context->course_id;
		if($this->_context->isPOST()){
			
			$course_score = $this->_context->course_score;
			$teacher_score = $this->_context->teacher_score;
			$message = $this->_context->message;
			$markdata = array(
				'mid' => $user['id'],
				'course_score' => $course_score,
				'teacher_score' => $teacher_score,
				'course_id' => $course_id,
				'teacher_id' => $teacher_id,
				'message' => $message,
				'datetime' => time()
			);
			$mark = new Mark($markdata);
			$mark->save();
			return $this->_redirect( url('default/myscore'));
		}
		$markarr = Mark::find("mid = ? and teacher_id = ? and course_id",$user['id'],$teacher_id,$course_id)->getOne()->toarray();
		//var_dump($markarr);
		$this->_view['markarr'] = $markarr;
	}


	/**
	 * 证书领取方式
	 */
	function actionReceivecard(){
		$userstr = $_COOKIE['user'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        //return  $this->check();
		$member = Member::find('id = ?',$user['id'])->getOne();
		$this->_view['member'] = $member;
		if($this->_context->isPOST()){
			$getway = $this->_context->getway;
			$name = $this->_context->name;
			$phone = $this->_context->phone;
			$address = $this->_context->address;
			$member->getway = $getway;
			$member->address = $address;
			$member->express_name = $name;
			$member->express_tel = $phone;
			$member->save();
			return $this->_redirect( url('default/receivecard'));
		}
	}

	/**
	 * 分享二维码
	 * @return [type] [description]
	 */
	function actionQrcode(){
		$userstr = $_COOKIE['zsuser'];
		if($userstr){
			$user = json_decode($userstr,true);
		}
        if(!isset($user['id'])){
            return $this->_redirect( url('default/login'));
        }
        $id = $this->_context->id;
        $tabletype = $this->_context->tabletype;
        $userid = $user['id'];
        $code = url('baoming/signup',array('id'=>$id,'tabletype'=>$tabletype,'userid'=>$userid));
        $this->_view['code'] = $code;
		//$code = 
		//
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/jssdk.php';
    	$appid = 'wx6f15d9b62099ce85';
    	$appsecret = 'd22be8116e26f564cf3fd0289a5caef2';
        $jssdk = new JSSDK($appid, $appsecret);
        $signPackage = $jssdk->GetSignPackage();
        $this->_view['signPackage'] = $signPackage;

        $plandata = Plan::find('id = ?',$id)->getOne();
        $this->_view['wximg'] = 'http://www.zjnep.com/sims'.$plandata['img'];
		$this->_view['desc_short'] = $plandata['name'];
		$this->_view['link'] = '';
	}


}

