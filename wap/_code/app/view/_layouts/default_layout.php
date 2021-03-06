<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>麦能网</title>

    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Include the compiled Ratchet CSS -->
    <link href="<?=$_BASE_DIR?>css/ratchet.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>css/style.css">

    <!-- Include the compiled Ratchet JS -->
    <script src="<?=$_BASE_DIR?>js/ratchet.js"></script>
    <script src="<?=$_BASE_DIR?>js/vendor/zepto.js"></script>
    <!-- QQ登录meta -->
    <meta property="qc:admins" content="225572625476516506375" />
    <!-- 微博登录meta -->
    <meta property="wb:webmaster" content="390d28746c2dfd7e" />
  </head>
  <body>

    <!-- Make sure all your bars are the first things in your <body> -->
    <header class="bar bar-nav">
      <h1 class="title">麦能网</h1>
    </header>
    <?$this->_block('contents');?><?$this->_endblock();?>
  </body>
</html>