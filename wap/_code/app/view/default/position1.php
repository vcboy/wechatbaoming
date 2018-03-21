<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
    <!-- /.container -->
    <form name="positionForm" id="positionForm" method="post" >
    <div class="panel panel-default">
        <!-- Table -->
        <table  class="table list-group-item-success">
            <tbody>
                <?if($info['return']){//查询成功?>
                    <tr>
                        <td width="40%">条码：</td>
                        <td><?=$info['barcode']?></td>
                    </tr>
                    <tr>
                        <td>书名：</td>
                        <td><?=$info['title']?></td>
                    </tr>
                    <tr>
                        <td>作者：</td>
                        <td><?=$info['author']?></td>
                    </tr>
                    <tr>
                        <td>状态：</td>
                        <td><?=$info['state']?></td>
                    </tr>
                    <tr>
                        <td>位置：</td>
                        <td><?=$info['position']?></td>
                    </tr>
                <?}else{?>
                    <tr>
                        <td><?=$info['remark']?></td>
                    </tr>
                <?}?>
            </tbody>
        </table>       
    </div>
    <input name="barcode" id="barcode" type="hidden" value="<?=$info['barcode']?>">
    <button type="submit" class="btn btn-primary btn-block btn-lg" ><?=$status?></button>
    </form>
</div>
<?php $this->_endblock(); ?>