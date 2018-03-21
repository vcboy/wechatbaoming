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
    <script src="<?=$_BASE_DIR?>js/common.js"></script>

    <script type="text/javascript">
        function back() {
            //window.location.href="<?echo url('/lent')?>"
            window.history.back();
        }
    </script>
  </head>

  <body>
    <!-- Navigation -->
    <!-- <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            Brand and toggle get grouped for better mobile display
            <div class="navbar-header">
                <div class="navbar-brand" href="index.html">Start Bootstrap</div>
            </div>
            /.navbar-collapse
        </div>
        /.container
    </nav> -->
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand" >微信报名</span>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <!-- <li>
                        <a href="about.html">About</a>
                    </li>
                    <li>
                        <a href="services.html">Services</a>
                    </li>
                    <li>
                        <a href="contact.html">Contact</a>
                    </li> -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">个人中心 <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <!-- <li>
                                <a href="<?=url("default/myhd")?>">我的活动</a>
                            </li> -->
                            <li>
                                <a href="<?=url("default/myscore")?>">我的活动</a>
                            </li>
                            <!-- <li>
                                <a href="<?=url("default/setmark")?>">课程打分</a>
                            </li> -->
                            <li>
                                <a href="<?=url("default/receivecard")?>">证书领取</a>
                            </li>
                            <li>
                                <a href="<?=url("default/usercenter")?>">个人资料</a>
                            </li> 
                            <li>
                                <a href="<?=url("default/logout")?>">退出</a>
                            </li>                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">在线服务<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?=url("baoming/planlist")?>">活动报名</a>
                            </li>
                            <li>
                                <a href="<?=url("default/notice")?>">资料下载</a>
                            </li>
                            <li>
                                <a href="<?=url("news/")?>">公告信息</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <?$this->_block('contents');?><?$this->_endblock();?>
    <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p style="margin: 0  10px;">Copyright &copy; Your Website 2017</p>
                </div>
            </div>
        </footer>
  </body>
</html>
