<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>js/jqBootstrapValidation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // something to have when submit produces an error ?
            // Not decided if I need it yet
        },
        submitSuccess: function($form, event){
          
        },
        filter: function() {
            return $(this).is(":visible");
        },
      })
    })
</script>
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->

        <!-- Content Row -->
        <div class="row">
            <!-- Map Column -->
            <!-- <div class="col-md-8">
            
                  <a href="<?=url('baoming/clip')?>"><button type="button" style="margin-top: 50px;" class="btn btn-primary  " id="scancard">
                    上传身份证
                  </button></a>
            
                  <a href="<?=url('baoming/clipzj')?>"><button type="button" style="margin-top: 50px;" class="btn btn-primary  " id="scancardzj">
                    证件照片
                  </button></a>
            
            </div> -->
        </div>
        <!-- /.row -->

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3>个人资料</h3>
                <form name="sentMessage" id="contactForm" method="post" novalidate>
                    <!--input输入框-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>姓名:</label>
                            <input type="text" class="form-control" id="name" name="name" required data-validation-required-message="请输入姓名." value="<?=$member['name']?>">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>证件号码:</label>
                            <input type="text" class="form-control" id="sfz" name="sfz" required data-validation-required-message="请输入身份证号码." value="<?=$member['cid']?>" readonly>
                        </div>
                    </div>                                 
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>手机号码:</label>
                            <input type="number" class="form-control" id="phone" name="phone" required data-validation-required-message="请输入手机号码." value="<?=$member['tel']?>">
                        </div>
                    </div>
                    
                    <!-- <div class="control-group form-group">
                        <div class="controls">
                            <label>积分:</label>
                            <input type="text" class="form-control" id="phone" required data-validation-required-message="请输入手机号码." value="<?=$member['jf']?>">
                        </div>
                    </div> -->

                    <div class="control-group form-group">
                        <div class="controls">
                            <label>证件照:</label>
                            <?php
                            if($member['pic_path']) {
                                echo '已上传';
                            }else{
                            ?>
                            <a href="<?=url('baoming/clipzj')?>"><button type="button" class="btn btn-info  " id="scancardzj">证件照片</button></a>
                            <?    
                            }
                            ?>
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label>身份证:</label>
                            <?php
                            if($member['sfz_path']){
                                echo '已上传';
                            }else{
                            ?>
                            <a href="<?=url('baoming/clip')?>"><button type="button" class="btn btn-info  " id="scancard">上传身份证</button></a>
                            <?
                            }
                            ?>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary">修改</button>
                </form>
            </div>

        </div>
        <!-- /.row -->

    </div>

<?php $this->_endblock(); ?>