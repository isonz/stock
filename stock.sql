/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : stock

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-12-23 00:32:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sk_datas
-- ----------------------------
DROP TABLE IF EXISTS `sk_datas`;
CREATE TABLE `sk_datas` (
  `days` int(11) unsigned NOT NULL,
  `ticker` char(8) NOT NULL,
  `last_price` float(8,2) unsigned DEFAULT '0.00' COMMENT '最新价',
  `quota` float(6,2) DEFAULT '0.00' COMMENT '涨跌额',
  `range` float(6,3) DEFAULT '0.000' COMMENT '涨跌幅',
  `buyin` float(8,2) unsigned DEFAULT '0.00' COMMENT '买入',
  `sellout` float(8,2) unsigned DEFAULT '0.00' COMMENT '卖出',
  `zuoin` float(8,2) unsigned DEFAULT '0.00' COMMENT '昨收',
  `jinkai` float(8,2) unsigned DEFAULT '0.00' COMMENT '今开',
  `highest` float(8,2) unsigned DEFAULT '0.00' COMMENT '最高',
  `lowest` float(8,2) unsigned DEFAULT '0.00' COMMENT '最低',
  `volume` int(11) unsigned DEFAULT '0' COMMENT '成交量',
  `turnover` float(12,2) unsigned DEFAULT '0.00' COMMENT '成交额',
  PRIMARY KEY (`days`),
  KEY `datas_ticker` (`ticker`),
  KEY `range` (`range`),
  CONSTRAINT `datas_ticker` FOREIGN KEY (`ticker`) REFERENCES `sk_stock` (`ticker`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_datas
-- ----------------------------

-- ----------------------------
-- Table structure for sk_setting
-- ----------------------------
DROP TABLE IF EXISTS `sk_setting`;
CREATE TABLE `sk_setting` (
  `name` varchar(20) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_setting
-- ----------------------------
INSERT INTO `sk_setting` VALUES ('SINA_ENCODE', 'GBK');
INSERT INTO `sk_setting` VALUES ('SINA_STOCK_COUNT_URL', 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeStockCount?node=hs_a');
INSERT INTO `sk_setting` VALUES ('SINA_STOCK_PAGE_URL', 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=80&sort=symbol&asc=1&node=hs_a&symbol=&_s_r_a=page');
INSERT INTO `sk_setting` VALUES ('SINA_STOCK_RUN', '{\"SINA_STOCK_COUNT\":2557,\"SINA_STOCK_COUNT_DATE\":1419206400}');

-- ----------------------------
-- Table structure for sk_stock
-- ----------------------------
DROP TABLE IF EXISTS `sk_stock`;
CREATE TABLE `sk_stock` (
  `ticker` char(8) NOT NULL,
  `name` varchar(15) NOT NULL,
  `update_at` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`ticker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_stock
-- ----------------------------
