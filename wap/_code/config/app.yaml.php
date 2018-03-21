# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格

#############################
# 应用程序设置
#############################

# 在这里添加应用程序需要的设置
# 这里的设置可以用 Q::ini('appini/设置名') 来读取，例如 Q::ini('appini/app_title');
genders: [女, 男]

# 答题选项索引
optionsindex:
  0: A
  1: B
  2: C
  3: D
  4: E
  5: F
  6: G
 
# 大写数字
bignum:
  0: 一
  1: 二
  2: 三
  3: 四
  4: 五
  5: 六
  6: 七
  7: 八
  8: 九
  9: 十
  10: 十一
  11: 十二

# 考生来源
use_sources:
  1: 学校   
  2: 企业  
  3: 部队  
  4: 社会  
  5: 其他

# 课程状态
course_states:
  0: 未开始
  1: 学习中
  2: 完成

# 考核科目
exam_sources:
  1: 理论
  2: 技能
  3: 综合评审
  4: 外语

#申报级别
sb_sources:
  1: 五级
  2: 四级
  3: 三级
  4: 二级
  5: 一级

#文化程度
edu_level:
  1: 小学
  2: 初中
  3: 职高
  4: 高中
  5: 技校
  6: 髙技
  7: 高职
  8: 中专
  9: 大专
  10: 大学本科
  11: 硕士
  12: 博士
  13: 其他

#证件类型
card_type:
  1: 身份证
  2: 军官证
  3: 香港证件
  4: 澳门证件
  5: 台湾证件
  6: 外国护照

#户口性质
hukou_type:
  1: 本市城镇
  2: 本市农村
  3: 非本市城镇
  4: 非本市农村
  5: 台港澳人员
  6: 外籍人员

#职称
zhicheng_type:
  1: 初级职称
  2: 中级职称
  3: 高级职称

#职业资格
zhiye_type:
  1: 无等级
  2: 五级
  3: 四级
  4: 三级
  5: 二级
  6: 一级


#课程考试类型
exam_type: [省考,委托考试,实践考试,免考申报,证书顶替]

admin_levels: 
  0: 请选择
  1: 超级管理员
  6: 二级管理员
  4: 主考院校管理
  2: 主考院校
  3: 学习中心
  5: 班主任


#活动类型  
tabletype:
  1: 职业资格鉴定
  2: 商务委电子商务专业人才鉴定申请
  3: 商务委电子商务培训报名
  4: 教育局企业职工报名


notif_type:
  1: 重要
  2: 教务
  3: 学籍
  4: 教学
  5: 考试
  6: 成绩
  7: 教材
  8: 选课
  9: 毕设
  10: 补考
  11: 答疑
  12: 学费

#书本类型
book_attr:
  1: PDF
  2: EPUB
  3: HTML
#学生状态
user_status:
  0: 未审核
  1: 审核通过
  2: 审核未通过
#推荐名称
tj_name:
  0: 不推荐
  1: 推荐
#加分方式
operate_type:
  1: 自动
  2: 手动
#积分操作
operate:
  1: 加分
  2: 减分
#调查问卷----题目类型
survey_type:
  1: 单选
  2: 多选
#调查问卷----是否限制IP
ip_limit:
  1: 限制IP
  2: 不限制IP
#默认头像
default_head:
  0: upload/default/8.jpg
  1: upload/default/10.jpg

web_url_root: http://127.0.0.1/sxxy/

#网站路径
url_info: http://www.mynep.com
#url_info: http://10.82.97.204/mynep
#url_info: http://localhost/mynep

#课程中心 地址
course_center: http://cc.mynep.com.cn

#课程中心数据库连接
website_id: 53

#QQ登录OAuth
qq_oauth:
  appid: 101214550
  appkey: ec522d7e0d359d28160fbd641b682300
  callback: /qq_back.htm

#微博登录OAuth
wb_oauth:
  appid: 3574580874
  appkey: c8d1b0ac65a095f122732d8c3f63af9f


