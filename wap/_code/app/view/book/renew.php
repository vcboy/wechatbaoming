<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
<h1></h1>
        <!-- Contact Form -->
        <form action="<?echo url('default/renew')?>" method="post">

                <div class="panel panel-default">
                    <!-- Table -->
                    <?if(in_array($info['barcode'],$fail)){//续借失败?>
                        <table class="table list-group-item-danger">
                            <tbody>
                                <tr>
                                    <td width="40%">续借失败：</td>
                                    <td><?=$note[$info['barcode']]?></td>
                                </tr>
                    <?}?>
                    <?if(in_array($info['barcode'],$success)){//续借成功?>
                        <table class="table list-group-item-success">
                            <tbody>
                                <tr>
                                    <td width="40%"></td>
                                    <td>续借成功</td>
                                </tr>
                    <?}else{//默认续借?>
                        <table class="table renew" id="borrow-<?=$info['barcode']?>">
                            <tbody>
                    <?}?>
                                <tr>
                                    <td width="40%">条码号：</td>
                                    <td><?=$info['barcode']?></td>
                                </tr>
                                <tr>
                                    <td>题名：</td>
                                    <td><?=$info['title']?></td>
                                </tr>
                                <!-- <tr>
                                    <td>借阅日期：</td>
                                    <td><?=$info['startdate']?></td>
                                </tr> -->
                                <tr>
                                    <td>应还日期：</td>
                                    <td><?=$info['enddate']?></td>
                                </tr>
                                
                        </tbody>
                    </table>
                </div>

                <div class="control-group form-group" id="msg">
                    <p class="help-block"></p>
                </div>
                <input name="borrow_id" id="borrow_id" type="hidden">
                <div class="col-md-12">
                    <?if(count($info)==count($note)){?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg" disabled="disabled" >立即续借</button>
                    <?}else{?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg" onclick="return check('borrow_id')">立即续借</button>
                    <?}?>
                </div>
            <!-- /.row -->
            </div>
        </form>

    <!-- /.container -->
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".renew").click(function(){
            $('.help-block').html('');
            var id_str = $(this).attr("id");
            var id_arr = id_str.split('-');
            var id = id_arr[1];
            var v_id = "borrow_id";
            if($(this).hasClass('list-group-item-info')){
                var value = $('#'+v_id).val();
                var value_arr = value.split(',');
                var value_id = '';
                if(value_arr.length==1){
                    $('#'+v_id).val('');
                }else{
                    for (var i = 0; i <=value_arr.length-1; i++) {
                        if(value_arr[i]!=id){
                            if(value_id==''){
                                value_id = value_arr[i];
                            }else{
                                value_id += ','+value_arr[i];
                            }
                        }
                    };
                    $('#'+v_id).val(value_id);
                }
                $(this).removeClass('list-group-item-info');
            }else{
                $(this).addClass('list-group-item-info');
                var value = $('#'+v_id).val();
                if(value==""){
                    $('#'+v_id).val(id);
                }else{
                    $('#'+v_id).val(value+','+id);
                }
            }
            
        });
    });
    function check (id) {
        // body...
        $('#msg').removeClass('has-error');
        $('.help-block').html('');
        var value = $('#'+id).val()
        if(value.length){
            return true;
        }else{
            $('#msg').addClass('has-error');
            $('.help-block').html('至少选择一本');
            return false;
        }
    }
</script>
<?php $this->_endblock(); ?>