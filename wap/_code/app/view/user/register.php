<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
    <!-- /.container -->
    <form name="positionForm" id="positionForm" method="post" action="<?echo url('user/register')?>">
    <div class="panel panel-default">
        <!-- Table -->
        <table  class="table list-group-item-success">
            <tbody>
                    <tr>
                        <td>手机号：</td>
                        <td><input type="text" name="phone" id="phone"></td>
                        <td><div id="send" class="btn btn-primary"  onclick="send()">获取验证码</div></td>
                    </tr>
                    <tr>
                        <td>验证码：</td>
                        <td><input type="text" name="code" id="code"></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>       
    </div>
    <input name="user" type="hidden" value="11">
    <button type="submit" class="btn btn-primary btn-block btn-lg" onclick="check()">提交</button>
    </form>
</div>
<script type="text/javascript">

    function send() {
        // body...
        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test($("#phone").val())){ 
            alert('请输入有效的手机号码！'); 
        }else{
            $.ajax({
                type : "post",
                method : "post",
                dataType : "json",
                data:{"phone":$("#phone").val()},
                url : "<?php echo url('user/send')?>",
                success : function(data) {
                    $("#send").attr("disabled", true);
                    stime(59);
                },
                error:function(){
                    //alert(1);
                    alert('发送短信失败');
                }               
            });
        }
    }
    function stime(num){
        if(num>0){
            num--;
            $("#send").html(num+"秒后重送");
            setTimeout("stime("+num+")",1000);
        }else{
            $("#send").attr("disabled", false);
            $("#send").html("获取验证码");
        }
    }
    function check() {
        // body...
        var code = $("#code").val();
        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test($("#phone").val())||code.length==0){ 
            alert('请输入正确的手机号和验证码'); 
            return false;
        }else{
            return true;
        }
    }
    <?if(!empty($error)){echo "alert('".$error."')";}?>
</script>
<?php $this->_endblock(); ?>