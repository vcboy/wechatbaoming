<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3></h3>
                <form name="sentMessage" id="renewForm" method="post"  action="<?echo url('default/renew')?>" >
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>读者密码:</label>
                            <input type="password" class="form-control" id="pwd" name="pwd" maxlength="15" minlength="4" required data-validation-required-message="请输入你的姓名">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg" >点击续借</button>
                </form>
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
<script type="text/javascript">
    $(function() {$("#renewForm input").jqBootstrapValidation();})
</script>
<?php $this->_endblock(); ?>