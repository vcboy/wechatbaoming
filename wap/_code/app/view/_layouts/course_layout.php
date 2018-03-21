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
  </head>
  <body>

    <?$this->_block('contents');?><?$this->_endblock();?>
  </body>
</html>
