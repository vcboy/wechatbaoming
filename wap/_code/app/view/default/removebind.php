<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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
        'closeWindow'
    ]
  });
</script>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-8">
                <h3></h3>
                <form name="sentMessage" id="contactForm" method="post" >
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>读者账号:</label>
                            <input type="text" class="form-control" id="readercode" name="readercode" maxlength="15" minlength="6" value="<?=$readercode?>" readonly="true">
                            <input name="type" id="type" value="removebind" type="hidden">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg" >解除绑定</button>
                    <button type="button" class="btn btn-primary btn-block btn-lg" onclick="javascript:closewin()">关闭</button>
                </form>
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
<script type="text/javascript">
    $(function() {$("#contactForm input").jqBootstrapValidation();})
    $(document).ready(function(){
        var status = <?=$info['status']?>;
        if(status == '1'){
            alert('读者绑定成功');
            //window.close();
            
        }
    });

    function closewin(){
        //alert('读者绑定成功');
        wx.closeWindow();
    }
</script>
<?php $this->_endblock(); ?>