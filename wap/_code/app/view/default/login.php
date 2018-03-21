<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<link href="<?=$_BASE_DIR?>css/signin.css" rel="stylesheet">
  <div class="container">
    <form class="form-signin" method="post" enctype="application/x-www-form-urlencoded" action="">
      <h2 class="form-signin-heading">用户登录</h2>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="用户名" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="密码" required>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="rememberme" id="rememberme" value="1" checked> 记住我 
          <span><?=$error['message']?></span>
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">登 录</button>
    </form>

  </div> <!-- /container -->
<?php $this->_endblock(); ?>