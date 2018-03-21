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
        'getLocation',
        'openLocation'
    ]
  });
  wx.ready(function () {
      // 在这里调用 API
      // 12.1 扫一扫
      /*alert('scan');
      wx.scanQRCode();*/
      //alert('scan');
      $("#scancard").click(function(){

        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                window.latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                window.longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                console.log(res);
                alert(window.latitude+' '+window.longitude);
            }
        });
      });
      $("#getlocation").click(function(){
        wx.openLocation({
            latitude: window.latitude, // 纬度，浮点数，范围为90 ~ -90
            longitude: window.longitude, // 经度，浮点数，范围为180 ~ -180。
            name: '', // 位置名
            address: '', // 地址详情说明
            scale: 25, // 地图缩放级别,整形值,范围从1~28。默认为最大
            infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
        });
      })
  });
</script>
<div class="container">
  <div>
  <p>
  <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="scancard">
    获取地址
  </button></p>
  <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="getlocation">
    查看地址
  </button></p>
  <p style="text-align: center">卡券核销，点击扫一扫</p>
  </div>
</div>
<?php $this->_endblock(); ?>