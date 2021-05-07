
-- -----------------------------
-- Table structure for `#@__sys_user`
-- -----------------------------
DROP TABLE IF EXISTS `#@__sys_user`;
CREATE TABLE `#@__sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `realname` varchar(64) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别0=未知，1=男，2=女',
  `dept_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '所在部门',
  `position_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '职位管理',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `qicq` varchar(64) NOT NULL DEFAULT '',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '手机',
  `intro` varchar(256) NOT NULL DEFAULT '' COMMENT '介绍',
  `rules` text NOT NULL COMMENT '权限节点',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `visible` int(11) NOT NULL DEFAULT '1' COMMENT '1=显示、0=隐藏',
  `org_id` int(11) NOT NULL DEFAULT '1' COMMENT '组织结构',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COMMENT='[系统]系统用户表';

-- -----------------------------
-- Records of `#@__sys_user`
-- -----------------------------
INSERT INTO `#@__sys_user` VALUES ('1', 'admin', 'd526e2fef6f800326a900b365a28e448', '开发人生', '0', '1', '0', 'admin@admin', '1871720801', '18030402705', '', '', '1587348600', '1619581371', '1', '1', '1');
INSERT INTO `#@__sys_user` VALUES ('84', 'manage', 'd526e2fef6f800326a900b365a28e448', '匆道', '1', '0', '0', '', '', '', '', '', '1587610606', '1587621461', '0', '1', '1');

-- -----------------------------
-- Table structure for `#@__sys_area`
-- -----------------------------
DROP TABLE IF EXISTS `#@__sys_area`;
CREATE TABLE `#@__sys_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '' COMMENT '地区名称',
  `domain` varchar(128) NOT NULL DEFAULT '' COMMENT '绑定域名',
  `pid` int(11) DEFAULT '0' COMMENT '上级编号',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `visible` smallint(6) DEFAULT '1' COMMENT '1=显示，0=隐藏',
  `manager_user_id` varchar(256) DEFAULT '' COMMENT '管理人员',
  `manager_user_name` varchar(1024) DEFAULT '' COMMENT '管理人员名称',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `org_id` int(11) DEFAULT '1',
  `tel` varchar(50) DEFAULT '' COMMENT '联系电话',
  `linkman` varchar(50) DEFAULT '' COMMENT '联系人',
  `address` varchar(256) DEFAULT '' COMMENT '联系地址',
  `talk` varchar(1024) DEFAULT '' COMMENT '在线沟通',
  `weixin` varchar(1024) DEFAULT '' COMMENT '微信图片地址',
  `mobile` varchar(50) DEFAULT '' COMMENT '手机号码',
  `traffic` varchar(1024) DEFAULT '' COMMENT '交通线路',
  `email` varchar(128) DEFAULT '' COMMENT '电子邮箱',
  `map_x` varchar(64) DEFAULT '' COMMENT '地址X坐标',
  `map_y` varchar(64) DEFAULT '' COMMENT '地址Y坐标',
  `popup` varchar(1024) DEFAULT '' COMMENT '底部沟通',
  `beian` varchar(1024) DEFAULT '' COMMENT '底部备案',
  `fullname` varchar(1024) DEFAULT '' COMMENT '公司全称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='[系统]地区表';

-- -----------------------------
-- Records of `#@__sys_area`
-- -----------------------------
INSERT INTO `#@__sys_area` VALUES ('1', '成都', 'http://www.07fly.com', '0', '1', '1', '1,84,1', '开发人生,匆道,开发人生', '1597672045', '1608727787', '1', '028-61833149', '李先生', '四川省成都市量力钢材城4-3-3', '', '', '028-61833149', '地铁4号线', 'goodmuzi@qq.com', '104.072642', '30.674467', '', '', '成都零起飞科技有限公司');

-- -----------------------------
-- Table structure for `#@__sys_area_user`
-- -----------------------------
DROP TABLE IF EXISTS `#@__sys_area_user`;
CREATE TABLE `#@__sys_area_user` (
  `sys_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `sys_area_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '地区id',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `org_id` int(10) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[系统]地区授权表';

-- -----------------------------
-- Records of `#@__sys_area_user`
-- -----------------------------
INSERT INTO `#@__sys_area_user` VALUES ('1', '1', '0', '1608727787', '1');
INSERT INTO `#@__sys_area_user` VALUES ('84', '1', '0', '1608727787', '1');
INSERT INTO `#@__sys_area_user` VALUES ('1', '1', '0', '1608727787', '1');
