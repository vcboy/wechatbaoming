<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3></h3>
                <form name="sentMessage" id="contactForm" method="post" novalidate >
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>证件号:</label>
                            <input type="text" class="form-control" id="cardno" name="cardno" required data-validation-required-message="请输入学生学号或教师工号" maxlength="15" minlength="5" placeholder="学生学号或教师工号">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>身份证后六位:</label>
                            <input type="text" class="form-control" id="readername" name="readername" required data-validation-required-message="身份证后六位" placeholder="身份证后六位" maxlength="10" minlength="6">
                            <input name="openid" id="openid" value="<?=$openid?>" type="hidden">
                            <input name="type" id="type" value="bind" type="hidden">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>手  &nbsp;&nbsp;机:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required data-validation-required-message="请输入你的手机号码" maxlength="11" minlength="11">
                            <input type="text" name="yzcode" id="yzcode" placeholder="验证码" class="form-control" style="width:100px; float: left; margin: 5px 10px 15px 0px;" maxlength="4" minlength="4"> 
                            <button type="button" class="btn btn-default" onclick="sendcode()"  id="send" style="margin-top: 5px;">验证码</button>   
                        </div>
                    </div>
                    <!-- 
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>读者账号:</label>
                            <input type="text" class="form-control" id="readercode" name="readercode" required data-validation-required-message="请输入你的账号" maxlength="15" minlength="6">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>读者密码:</label>
                            <input type="password" class="form-control" id="pwd" name="pwd" required data-validation-required-message="请输入你的密码" maxlength="15" minlength="6">
                            <input name="openid" id="openid" value="<?=$openid?>" type="hidden">
                            <input name="type" id="type" value="bind" type="hidden">
                        </div>
                    </div> -->
                    <div id="success"></div>
                    <?if($info['status']=='2'){//绑定失败?>
                        <div class="alert alert-danger">绑定失败：<?=$info['remark']?></div>
                    <?}?>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg">立即绑定</button>
                </form>
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
<script type="text/javascript">
    $(function() {$("#contactForm input").jqBootstrapValidation();})


    function sendcode() {
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
        var code = $("#yzcode").val();
        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test($("#phone").val())||code.length==0){ 
            alert('请输入正确的手机号和验证码'); 
            return false;
        }else{
            return true;
        }
    }
    <?if(!empty($error)){echo "alert('".$error."')";}?>

    $(document).ready(function(){
        var status = <?=$info['status']?>;
        if(status == '1'){
            alert('绑定成功');
            window.close();
        }
    })
</script>
<?php $this->_endblock(); ?>