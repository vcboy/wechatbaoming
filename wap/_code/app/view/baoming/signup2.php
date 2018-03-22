<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>js/jqBootstrapValidation.js"></script>
<script src="<?=$_BASE_DIR?>js/clone-form-td.js"></script>
<script src="<?=$_BASE_DIR?>js/contact_me.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    <?php
    if(isset($member)){
      echo "$('#name').val('".$member['name']."');\n";
      echo "$('input[name=sex][value=".$member['sex']."]').attr('checked','checked');\n";
      echo "$('#nation').val('".$member['nation']."');\n";
      echo "$('#sfz').val('".$member['cid']."');\n";
      echo "$('#phone').val('".$member['tel']."');\n";
      if($member['birthday']){
        $birarr = explode('/',$member['birthday']);
        echo "$('#year').val('".$birarr[0]."');\n";
        echo "$('#month').val('".$birarr[1]."');\n";
        echo "$('#day').val('".$birarr[2]."');\n";
      }
    }
    if(isset($sigupinfo)){
      echo "$('#company').val('".$sigupinfo['company']."');\n";    
      echo "var edu = '".$sigupinfo['education']."';\n";
      echo "var job = '".$sigupinfo['job']."';\n";
      echo "var eduobj = JSON.parse(edu);\n";
      echo "console.log(eduobj);\n";

      echo "var jobobj = JSON.parse(job);\n";
    ?>
      for(var i in eduobj){
        n = parseInt(i)+1;
          console.log(n);
          var newElem1 = $('#template').clone().html(); 
          newElem = newElem1.replace(/{n}/g,n);
          $(newElem).appendTo('#clonetd');
          $('#datetimes_edu'+n).focus();
          $('#datetimes_edu'+n).val(eduobj[i][0]);
          $('#school_edu'+n).val(eduobj[i][1]);
          $('#major_edu'+n).val(eduobj[i][2]);
          $('#education_edu'+n).val(eduobj[i][3]);
      }

      for(var i in jobobj){
        n = parseInt(i)+1;
          console.log(n);
          var newElem2 = $('#template_work').clone().html(); 
        newElem = newElem2.replace(/{n}/g,n);
        $(newElem).appendTo('#clonetd_work');
        $('#datetimes_work'+n).focus();
        $('#datetimes_work'+n).val(jobobj[i][0]);
        $('#school_work'+n).val(jobobj[i][1]);
        $('#major_work'+n).val(jobobj[i][2]);
        //$('#datetimes_work'+n).val(jobobj[i][3]);
      }
    <?
    }else{
      echo "init();\n";
    }
    ?>
      

     function init(){
        var newElem1 = $('#template').clone().html(); 
        newElem = newElem1.replace(/{n}/g,1);
        $(newElem).appendTo('#clonetd');
        $('#datetimes_edu1').focus();

        
        var newElem2 = $('#template_work').clone().html(); 
        newElem = newElem2.replace(/{n}/g,1);
        $(newElem).appendTo('#clonetd_work');
        $('#datetimes_work1').focus();
    };
  })
</script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    /*debug: true,*/
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
          "chooseImage",
          "previewImage",
          "uploadImage",
          "downloadImage"
    ]
  });
  wx.ready(function() {
          /**
           * [weixinUpload 调用微信接口实现上传]
           * @param  {[function]} choose [选择图片后的回调]
           * @param  {[function]} upload [上传后的回调]
           */
          function weixinUpload(choose, upload) {
            wx.chooseImage({
              count: 1,
              sizeType: ['original', 'compressed'],
              sourceType: ['album', 'camera'],
              success: function(res) {
                var localIds = res.localIds;
                choose && choose(localIds); //选择图片后回调
                // 上传照片
                wx.uploadImage({
                  localId: '' + localIds,
                  isShowProgressTips: 1, //开启上传进度
                  success: function(res) {
                    serverId = res.serverId; 
                    upload && upload(serverId); //上传图片后回调
                  }
                });
              }
            });
          }

          /**
           * [uploadImage 上传图片到本地服务器]
           * @param  {[type]}   mediaId  [图片serverID]
           * @param  {Function} callback [回调]
           */
          function uploadImage(mediaId, callback) {
            $.ajax({
              type: "POST",
              url: "<?=url('baoming/uploadpic',array('api'=>'upload'))?>",
              data: {
                media_id: mediaId
              },
              dataType: "json",
              success: function(result) {
                if (result.code == 200) {
                  result.data.url = "<?=$_BASE_DIR?>"+result.data.url;
                  callback(result.data);
                }else{
                  alert("上传失败！");
                }
              },
              error: function() {
                alert("系统异常，请稍后重试");
              }
            });
          }

          var $uploadButton = $('#js-upload')
          var $uploadPreview = $('#js-preview')

          //点击上传按钮
          //$uploadButton.on('click', function() {
          $("#scancard").click(function(){
            weixinUpload(
              function(localIds) {
                $uploadPreview.attr('src', localIds); //微信本地图片地址,可以用来做上传前预览
              },
              function(serverId) {
                uploadImage(serverId, function(data) {
                  $uploadPreview.attr('src', data.url); //返回真实的图片地址
                  alert(data.url);
                });
              }
            );
          })
        });
</script>

    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <!-- <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Contact
                    <small>Subheading</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="index.html">Home</a>
                    </li>
                    <li class="active">Contact</li>
                </ol>
            </div>
        </div> -->
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row" style="display: none">
            <!-- Map Column -->
            <div class="col-md-8">
                <p>
                  <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="scancard">
                    上传图片
                  </button>
                </p>
                <article>
                 <figure><img src="" id="js-preview" width="100%"></figure>
               </article>
            </div>
            <!-- Contact Details Column -->
            <!-- <div class="col-md-4">
                <h3>Contact Details</h3>
                <p>
                    3481 Melrose Place<br>Beverly Hills, CA 90210<br>
                </p>
                <p><i class="fa fa-phone"></i> 
                    <abbr title="Phone">P</abbr>: (123) 456-7890</p>
                <p><i class="fa fa-envelope-o"></i> 
                    <abbr title="Email">E</abbr>: <a href="mailto:name@example.com">name@example.com</a>
                </p>
                <p><i class="fa fa-clock-o"></i> 
                    <abbr title="Hours">H</abbr>: Monday - Friday: 9:00 AM to 5:00 PM</p>
                <ul class="list-unstyled list-inline list-social-icons">
                    <li>
                        <a href="#"><i class="fa fa-facebook-square fa-2x"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-linkedin-square fa-2x"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-twitter-square fa-2x"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-google-plus-square fa-2x"></i></a>
                    </li>
                </ul>
            </div> -->
        </div>
        <!-- /.row -->

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3>浙江省电子商务专业人才鉴定申请表</h3>
                <form name="sentMessage" id="contactForm" novalidate>
                    <!--input输入框-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>申报单位:</label>
                            <input type="text" class="form-control" id="company" required data-validation-required-message="请输入申报单位名称.">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>姓名:</label>
                            <input type="text" class="form-control" id="name" required data-validation-required-message="请输入姓名.">
                            <p class="help-block"></p>
                        </div>
                    </div>                    
                    <!--单选-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>性别:</label>
                            <label class="radio-inline">
                              <input type="radio" name="sex" id="inlineRadio1" value="1" checked> 男
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="sex" id="inlineRadio2" value="0"> 女
                            </label>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>民族:</label>
                            <input type="text" class="form-control" id="nation" required data-validation-required-message="请输入民族.">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <!--下拉选择-->
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>出生年月:</label>
                            <div>
                            <select class="form-control" style="width:40%;float: left;" id="year">
                            <?php
                              foreach ($years as $key => $value) {
                                echo "<option value='".$value."'>".$value."</option>";
                              }
                            ?>
                            </select>
                            <select class="form-control" style="width:30%;float: left;" id="month">
                            <?php
                              foreach ($month as $key => $value) {
                                echo "<option value='".$value."'>".$value."</option>";
                              }
                            ?>
                            </select>
                            <select class="form-control" style="width:30%;float: left;" id="day">
                            <?php
                              foreach ($days as $key => $value) {
                                echo "<option value='".$value."'>".$value."</option>";
                              }
                            ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>身份证:</label>
                            <input type="text" class="form-control" id="sfz" required data-validation-required-message="请输入身份证号码.">
                        </div>
                    </div>
                    <!-- <div class="control-group form-group">
                        <div class="controls">
                            <label>报考证书:</label>
                            <input type="text" class="form-control" id="bkzs" required data-validation-required-message="请输入身份证号码.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>证书等级:</label>
                            <input type="text" class="form-control" id="zsdj" required data-validation-required-message="请输入证书等级.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>报考方向:</label>
                            <input type="text" class="form-control" id="bkfx" required data-validation-required-message="请输入报考方向.">
                        </div>
                    </div> -->
                    
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>联系电话:</label>
                            <input type="tel" class="form-control" id="phone" required data-validation-required-message="Please enter your phone number.">
                        </div>
                    </div>
                    
                    <div id = "template"  style="display: none">
                      <div id="entry{n}" class="clonedInput divedu">
                        <h2 id="reference" name="reference" class="heading-reference">教育经历 #{n}</h2>
                        <fieldset>
                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_fn control-label" for="first_name">时间:</label>
                            <input id="datetimes_edu{n}" name="datetimes_edu{n}" type="text" placeholder="" class="input_fn form-control" required="">
                          </div>

                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_ln control-label" for="last_name">学校:</label>
                            <input id="school_edu{n}" name="school_edu{n}" type="text" placeholder="" class="input_ln form-control">
                          </div>

                          <!-- Text input-->
                          <div class="form-group">
                            <label class="label_sn control-label" for="last_name">专业:</label>
                            <input id="major_edu{n}" name="major_edu{n}" type="text" placeholder="" class="input_ln form-control">
                          </div>
                          
                          <div class="form-group">
                            <label class="label_sn control-label" for="last_name">学历学位:</label>
                            <input id="education_edu{n}" name="education_edu{n}" type="text" placeholder="" class="input_ln form-control">
                          </div> 

                          
                      </fieldset>
                      </div><!-- end #entry1 -->
                    </div>

                    

                    <div id="clonetd"></div>
                    <!-- Button (Double) -->
                    <p>
                    <button type="button" id="btnAdd" name="btnAdd" class="btn btn-info">新增</button>
                      <button type="button" id="btnDel" name="btnDel" class="btn btn-danger">移除</button>
                    </p>

                    <div id = "template_work"  style="display: none">
                      <div id="entry_work{n}" class="clonedInput divwork">
                        <h2 id="reference" name="reference" class="heading-reference">工作经历 #{n}</h2>
                        <fieldset>

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_fn control-label" for="first_name">时间:</label>
                        <input id="datetimes_work{n}" name="datetimes_work{n}" type="text" placeholder="" class="input_fn form-control" required="">
                      </div>

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_ln control-label" for="last_name">单位:</label>
                        <input id="school_work{n}" name="school_work{n}" type="text" placeholder="" class="input_ln form-control">
                      </div>

                      <!-- Text input-->
                      <div class="form-group">
                        <label class="label_sn control-label" for="last_name">岗位:</label>
                        <input id="major_work{n}" name="major_work{n}" type="text" placeholder="" class="input_ln form-control">
                      </div>

                      <!-- <div class="form-group">
                        <label class="label_sn control-label" for="last_name">学历学位:</label>
                        <input id="education_work{n}" name="education_work{n}" type="text" placeholder="" class="input_ln form-control">
                      </div> -->
                        
                      <!-- Select Basic -->
                      <!-- <label class="label_ttl control-label" for="title">学历学位:</label>
                      <div class="form-group">
                          <select class="select_ttl form-control" name="education_work{n}" id="education_work{n}">
                            <option value="" selected="selected" disabled="disabled">Select your title</option>
                            <option value="Dr.">Dr.</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Sir">Sir</option>
                          </select>
                        </div> -->

                      </fieldset>
                      </div><!-- end #entry1 -->
                    </div>
                    <div id="clonetd_work"></div>
                    <!-- Button (Double) -->
                    <p>
                    <button type="button" id="btnAdd_work" name="btnAdd_work" class="btn btn-info">新增</button>
                      <button type="button" id="btnDel_work" name="btnDel_work" class="btn btn-danger">移除</button>
                    </p>

                    <!--text文本框-->
                    <div class="control-group form-group" style="display: none">
                        <div class="controls">
                            <label>备注:</label>
                            <textarea rows="10" cols="100" class="form-control" id="message" maxlength="999" style="resize:none"></textarea>
                        </div>
                    </div>
                    
                    <!-- For success/fail messages -->
                    <input type="hidden" name="plan_id" id="plan_id" value="<?=$plan_id?>">
                    <input type="hidden" name="userid" id="userid" value="<?=$userid?>">
                    <input type="hidden" name="jf" id="jf" value="<?=$plandata['jf']?>">

                    <button type="submit" class="btn btn-primary">提交报名</button>
                </form>

                <div id="pay">
                    <div id="success"></div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>费用合计:</label>
                            <span class="score2">¥<?=$plandata['fee']?></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">马上支付</button>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </div>

<?php $this->_endblock(); ?>