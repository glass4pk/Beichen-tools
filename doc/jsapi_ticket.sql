/*
 Navicat MySQL Data Transfer

 Source Server         : dev.yes-go.cn
 Source Server Type    : MySQL
 Source Server Version : 50556
 Source Host           : dev.yes-go.cn:3306
 Source Schema         : cpmapping

 Target Server Type    : MySQL
 Target Server Version : 50556
 File Encoding         : 65001

 Date: 28/08/2018 16:19:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for jsapi_ticket
-- ----------------------------
DROP TABLE IF EXISTS `jsapi_ticket`;
CREATE TABLE `jsapi_ticket`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jsapi_ticket` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'jsapi_ticket',
  `type` tinyint(1) NOT NULL COMMENT '是否是测试：1为非测试，2为测试',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of jsapi_ticket
-- ----------------------------
INSERT INTO `jsapi_ticket` VALUES (1, 'kgt8ON7yVITDhtdwci0qeXG07Gj5GoGRyf1pj88_Y1CaBBrZ57V5PRZUGnEuW-QUKubWbEe4u2jrt9L5GhOP5g', 1, '2018-08-28 16:04:43');

SET FOREIGN_KEY_CHECKS = 1;
