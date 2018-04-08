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

        $("#taxnum").change(function(){
            var taxnum = $("#taxnum").val();
            var syfee = $("#syfee").val();
            if(eval(taxnum) > eval(syfee)){
                $("#taxnum").val(syfee);
                alert('超出开票最大金额');
            }
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
                <h3>开票申请</h3>
                <form name="sentMessage" id="contactForm" method="post" novalidate>
                    <!--input输入框-->                   
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>发票抬头:</label>
                            <input type="text" class="form-control" id="taitou" name="taitou" value="<?=$member['taitou']?>" required data-validation-required-message="请输入发票抬头.">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label>税号:</label>
                            <input type="text" class="form-control" id="taxno" name="taxno" value="<?=$member['taxno']?>" required data-validation-required-message="请输入税号.">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label>剩余开票金额:</label>
                            <?php
                            $syfee = $member['totalfee'] - $member['taxed'];
                            $showfee = $syfee<0?0:$syfee;
                            echo $showfee;
                            ?>
                            元
                        </div>
                        <input type="hidden" id="syfee" value="<?=$showfee?>" >
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label>本次开票金额:</label>
                            <input type="number" class="form-control" id="taxnum" name="taxnum" required data-validation-required-message="请输入开票金额.">
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary">提交</button>
                </form>

                <h3 class="sub-header">开票记录</h3>
        <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>开票金额(元)</th>
                  <th>申请时间</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($taxrecords as $key => $value) {
                    $num = $key +1;
                    echo "<tr><td>".$num."</td><td>".$value['taxnum']."</td><td>".date("Y-m-d H:i:s",$value['tax_time'])."</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>

        </div>
            </div>

        
        <!-- /.row -->

    </div>

<?php $this->_endblock(); ?>