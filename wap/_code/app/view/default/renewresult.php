<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h1></h1>
    <!-- /.container -->
    <div class="panel panel-default">
        <!-- Table -->
        <?if($info['status']=='1'){?>
            <table class="table list-group-item-success">
            <tbody>
                <tr>
                    <td >转借成功</td><td><?=$info['remark']?></td>
                </tr>
        <?}?>
        <?if($info['status']=='2'){?>
            <table class="table list-group-item-warning">
            <tbody>
                <tr>
                    <td >转借失败</td><td><?=$info['remark']?></td>
                </tr>
        <?}?>
        <?if($info['status']=='0'){?>
            <table class="table list-group-item-danger">
            <tbody>
                <tr>
                    <td >转借失败</td><td><?=$info['remark']?></td>
                </tr>
        <?}?>
            
                <?foreach($list as $key => $value){?>
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
            </tbody>
        </table>
    </div>

</div>
<?php $this->_endblock(); ?>