/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 100125
Source Host           : localhost:3306
Source Database       : prom2

Target Server Type    : MYSQL
Target Server Version : 100125
File Encoding         : 65001

Date: 2017-08-22 07:29:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for prom2_log
-- ----------------------------
DROP TABLE IF EXISTS `prom2_log`;
CREATE TABLE `prom2_log` (
  `cntLogID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datTimestamp` datetime NOT NULL,
  `txtCallStack` varchar(512) DEFAULT NULL,
  `txtMessage` varchar(255) DEFAULT NULL,
  `lngCode` int(11) DEFAULT NULL,
  `lngLoggedInUserID` int(10) unsigned DEFAULT NULL COMMENT 'User who was logged in when error occurred',
  PRIMARY KEY (`cntLogID`),
  KEY `lngLoggedInUserID` (`lngLoggedInUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
