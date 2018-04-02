<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>微信报名</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=$_BASE_DIR?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$_BASE_DIR?>css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?=$_BASE_DIR?>css/modern-business.css" rel="stylesheet">
    <link href="<?=$_BASE_DIR?>css/wechat.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=$_BASE_DIR?>js/vendor/jquery-2.1.1.min.js"></script>
    <script src="<?=$_BASE_DIR?>js/vendor/bootstrap.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script type="text/javascript">
        function back() {
            //window.location.href="<?echo url('/lent')?>"
            window.history.back();
        }
    </script>
  </head>
  <body style="padding-top: 10px;">

    <div class="container">

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-md-12">
              <div>
                <div class="share">点击右上角"三个点"<br>发送给朋友或分享到朋友圈</div>
                <img ng-src="imgaes/point.png" src="<?=$_BASE_DIR?>images/point.png">
              </div>
              
              <div class="thumbnail" style="margin-top: 30px;">                    
                <a href="<?=$code?>"><img class="img-responsive" src="<?=$_BASE_DIR?>qrcode/qrcode.php?size=8&data=<?echo $link?>" alt=""></a>
              </div>
            </div>
            <p style="text-align: center;">活动二维码</p>
        </div>
        <!-- /.row -->

    </div>

        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p style="margin: 0  10px;">Copyright &copy; Your Website 2017</p>
                </div>
            </div>
        </footer>
  </body>
  <script type="text/javascript">
  wx.config({
    /*debug: true,*/
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'checkJsApi',
      'onMenuShareTimeline',
      'onMenuShareAppMessage'
    ]
  });
  wx.ready(function () {
      wx.onMenuShareAppMessage({
        //title: '我的麦能网',
        desc: "<?=$plandata['name']?>",
        link: "<?=$link?>",
        imgUrl: "<?=$wximg?>",
        trigger: function (res) {
          // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
          //alert('用户点击发送给朋友');
        },
        success: function (res) {
          //alert('已分享');
          //alert(JSON.stringify(res));
        },
        cancel: function (res) {
          //alert('已取消');
        },
        fail: function (res) {
          //alert(JSON.stringify(res));
        }
      });

      wx.onMenuShareTimeline({
        title: "<?=$plandata['name']?>",
        link: "<?=$link?>",
        //imgUrl: 'http://wx.mynep.com/dealer/img/icon.png',
        imgUrl: "<?=$wximg?>",
        trigger: function (res) {
          // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
          //alert('用户点击分享到朋友圈');
        },
        success: function (res) {
          //alert('已分享');
        },
        cancel: function (res) {
          //alert('已取消');
        },
        fail: function (res) {
          //alert(JSON.stringify(res));
        }
      });
  });
</script>
</html>