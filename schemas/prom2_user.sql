/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 100125
Source Host           : localhost:3306
Source Database       : prom2

Target Server Type    : MYSQL
Target Server Version : 100125
File Encoding         : 65001

Date: 2017-08-22 15:06:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for prom2_user
-- ----------------------------
DROP TABLE IF EXISTS `prom2_user`;
CREATE TABLE `prom2_user` (
  `cntPromUserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `txtCreatedFromHost` varchar(100) DEFAULT NULL,
  `enuSalutation` enum('Mr','Mrs','Ms','Miss','Dr') DEFAULT NULL COMMENT 'Salutation',
  `txtFirstname` varchar(100) DEFAULT NULL COMMENT 'Firstname',
  `txtLastname` varchar(100) DEFAULT NULL COMMENT 'Lastname',
  `txtPreferredName` varchar(100) DEFAULT NULL,
  `txtEmail` varchar(100) NOT NULL COMMENT 'Email',
  `txtEncryptedPassword` varchar(512) NOT NULL,
  `txtSaltAdded` varchar(100) NOT NULL,
  `datLastLogin` datetime DEFAULT NULL COMMENT 'Last login',
  PRIMARY KEY (`cntPromUserID`),
  UNIQUE KEY `txtEmail` (`txtEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
