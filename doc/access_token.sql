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

 Date: 28/08/2018 16:19:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_token
-- ----------------------------
DROP TABLE IF EXISTS `access_token`;
CREATE TABLE `access_token`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `access_token` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '是否是测试：1不是测试，2为测试',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of access_token
-- ----------------------------
INSERT INTO `access_token` VALUES (2, 'wxa46386d571e05bac', '13_Ee3VuImUFWCKxZjN1AdlDXzxjsz9dNX-7leFex446dlTZSQ_zqUcR_zc2rjB90g_vd1AjElveWNZb1H0m8o_NqQDYkGegk-xeCgNEqxmJyeuAB03e7twCdgtgU5zQdHu80OqpUxClSyFifnPUCCcABADLL', '2018-08-28 15:46:59', 1);

SET FOREIGN_KEY_CHECKS = 1;
