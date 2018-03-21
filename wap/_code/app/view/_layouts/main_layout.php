<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>书香园</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=$_BASE_DIR?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=$_BASE_DIR?>css/main.css" rel="stylesheet">
    <link href="<?=$_BASE_DIR?>css/style.css" rel="stylesheet">
    
	  <link href="<?=$_BASE_DIR?>css/tyn.css" rel="stylesheet">

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
    <script src="<?=$_BASE_DIR?>js/common.js"></script>
  </head>

  <body>
    
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed bleft" id="reback">
            <span class="sr-only">Toggle navigation</span>
            <span class="glyphicon glyphicon-chevron-left" style="font-size:20px;color: #666;"></span>
          </button>
          <a class="navbar-brand" href="#"><?=$navtitle?></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?=url('default/index')?>">首页</a></li>
            <li><a href="<?=url('book/')?>">阅读天地</a></li>
            <li><a href="<?=url('zj/')?>">读书札记</a></li>
            <li><a href="<?=url('jz/')?>">佳作欣赏</a></li>
            <li><a href="<?=url('hd/')?>">读书活动</a></li>
            <?php if(empty($_SESSION['user']['name'])){?>
            <li class="active"><a href="<?=url('default/login')?>">登录</a></li>
            <?php
            }else{
            ?>
            <li class="active"><a href="<?=url('userbook/index')?>">欢迎 <?=$_SESSION['user']['name']?>,点击进入个人书吧</a><a href="<?=url('default/logout')?>">退出</a></li>
            <?php
            }
            ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <?$this->_block('contents');?><?$this->_endblock();?>
  </body>
</html>
