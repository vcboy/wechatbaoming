<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
	<form name="sentMessage" id="contactForm" method="post" >
		<div class="panel panel-default">
	        <!-- Table -->
	        <?if(count($list)==0&&empty($info)){?>
	            <table class="table list-group-item-warning">
	            	<tbody>
	            	<tr>
	                    <td><?=$showstr?></td>
	                </tr>		
	        <?}else{?>
	        	<table class="table">
	            	<tbody>
	        <?}?>

	            	<?if($status==1){//借书成功?>
	                    <tr>
	                        <td width="40%">条码号：</td>
	                        <td><?=$info['barcode']?></td>
	                    </tr>
	                    <tr>
	                        <td>题名：</td>
	                        <td><?=$info['title']?></td>
	                    </tr>
	                    <tr>
	                        <td>作者：</td>
	                        <td><?=$info['author']?></td>
	                    </tr>
	                    <tr>
	                        <td>应还日期：</td>
	                        <td><?=substr($info['enddate'] , 0 , 10);?></td>
	                    </tr>
	                <?}else{
	                	foreach ($list as $key => $value) {?>
	                		<tr>
		                        <td width="40%">条码号：</td>
		                        <td><?=$value['barcode']?></td>
		                    </tr>
		                    <tr>
		                        <td>题名：</td>
		                        <td><?=$value['title']?></td>
		                    </tr>
		                    <tr>
		                        <td>责任者：</td>
		                        <td><?=$value['author']?></td>
		                    </tr>
	                	<?}?>

	                <?}?>
	            </tbody>
	        </table>
	    </div>

	    <?if($status=='1'){//借书成功?>
	    	<div class="alert alert-success"><?=$text?></div>
	    <?}?>
	    <?if($status=='2'){//借书失败?>
	    	<div class="alert alert-danger"><?=$text.':'.$info['remark']?></div>
	    <?}?>
		<input name="barcode" value="<?=$barcode?>" type="hidden">
		<?if(count($list)>0&&!$status){//提交过就不显示确定按钮?>
			<button type="submit" class="btn btn-primary btn-block btn-lg">确定借书</button>
		<?}?>
	</form>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>