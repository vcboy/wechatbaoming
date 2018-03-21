<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
<h1></h1>
        <!-- Contact Form -->
        <form action="<?echo url('default/note')?>" method="post">
            <?foreach($list as $key => $value){?>
                    <!-- Table -->
                    <?if(in_array($value['barcode'],$fail)){//续借失败?>
                        <div class="wechat_lbg " style="height:140px;" >
                            <div class="bbg_con ">
                                <span style="text-align:center">续借失败：<?=$note[$value['barcode']]?></span>
                    <?}?>
                    <?if(in_array($value['barcode'],$success)){//续借成功?>
                        <div class="wechat_lbg " style="height:140px;" >
                            <div class="bbg_con ">
                                <span style="text-align:center">续借成功</span>
                    <?}else{//默认续借?>
                        <div class="wechat_lbg " style="height:115px;" id="borrow-<?=$value['barcode']?>">
                            <div class="bbg_con ">
                    <?}?>
                            <span>条码号：<?=$value['barcode']?></span>
                            <span>题名：<?=$value['title']?></span>
                            <span>应还日期：<?=substr($value['enddate'] , 0 , 10);?></span>
                        </div>
                    </div>
            <?}?>
                <div class="control-group form-group" id="msg">
                    <p class="help-block"></p>
                </div>
                <input name="borrow_id" id="borrow_id" type="hidden">
                <input name="pwd" id="pwd" type="hidden" value="<?=$pwd?>">
                <div class="col-md-12">
                    <?if(count($list)==count($note)){?>
                        <button type="submit" class="btn btn-wechat btn-block btn-lg" disabled="disabled" >立即续借</button>
                    <?}else{?>
                        <button type="submit" class="btn btn-wechat btn-block btn-lg" onclick="return check('borrow_id')">立即续借</button>
                    <?}?>
                </div>
            <!-- /.row -->
            </div>
        </form>

    <!-- /.container -->
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".wechat_lbg").click(function(){
            $('.help-block').html('');
            var id_str = $(this).attr("id");
            var id_arr = id_str.split('-');
            var id = id_arr[1];
            var v_id = "borrow_id";
            if($(this).hasClass('bg_on')){
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
                $(this).removeClass('bg_on');
            }else{
                $(this).addClass('bg_on');
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