/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : stock

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-12-27 20:38:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sk_datas
-- ----------------------------
DROP TABLE IF EXISTS `sk_datas`;
CREATE TABLE `sk_datas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `days` int(11) unsigned NOT NULL,
  `ticker` char(8) NOT NULL,
  `trade` float(8,2) unsigned DEFAULT '0.00' COMMENT '最新价',
  `pricechange` float(6,2) DEFAULT '0.00' COMMENT '涨跌额',
  `changepercent` float(6,3) DEFAULT '0.000' COMMENT '涨跌幅',
  `buy` float(8,2) unsigned DEFAULT '0.00' COMMENT '买入',
  `sell` float(8,2) unsigned DEFAULT '0.00' COMMENT '卖出',
  `settlement` float(8,2) unsigned DEFAULT '0.00' COMMENT '昨收',
  `open` float(8,2) unsigned DEFAULT '0.00' COMMENT '今开',
  `high` float(8,2) unsigned DEFAULT '0.00' COMMENT '最高',
  `low` float(8,2) unsigned DEFAULT '0.00' COMMENT '最低',
  `volume` int(11) unsigned DEFAULT '0' COMMENT '成交量',
  `amount` float(20,2) unsigned DEFAULT '0.00' COMMENT '成交额',
  `per` float(8,3) DEFAULT '0.000',
  `pb` float(8,3) DEFAULT '0.000',
  `mktcap` float(20,6) DEFAULT '0.000000',
  `nmc` float(20,6) DEFAULT '0.000000',
  `turnoverratio` float(10,5) DEFAULT '0.00000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `days_ticker` (`days`,`ticker`),
  KEY `datas_ticker` (`ticker`),
  KEY `changepercent` (`changepercent`) USING BTREE,
  CONSTRAINT `datas_ticker` FOREIGN KEY (`ticker`) REFERENCES `sk_stock` (`ticker`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sk_holder
-- ----------------------------
DROP TABLE IF EXISTS `sk_holder`;
CREATE TABLE `sk_holder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `days` date NOT NULL,
  `ticker` char(8) NOT NULL,
  `holder` varchar(60) DEFAULT '' COMMENT '股东名称',
  `shares` bigint(21) unsigned DEFAULT '0' COMMENT '持股数量',
  `stake` float(6,3) unsigned DEFAULT '0.000' COMMENT '持股比例',
  `nature` varchar(20) DEFAULT '' COMMENT '股本性质',
  `type` varchar(10) DEFAULT 'ltgd' COMMENT '股东类型',
  PRIMARY KEY (`id`),
  UNIQUE KEY `days_ticker_holder` (`days`,`ticker`,`holder`) USING BTREE,
  KEY `holder_ticker` (`ticker`) USING BTREE,
  KEY `stake` (`stake`) USING BTREE,
  KEY `holder` (`holder`) USING BTREE,
  CONSTRAINT `holder_ticker` FOREIGN KEY (`ticker`) REFERENCES `sk_stock` (`ticker`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sk_setting
-- ----------------------------
DROP TABLE IF EXISTS `sk_setting`;
CREATE TABLE `sk_setting` (
  `name` varchar(30) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sk_stock
-- ----------------------------
DROP TABLE IF EXISTS `sk_stock`;
CREATE TABLE `sk_stock` (
  `ticker` char(8) NOT NULL,
  `name` varchar(15) NOT NULL,
  `update_at` date NOT NULL,
  PRIMARY KEY (`ticker`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sk_tmp_data
-- ----------------------------
DROP TABLE IF EXISTS `sk_tmp_data`;
CREATE TABLE `sk_tmp_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `days` date DEFAULT NULL COMMENT '时间',
  `type` varchar(10) DEFAULT 'datas' COMMENT '类型',
  `data` longtext COMMENT '数据内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `days_type` (`days`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
