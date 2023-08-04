
-- -----------------------------
-- Table structure for `#@__member`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member`;
CREATE TABLE `#@__member` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT '关键id',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `is_mobile` tinyint(1) DEFAULT '0' COMMENT '绑定手机号，0为不绑定，1为绑定',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码（仅用于登录）',
  `is_email` tinyint(1) DEFAULT '0' COMMENT '绑定邮箱，0为不绑定，1为绑定',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '电子邮件（仅用于登录）',
  `qicq` varchar(60) NOT NULL DEFAULT '' COMMENT 'QQ',
  `paypwd` varchar(50) DEFAULT '' COMMENT '支付密码，暂时未用到，可保留。',
  `member_money` decimal(10,2) DEFAULT '0.00' COMMENT '用户金额',
  `frozen_money` decimal(10,2) DEFAULT '0.00' COMMENT '冻结金额',
  `member_integral` decimal(10,0) DEFAULT '0' COMMENT '会员积分',
  `last_login` int(11) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) DEFAULT '' COMMENT '最后登录ip',
  `login_count` int(11) DEFAULT '0' COMMENT '登陆次数',
  `head_pic` varchar(255) DEFAULT '' COMMENT '头像',
  `remark` varchar(255) DEFAULT '' COMMENT '说明备注',
  `province_id` int(6) DEFAULT '0' COMMENT '省份',
  `city_id` int(6) DEFAULT '0' COMMENT '市区',
  `county_id` int(6) DEFAULT '0' COMMENT '县',
  `level_id` smallint(5) DEFAULT '1' COMMENT '会员等级，默认为1，注册会员 ',
  `open_level_time` int(11) unsigned DEFAULT '0' COMMENT '开通会员级别时间',
  `level_maturity_days` varchar(20) DEFAULT '' COMMENT '会员级别到期天数',
  `discount` decimal(10,2) DEFAULT '1.00' COMMENT '会员折扣，默认1不享受',
  `total_amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费累计额度',
  `is_activation` tinyint(1) DEFAULT '0' COMMENT '是否激活，0否，1是。\\r\\n后台注册默认为1激活。\\r\\n前台注册时，当会员功能设置选择后台审核，需后台激活才可以登陆。',
  `register_place` tinyint(1) DEFAULT '2' COMMENT '注册位置。后台注册不受注册验证影响，1为后台注册，2为前台注册。默认为2。',
  `open_id` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方唯一标识openid',
  `talk_code` varchar(256) NOT NULL DEFAULT '' COMMENT '第三方洽谈代码',
  `thirdparty` tinyint(1) DEFAULT '0' COMMENT '第三方注册类型：0=普通，1=微信，2=QQ',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '是否被锁定冻结',
  `admin_id` int(10) DEFAULT '0' COMMENT '关联管理员ID',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '伪删除，1=是，0=否',
  `real_status` tinyint(1) DEFAULT '0' COMMENT '0未实名，1=实名',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `is_recharge` int(2) NOT NULL DEFAULT '0' COMMENT '0=未充值，1=充值过',
  `expire_level_time` datetime DEFAULT NULL COMMENT 'vip会员到期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COMMENT='[会员]会员信息表';

-- -----------------------------
-- Table structure for `#@__member_adv`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_adv`;
CREATE TABLE `#@__member_adv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `ad_type` tinyint(1) DEFAULT '0' COMMENT '广告类型0=图片，1=文本，2=html',
  `name` varchar(60) DEFAULT '' COMMENT '广告名称',
  `links` varchar(255) DEFAULT '' COMMENT '广告链接',
  `mappic` varchar(255) DEFAULT '' COMMENT '示意图',
  `litpic` varchar(255) DEFAULT '' COMMENT '图片地址',
  `wappic` varchar(255) DEFAULT '' COMMENT '手机图片地址',
  `body` varchar(1024) DEFAULT '' COMMENT '到期后显示内容',
  `width` varchar(32) DEFAULT '' COMMENT '宽度',
  `height` varchar(32) DEFAULT '' COMMENT '高度',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `click` int(11) DEFAULT '1' COMMENT '点击量',
  `view` int(11) DEFAULT '1' COMMENT '展示量',
  `bgcolor` varchar(30) DEFAULT '' COMMENT '背景颜色',
  `visible` tinyint(1) unsigned DEFAULT '1' COMMENT '1=显示，0=屏蔽',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `target` varchar(50) DEFAULT '' COMMENT '是否开启浏览器新窗口',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`visible`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='[会员]广告位';

-- -----------------------------
-- Records of `lqf_member_adv`
-- -----------------------------
INSERT INTO `#@__member_adv` VALUES ('17', '0', '幻灯片广告', '', '', '20210127/cb0f8ae82cbc50177de0343fdff49092.jpg', '', '', '600', '300', '5.00', '1', '1', '', '1', '0', '', '0', '0');
INSERT INTO `#@__member_adv` VALUES ('18', '0', 'Banner广告', '', '', '20210127/cb0f8ae82cbc50177de0343fdff49092.jpg', '', '', '750', '90', '3.00', '1', '1', '', '1', '0', '', '0', '0');

-- -----------------------------
-- Table structure for `#@__member_adv_dis`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_adv_dis`;
CREATE TABLE `#@__member_adv_dis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `member_id` int(10) DEFAULT '0' COMMENT '会员idID',
  `adv_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '广告位置ID',
  `title` varchar(60) DEFAULT '' COMMENT '广告名称',
  `litpic` varchar(255) DEFAULT '' COMMENT '图片地址',
  `wappic` varchar(255) DEFAULT '' COMMENT '手机图片地址',
  `start_date` date DEFAULT NULL COMMENT '投放时间',
  `stop_date` date DEFAULT NULL COMMENT '结束时间',
  `links` varchar(255) DEFAULT '' COMMENT '到期广告链接',
  `body` varchar(1024) DEFAULT '' COMMENT '到期广告描述',
  `linkman` varchar(60) DEFAULT '' COMMENT '添加人',
  `email` varchar(60) DEFAULT '' COMMENT '添加人邮箱',
  `phone` varchar(60) DEFAULT '' COMMENT '添加人联系电话',
  `click` int(11) DEFAULT '1' COMMENT '点击量',
  `view` int(11) DEFAULT '1' COMMENT '展示量',
  `period` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买周期',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联订单',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '广告金额',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0=待付款，1=待审核，2=待上线，3=展示中，4=已经到期，5=拒绝中',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `target` varchar(50) DEFAULT '0' COMMENT '是否开启浏览器新窗口',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `position_id` (`adv_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='[会员]广告列表';

-- -----------------------------
-- Table structure for `#@__member_company`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_company`;
CREATE TABLE `#@__member_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员ID',
  `member_id` int(10) DEFAULT '0' COMMENT '会员表ID',
  `category_id` int(10) DEFAULT '0' COMMENT '公司分类',
  `province_id` int(10) DEFAULT '0' COMMENT '省id',
  `city_id` int(10) DEFAULT '0' COMMENT '市',
  `county_id` int(10) DEFAULT '0' COMMENT '区',
  `click` int(10) DEFAULT '1' COMMENT '点击数',
  `name` varchar(50) DEFAULT '' COMMENT '公司名称',
  `linkman` varchar(50) DEFAULT '' COMMENT '联系人',
  `tel` varchar(50) DEFAULT '' COMMENT '电话',
  `weixin` varchar(50) DEFAULT '',
  `qicq` varchar(50) DEFAULT '',
  `address` varchar(128) DEFAULT '' COMMENT '联系地址',
  `litpic` varchar(128) DEFAULT '' COMMENT '公司logo',
  `intro` text COMMENT '公司介绍',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态，0=未审核，1=审核',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='[会员]会员公司列表';

-- -----------------------------
-- Table structure for `#@__member_config`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_config`;
CREATE TABLE `#@__member_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员功能配置表ID',
  `name` varchar(50) DEFAULT '' COMMENT '配置的key键名',
  `value` text COMMENT '配置的value值',
  `desc` varchar(100) DEFAULT '' COMMENT '键名说明',
  `inc_type` varchar(64) DEFAULT '' COMMENT '配置分组',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='[会员]会员功能配置表';

-- -----------------------------
-- Records of `lqf_member_config`
-- -----------------------------
INSERT INTO `#@__member_config` VALUES ('1', 'member_reg', '30', '初始注册激活', 'member', '1563498415', '0');
INSERT INTO `#@__member_config` VALUES ('2', 'member_login', '1', '24小时登录一次', 'member', '1563498414', '0');
INSERT INTO `#@__member_config` VALUES ('3', 'info_pass', '2', '信息发布审核通过', 'info', '1547890773', '0');
INSERT INTO `#@__member_config` VALUES ('4', 'info_reject', '-2', '信息发布审核拒绝后', 'info', '1564555772', '0');
INSERT INTO `#@__member_config` VALUES ('5', 'member_realname', '20', '实名认证通过后', 'member', '1564555773', '0');
INSERT INTO `#@__member_config` VALUES ('6', 'info_refresh', '-1', '刷新信息扣出积分', 'info', '1588948593', '0');
INSERT INTO `#@__member_config` VALUES ('7', 'info_askfor_view', '-2', '查看报名信息', 'infoaskfor', '1588948593', '0');
INSERT INTO `#@__member_config` VALUES ('8', 'info_askfor_find', '-5', '找学员', 'infoaskfor', '1588948593', '0');

-- -----------------------------
-- Table structure for `#@__member_integral`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_integral`;
CREATE TABLE `#@__member_integral` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '明细表ID',
  `member_id` int(10) DEFAULT '0' COMMENT '会员表ID',
  `integral` decimal(10,0) DEFAULT '0' COMMENT '积分',
  `member_integral` decimal(10,0) DEFAULT '0' COMMENT '此条记录的账户积分',
  `cause` text COMMENT '事由，暂时在升级消费中使用到，以serialize序列化后存入，用于后续查询。',
  `cause_type` varchar(50) DEFAULT '' COMMENT '数据类型',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COMMENT='[会员]会员积分明细表';

-- -----------------------------
-- Table structure for `#@__member_level`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_level`;
CREATE TABLE `#@__member_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `level_name` varchar(30) DEFAULT '' COMMENT '级别名称',
  `level_value` int(10) DEFAULT '0' COMMENT '会员等级值',
  `is_system` tinyint(1) DEFAULT '0' COMMENT '类型，1=系统，0=用户',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '消费额度',
  `down_count` int(10) DEFAULT '0' COMMENT '每天下载次数限制',
  `upload_img` int(10) DEFAULT '0' COMMENT '上传图片数量',
  `discount` float(10,2) DEFAULT '100.00' COMMENT '折扣率，初始值为100即100%，无折扣',
  `posts_count` int(10) DEFAULT '5' COMMENT '会员投稿次数限制',
  `ask_is_release` tinyint(1) DEFAULT '1' COMMENT '允许在问答中发布问题，1=是，0=否',
  `ask_is_review` tinyint(1) DEFAULT '0' COMMENT '在问答中发布问题或回答是否需要审核，1=是，0=否',
  `remark` varchar(256) DEFAULT 'cn' COMMENT '备注说明',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='[会员]会员级别表';

-- -----------------------------
-- Records of `lqf_member_level`
-- -----------------------------
INSERT INTO `#@__member_level` VALUES ('1', '注册会员', '10', '1', '0.00', '100', '100', '100', '5', '1', '0', 'cn', '0', '1609900542');
INSERT INTO `#@__member_level` VALUES ('2', '白银会员', '51', '0', '0.00', '100', '300', '100', '10', '1', '0', 'cn', '1564532901', '1618536831');
INSERT INTO `#@__member_level` VALUES ('3', '黄金会员', '100', '0', '0.00', '100', '500', '100', '20', '1', '0', 'cn', '1564532901', '1611725089');
INSERT INTO `#@__member_level` VALUES ('4', '钻石会员', '500', '0', '0.00', '0', '1000', '60', '5', '1', '0', '比较好的', '1609853985', '0');

-- -----------------------------
-- Table structure for `#@__member_list`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_list`;
CREATE TABLE `#@__member_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `para_id` int(10) NOT NULL DEFAULT '0' COMMENT '属性ID',
  `info` text COMMENT '属性值',
  `lang` varchar(50) NOT NULL DEFAULT 'cn' COMMENT '语言标识',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='[会员]会员属性表(信息）';

-- -----------------------------
-- Table structure for `#@__member_menu`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_menu`;
CREATE TABLE `#@__member_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(30) DEFAULT '' COMMENT '导航名称',
  `mca` varchar(50) DEFAULT '' COMMENT '分组/控制器/操作名',
  `is_userpage` tinyint(1) DEFAULT '0' COMMENT '默认会员首页',
  `sort_order` int(10) DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，1=显示，0=隐藏',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='[会员]会员菜单表';

-- -----------------------------
-- Records of `lqf_member_menu`
-- -----------------------------
INSERT INTO `#@__member_menu` VALUES ('1', '个人信息', 'user/Users/index', '1', '100', '1', '1555904190', '1555917737');
INSERT INTO `#@__member_menu` VALUES ('2', '账户充值', 'user/Pay/pay_consumer_details', '0', '100', '1', '1555904190', '1563498414');
INSERT INTO `#@__member_menu` VALUES ('3', '商城中心', 'user/Shop/shop_centre', '0', '100', '1', '1555904190', '1563498415');
INSERT INTO `#@__member_menu` VALUES ('4', '会员升级', 'user/Level/level_centre', '0', '100', '1', '1555904190', '1564555772');
INSERT INTO `#@__member_menu` VALUES ('5', '会员投稿', 'user/UsersRelease/release_centre', '0', '100', '1', '1555904190', '1564555773');
INSERT INTO `#@__member_menu` VALUES ('6', '我的下载', 'user/Download/index', '0', '100', '1', '1590484667', '1609817872');

-- -----------------------------
-- Table structure for `#@__member_money`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_money`;
CREATE TABLE `#@__member_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '金额明细表ID',
  `member_id` int(10) DEFAULT '0' COMMENT '会员表ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `member_money` decimal(10,2) DEFAULT '0.00' COMMENT '此条记录的账户金额',
  `cause` text COMMENT '事由，暂时在升级消费中使用到，以serialize序列化后存入，用于后续查询。',
  `cause_type` tinyint(1) DEFAULT '0' COMMENT '数据类型，0为消费，1为充值。其余后续添加。',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否成功，默认1，0失败，1未付款，2已付款，3已完成，4订单取消。',
  `pay_method` varchar(10) DEFAULT '' COMMENT '支付方式，wechat为微信支付，alipay为支付宝支付',
  `wechat_pay_type` varchar(20) NOT NULL DEFAULT '' COMMENT '微信支付时，标记使用的支付类型（扫码支付，微信内部，微信H5页面）',
  `pay_details` text COMMENT '支付时返回的数据，以serialize序列化后存入，用于后续查询。',
  `order_number` varchar(30) DEFAULT '' COMMENT '订单号',
  `lang` varchar(50) DEFAULT 'cn' COMMENT '语言标识',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[会员]会员金额明细表';


-- -----------------------------
-- Table structure for `#@__member_order`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_order`;
CREATE TABLE `#@__member_order` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT '关键id',
  `name` varchar(256) NOT NULL DEFAULT '' COMMENT '订单名称',
  `order_code` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `payment_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态：0未付款(已下单)，1已付款(待发货)',
  `payment_method` tinyint(1) DEFAULT '0' COMMENT '订单支付方式，0为在线支付，1为线下付款，默认0',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `pay_name` varchar(20) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_details` mediumtext COMMENT '支付时返回的数据，以serialize序列化后存入，用于后续查询。',
  `wechat_pay_type` varchar(20) NOT NULL DEFAULT '' COMMENT '微信支付时，标记使用的支付类型（扫码支付，微信内部，微信H5页面）',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付款金额',
  `bus_type` varchar(50) DEFAULT NULL COMMENT '订单类型：level升级订单、integral、info',
  `bus_id` tinyint(1) unsigned DEFAULT '0' COMMENT '订单关联产品编号',
  `admin_remark` mediumtext COMMENT '管理员操作备注',
  `remark` mediumtext COMMENT '订单备注',
  `member_remark` mediumtext COMMENT '会员备注',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `pay_transaction_no` varchar(256) NOT NULL DEFAULT '0' COMMENT '支付渠道单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='[会员]订单主表';

-- -----------------------------
-- Table structure for `#@__member_parameter`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_parameter`;
CREATE TABLE `#@__member_parameter` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `dtype` varchar(32) NOT NULL DEFAULT '' COMMENT '字段类型',
  `dfvalue` varchar(1000) NOT NULL DEFAULT '' COMMENT '默认值',
  `is_system` tinyint(1) DEFAULT '0' COMMENT '是否为系统属性，系统属性不可删除，1为是，0为否，默认0。',
  `is_hidden` tinyint(1) DEFAULT '0' COMMENT '是否禁用属性，1为是，0为否',
  `is_required` tinyint(1) DEFAULT '0' COMMENT '是否为必填属性，1为是，0为否，默认0。',
  `is_reg` tinyint(1) DEFAULT '1' COMMENT '是否为注册表单，1为是，0为否',
  `sort_order` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `lang` varchar(50) NOT NULL DEFAULT 'cn' COMMENT '语言标识',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='[会员]会员属性表(字段)';


-- -----------------------------
-- Table structure for `#@__member_picture`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_picture`;
CREATE TABLE `#@__member_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `reply_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '回复备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0待审，1=审核，2=拒绝',
  `issave` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否保存',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='[会员]会员图片管理表';

-- -----------------------------
-- Table structure for `#@__member_product_integral`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_product_integral`;
CREATE TABLE `#@__member_product_integral` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(30) DEFAULT '' COMMENT '类型名称',
  `integral` int(10) DEFAULT '0' COMMENT '积分值',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(30) DEFAULT '',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='[会员]会员产品积表';

-- -----------------------------
-- Records of `lqf_member_product_integral`
-- -----------------------------
INSERT INTO `#@__member_product_integral` VALUES ('3', '10积分', '10', '10.00', '100', '', '1609902409', '1610367870');
INSERT INTO `#@__member_product_integral` VALUES ('4', '110积分(赠送10分)', '110', '100.00', '110', '', '1609903516', '0');
INSERT INTO `#@__member_product_integral` VALUES ('5', '200积分(赠送40分)', '240', '200.00', '240', '', '1609903516', '0');

-- -----------------------------
-- Table structure for `#@__member_product_level`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_product_level`;
CREATE TABLE `#@__member_product_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(30) DEFAULT '' COMMENT '类型名称',
  `level_id` int(10) DEFAULT '0' COMMENT '会员等级ID',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `period` int(10) DEFAULT '0' COMMENT '会员周期值，默认单位为月',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(30) DEFAULT '',
  `create_time` int(11) DEFAULT '0' COMMENT '新增时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='[会员]会员产品升级';

-- -----------------------------
-- Records of `lqf_member_product_level`
-- -----------------------------
INSERT INTO `#@__member_product_level` VALUES ('1', '升级为本站白银会员', '2', '150.00', '12', '88', '', '1564532901', '1611725131');
INSERT INTO `#@__member_product_level` VALUES ('2', '升级为本站黄金会员', '3', '200.00', '12', '100', '', '1564532901', '1611725148');
INSERT INTO `#@__member_product_level` VALUES ('3', '升级为本站钻石会员', '4', '100.00', '12', '100', '', '1609897320', '1611725292');

-- -----------------------------
-- Table structure for `#@__member_realname`
-- -----------------------------
DROP TABLE IF EXISTS `#@__member_realname`;
CREATE TABLE `#@__member_realname` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=未审核，1=审核,2=拒绝',
  `name` varchar(256) NOT NULL DEFAULT '' COMMENT '真实名称',
  `real_type` varchar(256) NOT NULL DEFAULT '1' COMMENT '实名类型、1=个人，2=公司',
  `cert_type` varchar(256) NOT NULL DEFAULT '' COMMENT '证件类型、1=身份证、2=营业执照',
  `cert_code` varchar(256) NOT NULL DEFAULT '' COMMENT '证件号',
  `cert_pic` varchar(256) NOT NULL DEFAULT '' COMMENT '证件图片列',
  `cert_pic1` varchar(256) NOT NULL DEFAULT '' COMMENT '证件图片',
  `cert_pic2` varchar(256) NOT NULL DEFAULT '' COMMENT '证件图片2',
  `member_id` varchar(256) NOT NULL DEFAULT '' COMMENT '会员编号',
  `reply_remark` varchar(256) NOT NULL DEFAULT '' COMMENT '回复备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `org_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='[会员]会员实名认证';
