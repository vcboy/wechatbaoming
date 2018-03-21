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
  <div>
  <p>
  <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="scancard">
    上传图片
  </button></p>
  <article>
   <figure><img src="" id="js-preview" width="100%"></figure>
 </article>
</div>

<?php $this->_endblock(); ?>