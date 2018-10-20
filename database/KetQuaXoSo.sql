/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100136
 Source Host           : localhost:3306
 Source Schema         : sms_content

 Target Server Type    : MySQL
 Target Server Version : 100136
 File Encoding         : 65001

 Date: 20/10/2018 16:35:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for KetQuaXoSo
-- ----------------------------
DROP TABLE IF EXISTS `KetQuaXoSo`;
CREATE TABLE `KetQuaXoSo` (
  `date` varchar(45) NOT NULL,
  `service` varchar(45) NOT NULL,
  `mt` text,
  `timeCreated` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0: Chưa kiểm duyệt, 1: Đã kiểm duyệt',
  PRIMARY KEY (`date`,`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
