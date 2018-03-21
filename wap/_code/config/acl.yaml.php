# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格。并且各条访问规则之间不能留有空行。

#############################
# 访问规则
#############################

# 访问规则示例
#模块分类在auth.yaml.php中
#模块权限 分成4个：1查看 2新增 3修改 4删除
# 如：create: enroll.2  表示create动作需要enroll模块的新增权限
# _allow表示整个controller的默认权限

enroll:
  _allow: enroll
  create: enroll.2
  edit: enroll.3
  delete: enroll.4

org:
  index: org
  export: org
  view: org
  create: org.2
  edit: org.3
  delete: org.4
  index2: org2
  export2: org2
  view2: org2
  create2: org2.2
  edit2: org2.3
  delete2: org2.4

discipline:
  _allow: discipline
  create: discipline.2
  edit: discipline.3
  delete: discipline.4

class:
  _allow: class
  create: class.2
  edit: class.3
  delete: class.4

#course:
#  _allow: course
#  create: course.2
#  edit: course.3
#  delete: course.4

users:
  index: stu
  export: stu
  view: stu
  edit: stu.3
  delete: stu.4
  dongjie: stu.3 
  
userbc:
  import: stubm
  index: bmview
  export: bmview
  edit: bmview.3
  delete: bmview.4
  slist: bmaudit
  sexport: bmaudit
  audit: bmaudit
  vlist: stuverify
  vexport: stuverify
  verify: stuverify

score:
  index: score
  export: score
  view: score
  edit: score.3
  delete: score.4
  import: scorein
  replace: scorerep

leavescholl:
  _allow: stugra
  finsh: stufin

gradudate:
  _allow: stuxjch

changezy:
  _allow index

fee:
  index: feecx
  export: feecx
  dlist: feecx
  view: feecx
  import: feein
  rlist: feeremain
  rexport: feeremain
  edit: feeremain.3
  jlist: feeback
  jexport: feeback
  feeback: feeback.2

stat:
  stustat: stat1
  gpassstat: stat2
  upass: stat3
  unpass: stat4
  cpass: stat5

admin:
  profile: profile
  index: admin
  create: admin.2
  edit: admin.3
  auth: admin.3
  delete: admin.4

log:
  _allow: log
  delete: log.4
