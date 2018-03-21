#<?php die(); ?>

#############################
# 权限模块
#############################

#模块分组显示
#name 模块名称
#perms 模块权限 分成4个：1查看 2新增 3修改 4删除
#url 模块首页地址
#baselevel 此模块在指定管理员等级及以上有权限
#levels 数组，此模块只有指定等级的管理员有权限
#subbaselv 子功能在指定管理员等级及以上有权限

-
  _label: 图书管理
  _group: 图书管理
  
  booktype:
    name:  图书分类
    perms: [1, 2, 3, 4]
    url: booktype
    subbaselv:
      2: [1]
      3: [1]
      4: [1]
  book:
    name:  图书列表
    perms: [1, 2, 3, 4]
    url: book
    subbaselv:
      2: [1]
      3: [1]
      4: [1]
  audiobook:
    name:  有声读物
    perms: [1, 2, 3, 4]
    url: book/audioindex
    subbaselv:
      2: [1]
      3: [1]
      4: [1]
  bookcomment:
    name:  书评管理
    perms: [1, 2, 3, 4]
    url: bookcomment
    subbaselv:
      2: [1]
      3: [1]
      4: [1]

-
  _label: 测评管理
  course:
    name: 科目管理
    perms: [1, 2, 3, 4]
    url: course
  questiontype:
    name: 题型管理
    perms: [1, 2, 3, 4]
    url: questiontype
  questions:
    name: 题库详情
    perms: [1, 2, 3, 4]
    url: questions/list
  coursetemplate:
    name: 评测试卷模板
    perms: [1, 2, 3, 4]
    url: coursetemplate
  lxtask:
    name: 评测活动管理
    perms: [1, 2, 3, 4]
    url: coursetemplate/task
  lxcj:
    name: 评测成绩管理
    perms: [1, 2, 3, 4]
    url: coursetemplate/cj
-
  _label: 读书活动
  huodong:
    name: 活动管理
    perms: [1, 2, 3, 4]
    url: huodong
  zuopin:
    name: 作品管理
    perms: [1, 2, 3, 4]
    url: huodong/zuopin
  comments:
    name: 作品评论
    perms: [1, 2, 3, 4]
    url: huodong/comment

-
  _label: 新闻管理

  newstype:
    name: 新闻分类
    perms: [1,2,3,4]
    url: newstype
  news:
    name: 新闻列表
    perms: [1,2,3,4]
    url: news

-
  _label: 系统设置
  _group: 系统设置

  profile:
    name: 我的账户
    perms: [3]
    url: admin/profile
  admin:
    name: 系统账户
    perms: [1, 2, 3, 4]
    url: admin
    baselevel: [1, 4]
  group:
    name: 用户组管理
    perms: [1, 2, 3, 4]
    url: group
  log:
    name: 操作日志
    perms: [1]
    url: log
    baselevel: [1, 2, 3, 4]
  message:
    name: 在线留言管理
    perms: [1, 2, 3, 4]
    url: message
  survey:
    name: 问卷调查
    perms: [1, 2, 3, 4]
    url: survey
-
  _label: 班级管理
  _group: 班级管理

  grade:
    name: 年级管理
    perms: [1,2,3,4]
    url: class/grade
  class:
    name: 班级管理
    perms: [1,2,3,4]
    url: class
  classbook:
    name: 班级藏书
    perms: [1,2,3,4]
    url: classbook
-
  _label: 权限账号

  student:
    name: 学生账号
    perms: [1,2,3,4]
    url: user/student
  teacher:
    name: 老师账号
    perms: [1,2,3,4]
    url: user/teacher
  parent:
    name: 家长账号
    perms: [1,2,3,4]
    url: user/parent
-
  _label: 札记管理

  zj_type:
    name: 札记分类
    perms: [1,2,3,4]
    url: zj/type
  zj:
    name: 读书札记
    perms: [1,2,3,4]
    url: zj/index
  zj_comment:
    name: 札记评论
    perms: [1,2,3,4]
    url: zj/comment
-
  _label: 佳作管理

  jz_type:
    name: 佳作分类
    perms: [1,2,3,4]
    url: jz/type
  jz:
    name: 佳作欣赏
    perms: [1,2,3,4]
    url: jz/index
  jz_comment:
    name: 佳作评论
    perms: [1,2,3,4]
    url: jz/comment
-
  _label: 积分管理

  score_type:
    name: 积分分类
    perms: [1,2,3,4]
    url: scoretype
  score_rule:
    name: 积分规则
    perms: [1,2,3,4]
    url: scorerule
  score_record:
    name: 积分记录
    perms: [1,2,3,4]
    url: scorerecord
