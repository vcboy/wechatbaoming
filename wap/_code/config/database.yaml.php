# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格

##############################
# 数据库设置
##############################

# devel 模式
devel:
  driver:     mysqli
  host:       127.0.0.1
  login:      root
  password:   root
  database:   wechatbaoming
  charset:    utf8
  prefix:

# deploy 模式
deploy:
  driver:     mysqli
  host:       127.0.0.1
  login:      aaa
  password:   bbb
  database:   ccc
  charset:    utf8
  prefix:
