<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
	<form name="sentMessage" id="contactForm" method="post" >
		<div class="panel panel-default">
	        <!-- Table -->
	        	<table class="table">
	            	<tbody>
	                	<tr>
		                    <td width="40%">条码号：</td>
		                    <td><?=$book['barcode']?></td>
		                </tr>
		                <tr>
		                    <td>书名：</td>
		                    <td><?=$book['name']?></td>
		                </tr>
		                <tr>
		                    <td>作者：</td>
		                    <td><?=$book['author']?></td>
		                </tr>
		                <tr>
		                    <td>索书号：</td>
		                    <td><?=$book['isbn']?></td>
		                </tr>
		                <tr>
		                    <td>上架时间：</td>
		                    <td><?=date('Y-m-d',$book['date'])?></td>
		                </tr>
	            </tbody>
	        </table>
	    </div>
		<input name="barcode" value="<?=$book['barcode']?>" type="hidden">
		<button type="submit" class="btn btn-primary btn-block btn-lg"><?=$status?></button>
	</form>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>