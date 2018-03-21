<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */
class Controller_News extends Controller_Main
{

	function actionIndex(){
		$list = News::find("is_delete = 0 ")->order('id desc')->limit(0, 5)->getAll();
		//$typearr = array('1'=>'讲座预告','2'=>'新书通报','3'=>'好书推荐','4'=>'最新公告','5'=>'馆内动态');
		$this->_view['list'] = $list;
	}

	function actionGethd(){
		$page = intval($this->_context->page);
		$start = 5 * $page;
		$huodong = News::find("is_delete = 0")->order('id desc')->limit($start,5)->getAll();
		$this->_view['list'] = $huodong;
	}

	/**
	 * 
	 * @return [type] [description]
	 */
	function actionDetail(){
		$id = $this->_context->id;
		$info = News::find("is_delete = 0 and id =?",$id)->getOne();
		$this->_view['info'] = $info;
	}


	
}

