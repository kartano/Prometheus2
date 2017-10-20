/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 100126
Source Host           : localhost:3306
Source Database       : prom2

Target Server Type    : MYSQL
Target Server Version : 100126
File Encoding         : 65001

Date: 2017-10-20 15:04:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for prom2_pages
-- ----------------------------
DROP TABLE IF EXISTS `prom2_pages`;
CREATE TABLE `prom2_pages` (
  `cntPageID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txtRelativeURL` varchar(255) NOT NULL COMMENT 'Relative URL',
  `txtTitle` varchar(70) NOT NULL COMMENT 'Title',
  `txtContent` text NOT NULL COMMENT 'Content',
  `blnRobots_DoNotAllow` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`cntPageID`),
  UNIQUE KEY `txtRelativeURL` (`txtRelativeURL`),
  KEY `txtTitle` (`txtTitle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
