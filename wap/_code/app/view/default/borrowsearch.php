<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
	<?//dump($list);?>
	<?foreach($list as $key => $value){?>
		<div class="wechat_lbg" style="padding-bottom: 10px;">
	        <div class="bbg_con">
	            <span>条码号：<?=$value['barcode']?></span>
	            <span>题名：<?=$value['title']?></span>
	            <span>借阅日期：<?=substr($value['startdate'] , 0 , 10);?></span>
	            <span>应还日期：<?=substr($value['enddate'] , 0 , 10);?></span>
	        </div>
	        <div class="wechat_bbtn">
	        	<!-- <a class="btn_borrow" href="<?echo url('default/lent');?>">转借</a>
	        	<a class="btn_borrow" href="<?echo url('default/renew');?>">续借</a> -->
	        	<a class="btn_borrow" href="<?echo url('default/lentcode',array('barcode'=>$value['barcode']));?>">转借</a>
	        	<a class="btn_borrow" href="<?echo url('default/renewlogin');?>">续借</a>
	        </div>
	    </div>
	<?}?>
	<?if(count($list)==0){//查询失败?>
        <div class="alert alert-danger"><?=$info['remark']?></div>
    <?}?>
    
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>