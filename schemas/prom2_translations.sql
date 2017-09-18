/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 100125
Source Host           : localhost:3306
Source Database       : prom2

Target Server Type    : MYSQL
Target Server Version : 100125
File Encoding         : 65001

Date: 2017-09-18 15:57:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for prom2_translations
-- ----------------------------
DROP TABLE IF EXISTS `prom2_translations`;
CREATE TABLE `prom2_translations` (
  `cntTranslationID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txtSourceString` varchar(255) NOT NULL,
  `txtReplacementString` varchar(255) NOT NULL,
  PRIMARY KEY (`cntTranslationID`),
  UNIQUE KEY `source_unique_key` (`txtSourceString`,`txtReplacementString`),
  KEY `source_lookup_key` (`txtSourceString`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
