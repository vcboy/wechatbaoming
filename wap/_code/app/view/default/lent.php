<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <form action="<?echo url('default/lentcode')?>" method="post" name="sentMessage" >
            <?foreach($list as $key => $value){?>
            <a href="<?echo url('default/lentcode',array('barcode'=>$value['barcode']));?>">
                <div class="wechat_lbg " style="height:115px;" id="lent-<?=$value['barcode']?>">
                    <div class="bbg_con ">
                        <span>条码号：<?=$value['barcode']?></span>
                        <span>题名：<?=$value['title']?></span>
                        <span>应还日期：<?=substr($value['enddate'] , 0 , 10);?></span>
                    </div>
                </div>
            </a>
            <?}?>
            <?if(count($list)==0){?>
                <div class="alert alert-danger"><?=$info['remark']?></div>
            <?}?>
            <div class="control-group form-group" id="msg">
                <p class="help-block"></p>
            </div>               
            <input name="barcode" id="barcode" type="hidden">
            <input name="status" id="status" value="0" type="hidden">
            <?if(count($list)>0){?>
                <!-- <div class="col-md-12">
                    <button type="submit" class="btn btn-wechat btn-block btn-lg" onclick="return check('barcode')">立即转借</button>
                </div> -->
            <?}?>
    </form>
    <!-- /.container -->
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".wechat_lbg1").click(function(){
            var id_str = $(this).attr("id");
            var id_arr = id_str.split('-');
            var id = id_arr[1];
            var v_id = "barcode";
            var me = this;
            if($(this).hasClass('bg_on')){
                //var value = $('#'+v_id).val();
                //var value_arr = value.split(',');
                $('#'+v_id).val('');
                $(this).removeClass('bg_on');
            }else{
                $('.lent').removeClass('bg_on');
                
                $('#'+v_id).val(id);
                $.ajax({
                    type : "post",
                    method : "post",
                    dataType : "json",
                    data:{"id":id},
                    url : "<?=url('default/turnborrow')?>",
                    success : function(data) {
                        $('#status').val(data);
                        $(me).addClass('bg_on');
                    }
                });               
            }           
        });
    });
    function check (id) {
        // body...
        $('#msg').removeClass('has-error');
        $('.help-block').html('');
        var value = $('#'+id).val();
        if(value.length){
            var status = $('#status').val();
            if(status==0){
                $('#msg').addClass('has-error');
                $('.help-block').html('此书无法转借');
                return false;
            }else{
                return true;
            }
        }else{
            $('#msg').addClass('has-error');
            $('.help-block').html('请选择一本');
            return false;
        }
        
    }
</script>
<?php $this->_endblock(); ?>