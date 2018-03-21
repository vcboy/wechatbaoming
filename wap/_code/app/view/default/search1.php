<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3></h3>
                <form name="sentMessage" id="contactForm" novalidate action="<?echo url('default/result1')?>" method="get">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>条码:</label>
                            <input type="text" class="form-control" id="code" name="code" required data-validation-required-message="Please enter your name.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>书名:</label>
                            <input type="text" class="form-control" id="title" name="title"  required data-validation-required-message="Please enter your phone number.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>作者:</label>
                            <input type="text" class="form-control" id="author" name="author" required data-validation-required-message="Please enter your phone number.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>ISBN号:</label>
                            <input type="text" class="form-control" id="num" name="num"  required data-validation-required-message="Please enter your phone number.">
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
        var code = $('#code').val();
        var title = $('#title').val();
        var author = $('#author').val();
        var num = $('#num').val();
        if(code.length || title.length || author.length || num.length){
            return true;
        }else{
            $('#msg').addClass('has-error');
            $('.help-block').html('至少填写一项');
            return false;
        }
    }
</script>
<?php $this->_endblock(); ?>