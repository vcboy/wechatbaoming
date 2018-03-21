<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
<h1></h1>
    <form action="<?echo url('default/position1')?>" method="post">
        <?foreach($list as $key=>$value){?>
            <!-- <a class="row" href="<?echo url('default/position1',array('barcode'=>$value['barcode']))?>"> -->
                <div class="panel panel-default">
                    <table class="table" id="result-<?=$value['barcode']?>">
                        <tbody>
                            <tr>
                                <td>条码：<?=$value['barcode']?></td>
                            </tr>
                            <tr>
                                <td>书名：<?=$value['title']?></td>
                            </tr>                           
                            <tr>
                                <td>作者：<?=$value['author']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <!-- </a> -->
        <?}?>
        <?if(count($list)==0){?>
            <div class="alert alert-danger">查询没有结果:<?=empty($info['remark'])?'':$info['remark'];?></div>
        <?}?>
        <div class="control-group form-group" id="msg">
            <p class="help-block"></p>
        </div>
        <input name="barcode" id="barcode" type="hidden">
    	<ul class="pager">
            <li class="previous">
                <button type="button" class="btn btn-primary btn-lg" onclick="back('search')">重新查找</button>
            </li>
            <?if(count($list)>0){?>
                <li class="next">
                    <button type="sumit" class="btn btn-primary btn-lg" onclick="return check('barcode')">预约图书</button>
                </li>
            <?}?>
        </ul>
    </form>
    <!-- /.container -->
</div>
<script type="text/javascript">
	$(document).ready(function(){
        $(".table").click(function(){
            $('.help-block').html('');
            var id_str = $(this).attr("id");
            var id_arr = id_str.split('-');
            var id = id_arr[1];
            var v_id = "barcode";
            if($(this).hasClass('list-group-item-info')){
                $('#'+v_id).val('');
                $(this).removeClass('list-group-item-info');
            }else{
                $('.table').removeClass('list-group-item-info');
                $(this).addClass('list-group-item-info');
                $('#'+v_id).val(id);
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
            $('.help-block').html('请选择一本');
            return false;
        }
        /*var value = $('#'+id).val();
        if(value==''){
            alert('请选择图书');
            return false;
        }else{
            return true;
        }*/
    }
    function back (url) {
        // body...
        window.location.href = "<?=url('default/search')?>";
    }
</script>
<?php $this->_endblock(); ?>