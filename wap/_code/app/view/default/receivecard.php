<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>js/jqBootstrapValidation.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
      init();
      function init(){
         var getway = <?=$member['getway'];?>;
         $("input[name='getway'][value="+getway+"]").attr('checked',true);
         //console.log(getway);
         if(getway == 1){
            $("#expresscontent").hide();
         }else{
            $("#expresscontent").show();
         }
      }

      $("input[name='getway']").click(function(){
          var getway = $(this).val();
          console.log(getway);
          if(getway == 1)
            $("#expresscontent").hide();
          else
            $("#expresscontent").show();
      })
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


        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                
                <form name="sentMessage" id="contactForm" method="post" novalidate>
                    <div class="control-group form-group" style="margin-top: 20px;">
                        <div class="controls">
                            <label>证书领取方式:</label>
                            <label class="radio-inline">
                              <input type="radio" name="getway" id="inlineRadio1" value="1"> 自取
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="getway" id="inlineRadio2" value="2"> 快递
                            </label>
                        </div>
                    </div>
                    <div id="expresscontent">
                    <!--input输入框-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>姓名:</label>
                            <input type="text" class="form-control" name="name" id="name" required data-validation-required-message="请输入姓名." value="<?=$member['express_name']?>">
                            <p class="help-block"></p>
                        </div>
                    </div>                                                  
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>手机号码:</label>
                            <input type="number" class="form-control" name="phone" id="phone" required data-validation-required-message="请输入手机号码." value="<?=$member['express_tel']?>">
                        </div>
                    </div>
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>地址:</label>
                            <input type="text" class="form-control" name="address" id="address" required data-validation-required-message="请输入接收地址." value="<?=$member['address']?>">
                        </div>
                    </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary">保存</button>
                </form>
            </div>

        </div>
        <!-- /.row -->

    </div>

<?php $this->_endblock(); ?>