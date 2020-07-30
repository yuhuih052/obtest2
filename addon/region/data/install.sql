INSERT INTO `ob_hook` (`name`, `describe`, `addon_list`, `status`, `update_time`, `create_time`) VALUES ('RegionSelect', '区域选择', 'Region', '1', '0', '0');

INSERT INTO `ob_addon` (`name`, `title`, `describe`, `config`,  `author`, `version`, `status`, `create_time` , `update_time`)  VALUES ('Region', '区域选择', '区域选择插件', '', 'Bigotry', '1.0', '1', '0', '0');


-- ----------------------------
-- Table structure for `ob_region`
-- ----------------------------
DROP TABLE IF EXISTS `ob_region`;
CREATE TABLE `ob_region` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '地区名称',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '深度',
  `upid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=910007 DEFAULT CHARSET=utf8 COMMENT='省市县数据表';

-- ----------------------------
-- Records of ob_region
-- ----------------------------
INSERT INTO `ob_region` VALUES ('110000', '北京市', '1', '0', '1');
INSERT INTO `ob_region` VALUES ('120000', '天津市', '1', '0', '1');
INSERT INTO `ob_region` VALUES ('130000', '河北省', '1', '0', '1');
INSERT INTO `ob_region` VALUES ('140000', '山西省', '1', '0', '1');
INSERT INTO `ob_region` VALUES ('150000', '内蒙古', '1', '0', '1');