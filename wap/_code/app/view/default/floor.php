<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3></h3>
                <form name="sentMessage" id="contactForm" novalidate action="<?echo url('default/floor')?>" method="post">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>条码:</label>
                            <input type="text" class="form-control" id="floor" name="floor" required data-validation-required-message="Please enter your message.">
                        </div>
                    </div>
                    <div class="control-group form-group" id="msg">
                        <p class="help-block"></p>
                    </div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg" onclick="return check()">立即查找</button>
                </form>
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
<script type="text/javascript">
    function check(argument) {
        // body...
        $('#msg').removeClass('has-error');
        $('.help-block').html('');
        var floor = $('#floor').val();
        if(floor.length){
            return true;
        }else{
            $('#msg').addClass('has-error');
            $('.help-block').html('内容不能为空');
            return false;
        }
    }
</script>
<?php $this->_endblock(); ?>