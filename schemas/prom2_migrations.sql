/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 100125
Source Host           : localhost:3306
Source Database       : prom2

Target Server Type    : MYSQL
Target Server Version : 100125
File Encoding         : 65001

Date: 2017-08-22 07:29:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for prom2_migrations
-- ----------------------------
DROP TABLE IF EXISTS `prom2_migrations`;
CREATE TABLE `prom2_migrations` (
  `cntID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datExecute` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `txtFilename` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name of migration script run.',
  PRIMARY KEY (`cntID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prom2_migrations
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
