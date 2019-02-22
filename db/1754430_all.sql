SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `wx_admin`;
CREATE TABLE `wx_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL COMMENT '用户登录名',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `gender` tinyint(4) DEFAULT '1' COMMENT '性别(0：女；1：男；)',
  `correspondence_id` int(11) DEFAULT NULL COMMENT '函授站',
  `role_name` varchar(64) DEFAULT NULL COMMENT '角色名称',
  `is_delete` tinyint(4) DEFAULT '0' COMMENT '是否删除',
  `my_quickentry` text COMMENT '快捷入口',
  `courseids` varchar(255) DEFAULT NULL COMMENT '任课老师课程',
  `disciplineids` varchar(255) DEFAULT NULL COMMENT '任课老师的专业',
  `p_id` tinyint(2) NOT NULL,
  `level` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

insert into `wx_admin`(`id`,`username`,`password`,`name`,`gender`,`correspondence_id`,`role_name`,`is_delete`,`my_quickentry`,`courseids`,`disciplineids`,`p_id`,`level`) values
('1','admin','96e79218965eb72c92a549dd5a330112','超级管理员','1',null,'zongadming','0','{"75":{"id":75,"name":"\\u4fee\\u6539\\u5bc6\\u7801","url":"admin\\/edit-password"}}','','','1','1'),
('2','test','96e79218965eb72c92a549dd5a330112','测试账号','1',null,null,'0',null,null,null,'1','0'),
('3','member','96e79218965eb72c92a549dd5a330112','测试','1',null,null,'0',null,null,null,'2','0'),
('4','黄斌','e10adc3949ba59abbe56e057f20f883e','黄斌','1',null,'zhaosheng','0',null,null,null,'0','0'),
('5','董必华','e10adc3949ba59abbe56e057f20f883e','董必华','1',null,'zhaosheng','0',null,null,null,'0','0'),
('6','testzs','96e79218965eb72c92a549dd5a330112','测试招生','1',null,'zhaosheng','0',null,null,null,'0','0'),
('7','陈善军','e10adc3949ba59abbe56e057f20f883e','123456','1',null,'zhaosheng','0',null,null,null,'0','0');
DROP TABLE IF EXISTS  `wx_auth_assignment`;
CREATE TABLE `wx_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  KEY `item_name` (`item_name`),
  CONSTRAINT `wx_auth_assignment_ibfk_2` FOREIGN KEY (`item_name`) REFERENCES `wx_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员授权表';

insert into `wx_auth_assignment`(`item_name`,`user_id`,`created_at`) values
('zhaosheng','5','1522132350'),
('zhaosheng','4','1522132366'),
('zongadming','1','1522132471'),
('zhaosheng','6','1522224665'),
('zhaosheng','7','1522382564');
DROP TABLE IF EXISTS  `wx_auth_item`;
CREATE TABLE `wx_auth_item` (
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `type` int(11) NOT NULL COMMENT '类型{1：角色；2：权限；}',
  `description` text COMMENT '描述',
  `rule_name` varchar(64) DEFAULT NULL COMMENT '规则名称',
  `data` text COMMENT '数据',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL COMMENT '权限所属菜单',
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  KEY `name` (`name`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `wx_auth_item_ibfk_2` FOREIGN KEY (`rule_name`) REFERENCES `wx_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理权权限条目';

insert into `wx_auth_item`(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`,`menu_id`) values
('admin_create','2','用户添加',null,null,null,null,'33'),
('admin_delete','2','用户删除',null,null,null,null,'33'),
('admin_qrcode','2','我的二维码',null,null,null,null,'68'),
('admin_quickentry','2','快捷入口设置',null,null,null,null,'35'),
('admin_update','2','用户修改',null,null,null,null,'33'),
('certificate_index','2','证书领取',null,null,null,null,'64'),
('channel_create','2','添加频道',null,null,null,null,'36'),
('course_index','2','课程管理查看',null,null,null,null,'49'),
('grade_index','2','成绩管理',null,null,null,null,'62'),
('itemspreset_index','2','转码查看',null,null,null,null,'40'),
('itemstype/index','2','视频分类查看',null,null,null,null,'39'),
('itemsupload_index','2','视频上传',null,null,null,null,'43'),
('items_index','2','视频查看',null,null,null,null,'41'),
('jf_index','2','积分管理',null,null,null,null,'61'),
('jianding_del','2','鉴定报名删除',null,null,null,null,'54'),
('jianding_index','2','鉴定报名管理',null,null,null,null,'54'),
('jianding_nosh','2','商务委人才未审核管理',null,null,null,null,'67'),
('lession_index','2','课程管理',null,null,null,null,'57'),
('marketer_index','2','招生人员',null,null,null,null,'71'),
('member_index','2','会员管理',null,null,null,null,'60'),
('menu_create','2','菜单添加',null,null,null,null,'32'),
('menu_delete','2','菜单删除',null,null,null,null,'32'),
('menu_taxis','2','菜单排序',null,null,null,null,'32'),
('menu_update','2','菜单修改',null,null,null,null,'32'),
('news_index','2','公告信息',null,null,null,null,'66'),
('order_index','2','订单管理',null,null,null,null,'70'),
('password_update','2','修改密码',null,null,null,null,'75'),
('permission_create','2','权限添加',null,null,null,null,'30'),
('permission_delete','2','权限删除',null,null,null,null,'30'),
('permission_update','2','权限修改',null,null,null,null,'30'),
('plan_del','2','活动删除',null,null,null,null,'55'),
('plan_index','2','活动管理',null,null,null,null,'55'),
('plan_share','2','活动分享',null,null,null,null,'55'),
('platform_index','2','平台管理',null,null,null,null,'52'),
('resource_index','2','资料管理',null,null,null,null,'63'),
('role_create','2','角色添加',null,null,null,null,'31'),
('role_delete','2','角色删除',null,null,null,null,'31'),
('role_permission','2','角色权限设置',null,null,null,null,'31'),
('role_update','2','角色修改',null,null,null,null,'31'),
('shortcut_update','2','设置快捷方式',null,null,null,null,'74'),
('tax_record','2','开票管理',null,null,null,null,'72'),
('teacher_index','2','教师管理',null,null,null,null,'58'),
('zhaosheng','1','招生人员',null,null,null,null,null),
('zongadming','1','总管理员',null,null,null,null,null),
('zsinfo_index','2','我的会员',null,null,null,null,'69');
DROP TABLE IF EXISTS  `wx_auth_item_child`;
CREATE TABLE `wx_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员权限关系表';

insert into `wx_auth_item_child`(`parent`,`child`) values
('zongadming','admin_create'),
('zongadming','admin_delete'),
('zhaosheng','admin_qrcode'),
('zongadming','admin_qrcode'),
('zhaosheng','admin_quickentry'),
('zongadming','admin_quickentry'),
('zongadming','admin_update'),
('teacher','enroll_score'),
('zhaosheng','jianding_del'),
('zongadming','jianding_del'),
('zhaosheng','jianding_index'),
('zongadming','jianding_index'),
('zhaosheng','jianding_nosh'),
('zongadming','jianding_nosh'),
('zongadming','lession_index'),
('zongadming','marketer_index'),
('zongadming','member_index'),
('zongadming','menu_create'),
('zongadming','menu_delete'),
('zongadming','menu_taxis'),
('zongadming','menu_update'),
('zongadming','news_index'),
('teacher','notice_create'),
('teacher','notice_delete'),
('teacher','notice_edit'),
('zongadming','order_index'),
('zongadming','permission_create'),
('zongadming','permission_delete'),
('zongadming','permission_update'),
('zongadming','plan_del'),
('zongadming','plan_index'),
('zhaosheng','plan_share'),
('zongadming','plan_share'),
('zongadming','resource_index'),
('zongadming','role_create'),
('zongadming','role_delete'),
('zongadming','role_permission'),
('zongadming','role_update'),
('zongadming','tax_record'),
('zongadming','teacher_index'),
('zhaosheng','zsinfo_index'),
('zongadming','zsinfo_index');
DROP TABLE IF EXISTS  `wx_auth_rule`;
CREATE TABLE `wx_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `name` (`name`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员权限规则表';

DROP TABLE IF EXISTS  `wx_jf`;
CREATE TABLE `wx_jf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL COMMENT '会员id',
  `jf` float DEFAULT NULL COMMENT '积分',
  `way` varchar(64) DEFAULT NULL COMMENT '获取方式',
  `datetime` varchar(32) DEFAULT NULL COMMENT '积分日期',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='积分表';

insert into `wx_jf`(`id`,`mid`,`jf`,`way`,`datetime`) values
('1','1',8.0,'register','1522074732'),
('2','2',10.0,'register','1522119984'),
('3','3',10.0,'register','1522120046'),
('4','4',10.0,'register','1522122616'),
('5','4',10.0,'register','1522122635'),
('6','5',10.0,'register','1522122708'),
('7','5',10.0,'register','1522122724'),
('8','5',10.0,'register','1522132542'),
('9','5',10.0,'register','1522132592'),
('10','6',10.0,'register','1522390607'),
('11','9',8.0,'register','1522400612'),
('12','10',8.0,'register','1522400710'),
('13','11',11.0,'register','1522673651');
DROP TABLE IF EXISTS  `wx_jianding_table`;
CREATE TABLE `wx_jianding_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL COMMENT '活动id',
  `company` varchar(128) DEFAULT NULL COMMENT '申报单位',
  `name` varchar(64) DEFAULT NULL COMMENT '姓名',
  `sex` tinyint(4) DEFAULT NULL COMMENT '1：男 0：女',
  `nation` varchar(32) DEFAULT NULL COMMENT '民族',
  `birthday` varchar(32) DEFAULT NULL COMMENT '出生年月',
  `sfz` varchar(64) DEFAULT NULL COMMENT '身份证',
  `bkzs` varchar(64) DEFAULT NULL COMMENT '报考证书',
  `bkfx` varchar(128) DEFAULT NULL COMMENT '报考方向',
  `zsdj` varchar(64) DEFAULT NULL COMMENT '证书等级',
  `tel` varchar(32) DEFAULT NULL COMMENT '联系方式',
  `education` text COMMENT '教育经历',
  `job` text COMMENT '工作经历',
  `score` float DEFAULT '0' COMMENT '成绩分数',
  `is_sh` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否付款 0未付 1已付',
  `zs_id` int(11) NOT NULL DEFAULT '0' COMMENT '招生人员id，0为非推广途径',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单id',
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`),
  KEY `sfz` (`sfz`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='电子商务专业人才鉴定申请表';

insert into `wx_jianding_table`(`id`,`plan_id`,`company`,`name`,`sex`,`nation`,`birthday`,`sfz`,`bkzs`,`bkfx`,`zsdj`,`tel`,`education`,`job`,`score`,`is_sh`,`is_pay`,`zs_id`,`is_delete`,`orderid`) values
('1','17','asd','sdf','1','a','2018/1/1','33022719820614275x',null,null,null,'23433a','[["","","",""]]','[["","","",null]]',0.0,'1','0','0','0','1'),
('2','18','吉云控股','陈善军','1','汉','1994/1/1','330204199606185016',null,null,null,'13736015159','[["2017-10-2019-8","海南政府职业学院","安全防范技术","大专"]]','[["2017-10-","宁波保时捷","销售顾问",null]]',0.0,'1','1','0','0','2'),
('3','18','吉云控股','黄斌','1','汉','1987/11/14','330211132211140070',null,null,null,'15957482250','[["2014-2016","石油大学","石油","大专"]]','[["2016-2017","","吉博培训学校",null]]',0.0,'1','1','0','0','3'),
('4','18','吉博','小辛','1','汗','2018/1/1','231121199311194520',null,null,null,'18868936163','[["2011","剑桥","学前教育","本科"]]','[["","","",null]]',0.0,'1','0','0','0','4'),
('5','18','吉博','小辛','1','汗','2018/1/1','231121199311194520',null,null,null,'18868936163','[["2011","剑桥","学前教育","本科"]]','[["2011","","",null]]',0.0,'1','0','0','0','5'),
('6','18','吉博','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"]]','[["","","",null]]',0.0,'1','0','0','0','6'),
('7','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","\\u4f60","\\u6211","\\u4f60"]]','[["","",""]]',0.0,'1','1','0','0','7'),
('8','18','宁波市镇海区吉博培训学校','黄斌学生','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],[null,null,null,null]]','[["","","",null],[null,null,null,null]]',0.0,'1','1','4','0','8'),
('9','18','宁波市镇海区吉博培训学校','小董学生','0','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],[null,null,null,null]]','[["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','5','0','9'),
('10','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],["","","",""],[null,null,null,null]]','[["","","",null],["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','0','0','0'),
('11','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],["","","",""],["","","",""],[null,null,null,null]]','[["","","",null],["你","你","你",null],["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','0','0','0'),
('12','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],["","","",""],["","","",""],["","","",""],[null,null,null,null]]','[["我","你","我",null],["你","你","你",null],["","","",null],["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','0','0','0'),
('13','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],["","","",""],["","","",""],["","","",""],["","","",""],[null,null,null,null]]','[["我","你","我",null],["你","你","你",null],["","","",null],["","","",null],["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','0','0','0'),
('14','18','宁波市镇海区吉博培训学校','小辛','1','汉','2018/1/1','288619885668755',null,null,null,'12886589965','[["t2885","你","我","你"],["","","",""],["","","",""],["","","",""],["","","",""],["","","",""],["","","",""],[null,null,null,null]]','[["我","你","我",null],["你","你","你",null],["","","",null],["","","",null],["","","",null],["","","",null],["","","",null],[null,null,null,null]]',0.0,'1','0','0','0','0'),
('15','18','asf','sadf','1','dd','2018/1/1','332669696969696969',null,null,null,'2222222','[["","","",""]]','[["","","",null]]',0.0,'0','0','0','0','10'),
('16','18','asf','sadf','1','dd','2018/1/1','332669696969696969',null,null,null,'2222222','[["we","er","er","er"]]','[["wr","wr","er",null]]',0.0,'0','0','0','0','0'),
('17','17','asf','sadf','1','dd','2018/1/1','332669696969696969',null,null,null,'2222222','[["we","er","er","er"]]','[["wr","wr","er",null]]',0.0,'0','0','0','0','11'),
('18','17','爸陌陌','拉进来','1','汉','2018/1/1','236698555885885',null,null,null,'32288888','[["14","拉","才看见","来了"]]','[["来来来","土质","图",null]]',0.0,'1','1','0','0','12'),
('19','19',null,'阿克墨迹','1','号','2015/1/1','993682841269856656',null,null,null,'13563639853','[["1111","家里只有","噢噢噢","有需要"]]','[["提醒下我","腾讯云","下雨",null]]',0.0,'1','1','6','0','13');
DROP TABLE IF EXISTS  `wx_lession`;
CREATE TABLE `wx_lession` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL COMMENT '课程名称',
  `img` varchar(128) DEFAULT NULL COMMENT '封面',
  `description` text COMMENT '描述',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='课程表';

insert into `wx_lession`(`id`,`name`,`img`,`description`,`is_delete`) values
('1','电子商务','','电子商务','0'),
('2','小辛测试2','','','0');
DROP TABLE IF EXISTS  `wx_mark`;
CREATE TABLE `wx_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL COMMENT '会员id',
  `course_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程评分',
  `teacher_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT '教师评分',
  `teacher_id` int(11) DEFAULT NULL COMMENT '教师id',
  `course_id` int(11) DEFAULT NULL COMMENT '课程id',
  `datetime` int(11) DEFAULT NULL COMMENT '创建时间',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `message` text COMMENT '评论',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='课程教师评分表';

DROP TABLE IF EXISTS  `wx_member`;
CREATE TABLE `wx_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL COMMENT '姓名',
  `cid` varchar(64) DEFAULT NULL COMMENT '身份证',
  `nation` varchar(32) DEFAULT NULL COMMENT '民族',
  `birthday` varchar(32) DEFAULT NULL COMMENT '生日',
  `sex` tinyint(4) DEFAULT '1' COMMENT '1男 0女',
  `tel` varchar(32) DEFAULT NULL COMMENT '手机号码',
  `username` varchar(64) DEFAULT NULL COMMENT '用户名',
  `pass` varchar(64) DEFAULT NULL COMMENT '密码',
  `jf` float DEFAULT NULL COMMENT '积分',
  `source` tinyint(4) DEFAULT NULL COMMENT '数据来源(哪类报名)',
  `sid` int(11) DEFAULT NULL COMMENT '对应报名id',
  `datetime` varchar(32) DEFAULT NULL COMMENT '注册日期',
  `sfz_path` varchar(64) DEFAULT NULL COMMENT '身份证路径',
  `pic_path` varchar(64) DEFAULT NULL COMMENT '照片路径',
  `getway` tinyint(4) DEFAULT '1' COMMENT '证书领取方式 1:自取 2:快递',
  `address` varchar(255) DEFAULT NULL COMMENT '寄送地址',
  `express_name` varchar(128) DEFAULT NULL,
  `express_tel` varchar(32) DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `totalfee` float DEFAULT NULL COMMENT '总消费金额',
  `taxed` float DEFAULT NULL COMMENT '已开发票',
  `taitou` varchar(128) DEFAULT NULL COMMENT '发票抬头',
  `taxno` varchar(32) DEFAULT NULL COMMENT '税号',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员表';

insert into `wx_member`(`id`,`name`,`cid`,`nation`,`birthday`,`sex`,`tel`,`username`,`pass`,`jf`,`source`,`sid`,`datetime`,`sfz_path`,`pic_path`,`getway`,`address`,`express_name`,`express_tel`,`is_delete`,`totalfee`,`taxed`,`taitou`,`taxno`) values
('1','sdf','33022719820614275x','a','2018/1/1','1','23433a','33022719820614275x','14275x',8.0,'2','1','1522074732',null,null,'1',null,null,null,'0',112.0,16.0,'浙江美大','21155111x'),
('2','陈善军','330204199606185016','汉','1994/1/1','1','13736015159','330204199606185016','185016',10.0,'2','2','1522119984',null,null,'1',null,null,null,'0',null,null,null,null),
('3','黄斌','330211132211140070','汉','1987/11/14','1','15957482250','330211132211140070','140070',10.0,'2','3','1522120046',null,null,'1',null,null,null,'0',null,null,null,null),
('4','小辛','231121199311194520','汗','2018/1/1','1','18868936163','231121199311194520','194520',10.0,'2','4','1522122616',null,null,'1',null,null,null,'0',null,null,null,null),
('5','小辛','288619885668755','汉','2018/1/1','1','12886589965','288619885668755','668755',10.0,'2','6','1522122708','./upload/sfz/288619885668755','./upload/zj/288619885668755.jpg','1',null,null,null,'0',null,null,null,null),
('6','sadf','332669696969696969','dd','2018/1/1','1','2222222','332669696969696969','696969',10.0,'2','15','1522390607',null,null,'1',null,null,null,'0',null,null,null,null),
('7','陈世美',null,null,null,'1','13568635214',null,null,null,'2',null,'1522399920',null,null,'1',null,null,null,'0',null,null,null,null),
('8','武大郎',null,null,null,'1','13569635214',null,null,null,'2',null,'1522400129',null,null,'1',null,null,null,'0',null,null,null,null),
('9','6666','332669696969696969','dd','2018/1/1','1','666666','332669696969696969','696969',8.0,'2','17','1522400601',null,null,'1',null,null,null,'0',null,null,null,null),
('10','拉进来','236698555885885','汉','2018/1/1','1','32288888','236698555885885','885885',8.0,'2','18','1522400652',null,null,'1',null,null,null,'0',null,null,null,null),
('11','阿克墨迹','993682841269856656','号','2015/1/1','1','13563639853','993682841269856656','856656',11.0,'2','19','1522673605','./upload/sfz/993682841269856656','./upload/zj/993682841269856656.jpg','1',null,null,null,'0',null,null,null,null),
('12','aa',null,null,null,'1','123332222',null,null,null,'2',null,'1522674339',null,null,'1',null,null,null,'0',null,null,null,null);
DROP TABLE IF EXISTS  `wx_menu`;
CREATE TABLE `wx_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '名称',
  `parent` int(11) DEFAULT '0' COMMENT '上级菜单',
  `route` varchar(256) DEFAULT NULL,
  `taxis` int(11) DEFAULT '0' COMMENT '排序字段 默认0,以数字倒序排列',
  `data` text,
  `url` varchar(100) DEFAULT NULL COMMENT '菜单链接地址',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `name` (`name`),
  KEY `route` (`route`(255)),
  KEY `order` (`taxis`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COMMENT='系统管理员菜单权限表\r\n';

insert into `wx_menu`(`id`,`name`,`parent`,`route`,`taxis`,`data`,`url`) values
('29','系统设置','0',null,'20',null,''),
('30','权限管理','29',null,'8',null,'permission-form/index'),
('31','角色管理','29',null,'7',null,'role-form/index'),
('32','菜单管理','29',null,'6',null,'menu/index'),
('33','系统账号','29',null,'5',null,'admin/index'),
('34','设置快捷方式','29',null,'1',null,'shortcut/index'),
('35','修改密码','29',null,'9',null,'admin/edit-password'),
('39','分类设置','38',null,'42',null,'items-type/index'),
('40','转码设置','38',null,'41',null,'items-preset/index'),
('41','视频管理','38',null,null,null,'items/index'),
('43','视频上传','38',null,null,null,'items-upload/index'),
('49','直播管理','37',null,null,null,'course/index'),
('51','平台管理','29',null,null,null,'platform/index'),
('52','平台管理','37',null,null,null,'platform/index'),
('53','在线报名','0',null,'40',null,''),
('54','商务委人才申请-已审核','53',null,'14',null,'jianding/index'),
('55','活动管理','53',null,'20',null,'plan/index'),
('56','教务管理','0',null,'39',null,''),
('57','课程管理','56',null,'9',null,'lession/index'),
('58','教师管理','56',null,'8',null,'teacher/index'),
('60','会员管理','56',null,null,null,'member/index'),
('62','成绩管理','56',null,'7',null,'grade/index'),
('63','资料管理','56',null,'6',null,'resource/index'),
('64','证书领取','56',null,'5',null,'certificate/index'),
('66','公告信息','56',null,'4',null,'news/index'),
('67','商务委人才申请-未审核','53',null,'15',null,'jianding/nosh'),
('68','我的二维码','29',null,null,null,'admin/qrcode'),
('69','招生信息','56',null,null,null,'zsinfo/index'),
('70','订单管理','56',null,null,null,'order/index'),
('71','招生人员','29',null,'5',null,'admin/marketer'),
('72','开票管理','56',null,null,null,'taxrecord/index');
DROP TABLE IF EXISTS  `wx_news`;
CREATE TABLE `wx_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `pic` varchar(128) DEFAULT NULL COMMENT '图片',
  `attachment` varchar(255) DEFAULT NULL COMMENT '附件',
  `datetime` int(11) DEFAULT NULL COMMENT '日期',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `type` tinyint(4) DEFAULT '1' COMMENT '1：新闻 2：资料',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻表';

insert into `wx_news`(`id`,`title`,`content`,`pic`,`attachment`,`datetime`,`is_delete`,`type`) values
('11','333','<p>333</p>',null,null,'1521993600','0','1'),
('12','不包','<p>饿</p>',null,null,'1521993600','0','2');
DROP TABLE IF EXISTS  `wx_order`;
CREATE TABLE `wx_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL COMMENT '订单号',
  `price` float NOT NULL COMMENT '价格',
  `order_time` varchar(16) NOT NULL COMMENT '订单时间',
  `state` tinyint(4) NOT NULL COMMENT '支付状态：0：处理中；1：支付成功',
  `mid` int(11) NOT NULL COMMENT '会员id',
  `plan_id` int(11) DEFAULT NULL COMMENT '活动id',
  `source` tinyint(4) DEFAULT NULL COMMENT '数据来源(哪类报名)',
  `sid` int(11) DEFAULT NULL COMMENT '对应报名id',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';

insert into `wx_order`(`id`,`order_no`,`price`,`order_time`,`state`,`mid`,`plan_id`,`source`,`sid`,`is_delete`) values
('1','18032622321226',0.01,'1522074732','0','1','17','2','1','0'),
('2','18032711062428',0.1,'1522119984','1','2','18','2','2','0'),
('3','18032711072631',0.1,'1522120046','1','3','18','2','3','0'),
('4','18032711501654',0.1,'1522122616','0','4','18','2','4','0'),
('5','18032711503552',0.1,'1522122635','0','4','18','2','5','0'),
('6','18032711514870',0.1,'1522122708','0','5','18','2','6','0'),
('7','18032711520470',0.1,'1522122724','1','5','18','2','7','0'),
('8','18032714354253',0.1,'1522132542','1','5','18','2','8','0'),
('9','18032714363246',0.1,'1522132592','0','5','18','2','9','0'),
('10','18033014164787',0.1,'1522390607','0','6','18','2','15','0'),
('11','18033017033275',0.01,'1522400612','0','9','17','2','17','0'),
('12','18033017051058',0.01,'1522400710','1','10','17','2','18','0'),
('13','18040220541176',0.01,'1522673651','1','11','19','2','19','0');
DROP TABLE IF EXISTS  `wx_plan`;
CREATE TABLE `wx_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL COMMENT '活动名称',
  `tabletype` tinyint(4) DEFAULT NULL COMMENT '报名类型',
  `img` varchar(128) DEFAULT NULL COMMENT '图片',
  `jf` float DEFAULT NULL COMMENT '活动积分',
  `fee` float DEFAULT NULL COMMENT '费用',
  `bkzs` varchar(64) DEFAULT NULL COMMENT '报考证书',
  `bkfx` varchar(128) DEFAULT NULL COMMENT '报考方向',
  `zsdj` varchar(64) DEFAULT NULL COMMENT '证书等级',
  `description` text COMMENT '活动描述',
  `enddate` varchar(32) DEFAULT NULL COMMENT '报名结束日期',
  `course_id` int(11) DEFAULT NULL COMMENT '课程id',
  `teacher_id` int(11) DEFAULT NULL COMMENT '老师id',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `company` varchar(128) DEFAULT NULL COMMENT '申报单位',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='活动计划表';

insert into `wx_plan`(`id`,`name`,`tabletype`,`img`,`jf`,`fee`,`bkzs`,`bkfx`,`zsdj`,`description`,`enddate`,`course_id`,`teacher_id`,`is_delete`,`company`) values
('14','2017年7月电子商务职业资格鉴定','2','/uploads/201802145316-888.jpg',20.0,100.0,null,null,null,'','','1','1','0',null),
('15','美国营销国际协会（SMEI）中国峰会暨营销科学与创新人才','2','/uploads/201802145415-650.jpg',10.0,0.0,null,null,null,'','','1','1','0',null),
('16','小辛测试1','2','/uploads/201803134822-630.png',10.0,1.0,'bb','aa','cc','企业职工培训—电子商务','2018/03/23','1',null,'0',null),
('17','支付测试','2','/uploads/201803204806-735.jpg',8.0,0.01,'支付证书','微信支付','3级','','','1','1','0',null),
('18','小辛测试2','2','/uploads/201803105211-860.jpg',10.0,0.1,'助理电子商务师','移动互联网应用','初级','小辛测试2','','2','1','0',null),
('19','test','2','/uploads/201804152848-500.jpg',11.0,0.01,'cc','bb','dd','','','2','1','0','aa');
DROP TABLE IF EXISTS  `wx_platform`;
CREATE TABLE `wx_platform` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `private_key` varchar(64) DEFAULT NULL COMMENT '私钥',
  `public_key` varchar(64) DEFAULT NULL COMMENT '公钥',
  `describe` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS  `wx_taxrecord`;
CREATE TABLE `wx_taxrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taitou` varchar(128) DEFAULT NULL COMMENT '发票抬头',
  `taxno` varchar(32) DEFAULT NULL COMMENT '税号',
  `taxnum` float DEFAULT NULL COMMENT '发票金额',
  `tax_time` varchar(16) DEFAULT NULL COMMENT '开票时间',
  `mid` int(11) NOT NULL COMMENT '申请人',
  `isdone` tinyint(4) DEFAULT '0' COMMENT '是否开票 1已开票 0未开票',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='开票记录表';

insert into `wx_taxrecord`(`id`,`taitou`,`taxno`,`taxnum`,`tax_time`,`mid`,`isdone`) values
('1','浙江美大2222','21155111x222',3.0,'1522765848','1','0'),
('2','浙江美大','21155111x',5.0,'1522765855','1','1');
DROP TABLE IF EXISTS  `wx_taxrecord__seq`;
CREATE TABLE `wx_taxrecord__seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `wx_taxrecord__seq`(`id`) values
('4');
DROP TABLE IF EXISTS  `wx_teacher`;
CREATE TABLE `wx_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(32) DEFAULT NULL COMMENT '电话',
  `course_id` int(11) DEFAULT NULL COMMENT '课程id',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into `wx_teacher`(`id`,`name`,`phone`,`course_id`,`is_delete`) values
('1','周鑫鑫','12366659874','1','0');
DROP TABLE IF EXISTS  `wx_zsinfo`;
CREATE TABLE `wx_zsinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL COMMENT '活动id',
  `source` tinyint(4) DEFAULT NULL COMMENT '数据来源(哪类报名)',
  `sid` int(11) DEFAULT NULL COMMENT '对应报名id',
  `zs_id` int(11) DEFAULT NULL COMMENT '招生人员id',
  `mid` int(11) DEFAULT NULL COMMENT '学员id',
  `is_pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='招生信息表';

insert into `wx_zsinfo`(`id`,`plan_id`,`source`,`sid`,`zs_id`,`mid`,`is_pay`,`is_delete`) values
('8','18','2','8','4','5','1','0'),
('9','18','2','9','5','5','0','0'),
('10','19','2','19','6','11','1','0');
DROP TABLE IF EXISTS  `wx_zyzgjd_table`;
CREATE TABLE `wx_zyzgjd_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL COMMENT '活动id',
  `name` varchar(64) DEFAULT NULL COMMENT '姓名',
  `sex` tinyint(4) DEFAULT NULL COMMENT '1：男 0：女',
  `birthday` varchar(32) DEFAULT NULL COMMENT '出生年月',
  `edu_level` tinyint(4) DEFAULT NULL COMMENT '文化程度',
  `card_type` tinyint(4) DEFAULT NULL COMMENT '证件类型',
  `sfz` varchar(64) DEFAULT NULL COMMENT '证件号码',
  `nation` varchar(128) DEFAULT NULL COMMENT '户籍所在地',
  `hukou_type` tinyint(4) DEFAULT NULL COMMENT '户口性质',
  `company` varchar(128) DEFAULT NULL COMMENT '单位名称',
  `address` varchar(128) DEFAULT NULL COMMENT '通讯地址',
  `zipcode` varchar(16) DEFAULT NULL COMMENT '邮政编码',
  `tel` varchar(32) DEFAULT NULL COMMENT '联系电话',
  `phone` varchar(32) DEFAULT NULL COMMENT '手机号码',
  `email` varchar(64) DEFAULT NULL COMMENT '电子邮件',
  `zhiye_type` tinyint(4) DEFAULT NULL COMMENT '现职业资格',
  `zhicheng_type` tinyint(4) DEFAULT NULL COMMENT '现职称',
  `sbzy` varchar(128) DEFAULT NULL COMMENT '申报职业',
  `sbjb` tinyint(4) DEFAULT NULL COMMENT '申报级别',
  `examtype` tinyint(4) DEFAULT NULL COMMENT '考试类型',
  `khkm` tinyint(4) DEFAULT NULL COMMENT '考核科目',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='国家职业资格鉴定申请表';

SET FOREIGN_KEY_CHECKS = 1;

