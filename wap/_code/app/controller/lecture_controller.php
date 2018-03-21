<?php
// $Id$

/**
 * Controller_Class 控制器
 */
class Controller_Lecture extends Controller_Main
{

	function actionList(){
		$time = time();
		$sql = 'datetime >'.$time;
		//echo $sql;
		//$sql = '';
		$list = Lecture::find($sql)->order('id desc')->getAll();
		//var_dump($list);
		$this->_view['list'] = $list;
		$this->_view['title'] = '讲座预定';
		/*if(!empty($_SESSION['user'])){
			$openid = empty($_SESSION['user'])?"":$_SESSION['user']['openid'];
			$log = new Log();
			$log['content'] = '查看讲座预定';
			$log['memo'] = '查看讲座预定';
			$log['time'] = time();
			$log['userid'] = $openid;
			$log->save();
		}*/
		$this->logsave('查看讲座预定','查看讲座预定');
	}
	
	function actionInfo(){
		$code = $this->_context->code;
		$bind = $this->checkBind($code);
		if(!$bind){
			return $this->_redirect($this->url);
		}
		$readercode = $_SESSION['user']['readercode'];
		$id = $this->_context->id;
		$status = false;
		$text = "我要预约";
		$info = Lecture::find('id=?',$this->_context->id)->getOne();
		$book = Book::find('lecture_id =? and readercode=?',$id,$readercode)->getOne();
		if($info['datetime']<time()){
			$status = true;
			$text = "已结束";
		}elseif(!empty($book['id'])){
			$status = true;
			$text = "预约成功";
		}
		$card_id = $info['card_id'];
		$signPackage = $this->jssdk->getSignPackage();
        $cardSignPackage = $this->jssdk->getCardSignPackage($card_id);
        $this->_view['signPackage'] = $signPackage;
        $this->_view['cardSignPackage'] = $cardSignPackage;

		$this->_view['title'] = '讲座详情';
		$this->_view['info'] = $info;
		$this->_view['text'] = $text;
		$this->_view['status'] = $status;

	}
	function actionBook(){
		$id = $this->_context->id;
		$readercode = $_SESSION['user']['readercode'];
		$lecture = Lecture::find('id=?',$this->_context->id)->getOne();
		$book = Book::find('lecture_id =? and readercode=?',$id,$readercode)->getOne();
		if(empty($book['id'])){
			$data = new Book();
			$data->lecture_id = $id;
			$data->readercode = $readercode;
			$data->datetime = time();
			$data->save();
			return "1";
		}else{
			return "0";
		}
		return "0";
	}

	function actionMybook(){
		$readercode = $_SESSION['user']['readercode'];
		$db = QDB::getConn();
        $sql = "SELECT  * FROM lecture_book as b LEFT JOIN lecture as l ON b.lecture_id = l.id where b.readercode = ".$readercode." order by l.datetime desc";
        $book = $db->getAll($sql);
        /*$signPackage = $this->jssdk->getSignPackage();
        $cardSignPackage = $this->jssdk->getCardSignPackage('p33VtvxqxW4FCiQgnsn4nxoVq3bE');
        //var_dump($cardSignPackage);
        $this->_view['signPackage'] = $signPackage;
        $this->_view['cardSignPackage'] = $cardSignPackage;*/
		$this->_view['title'] = '我的订票';
		$this->_view['book'] = $book;
	}
	function actionCancel(){
		$id = $this->_context->id;
		$db = QDB::getConn();
        $sql = "DELETE  FROM lecture_book WHERE id = ".$id;
        $db->execute($sql);
        return $this->_redirect(url('/mybook'));
	}
} 
