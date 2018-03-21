<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>

    <div class="container">

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3>人才鉴定申请表</h3>
                <div class="line"></div>

                    <!--input输入框-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>申报单位:</label> <?=$sdata['company']?>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>姓名:</label> <?=$sdata['name']?>
                            <p class="help-block"></p>
                        </div>
                    </div>                    
                    <!--单选-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>性别:</label> <?=$sex = $sdata['sex'] == 1?"男":"女";?>
                            
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>民族:</label> <?=$sdata['nation']?>
                        </div>
                    </div>
                    <!--下拉选择-->
                    <div class="control-group form-group">
                        <div class="controls">
                            
                            <div>
                            <label>出生年月:</label><?=$sdata['birthday']?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>身份证:</label> <?=$sdata['sfz']?>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>报考证书:</label> <?=$sdata['bkzs']?>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>证书等级:</label> <?=$sdata['zsdj']?>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>报考方向:</label> <?=$sdata['bkfx']?>
                        </div>
                    </div>
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>联系电话:</label> <?=$sdata['tel']?>
                        </div>
                    </div>
                   
                    <?php
                    $education = $sdata['education'];
                    $edu_arr = json_decode($education,true);
                    if(!empty($edu_arr)){
                      foreach ($edu_arr as $key => $value) {
                        # code...
                      
                    ?>
                  
                      <div id="entry<?=$key+1?>" class="clonedInput divedu">
                        <h4 id="reference" name="reference" class="heading-reference">教育经历 #<?=$key+1?></h4>
                        <fieldset class="fs">
                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_fn control-label" for="first_name">时间:</label>
                            <?=$value[0]?>
                          </div>

                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_ln control-label" for="last_name">学校:</label>
                            <?=$value[1]?>
                          </div>

                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_sn control-label" for="last_name">专业:</label>
                            <?=$value[2]?>
                          </div>
                            
                          <!-- Select Basic -->
                          <label class="label_ttl control-label" for="title">学历学位:</label>
                          <?=$value[3]?>
                      </fieldset>
                      </div><!-- end #entry1 -->
                    <?php
                      }
                    }
                    ?>

                    

                    <div id="clonetd"></div>
                    <!-- Button (Double) -->


                    <?php
                    $job = $sdata['job'];
                    $job_arr = json_decode($job,true);
                    if(!empty($job_arr)){
                      foreach ($job_arr as $key => $value) {
                        # code...
                      
                    ?>
                      <div id="entry_work<?=$key+1?>" class="clonedInput divwork">
                        <h4 id="reference" name="reference" class="heading-reference">工作经历 #<?=$key+1?></h4>
                      <fieldset class="fs">

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_fn control-label" for="first_name">时间:</label>
                        <?=$value[0]?>
                      </div>

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_ln control-label" for="last_name">单位:</label>
                        <?=$value[1]?>
                      </div>

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_sn control-label" for="last_name">岗位:</label>
                        <?=$value[2]?>
                      </div>
                        
                      <!-- Select Basic -->
                      <label class="label_ttl control-label" for="title">学历学位:</label>
                      <?=$value[3]?>

                      </fieldset>
                      </div><!-- end #entry1 -->
                     <?php
                      }
                    }
                    ?>
  
                    <!-- For success/fail messages -->
                    <p></p>
                    <button class="btn btn-primary" id="back">返回</button>

            </div>

        </div>
        <!-- /.row -->

    </div>

<?php $this->_endblock(); ?>