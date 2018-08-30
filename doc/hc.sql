/*
 Navicat MySQL Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : hc

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 30/08/2018 13:18:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for card
-- ----------------------------
DROP TABLE IF EXISTS `card`;
CREATE TABLE `card`  (
  `c_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '唯一id',
  `t_id` bigint(20) UNSIGNED NOT NULL COMMENT '卡牌类型id',
  `card_id` int(10) NOT NULL COMMENT '每一卡牌类型下的卡牌编号',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '卡牌名称',
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '卡牌图片url',
  `status` tinyint(1) NOT NULL COMMENT '卡牌状态',
  `last_change_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最后编辑时间',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `create_timestamp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建时间（时间戳）',
  PRIMARY KEY (`c_id`) USING BTREE,
  UNIQUE INDEX `c_id`(`c_id`) USING BTREE,
  UNIQUE INDEX `t_id`(`t_id`, `card_id`) USING BTREE,
  CONSTRAINT `card_ibfk_1` FOREIGN KEY (`t_id`) REFERENCES `card_type` (`t_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_auth_user
-- ----------------------------
DROP TABLE IF EXISTS `card_auth_user`;
CREATE TABLE `card_auth_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `create_timestamp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建时间(时间戳)',
  `last_login_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上一次登录时间（时间戳）',
  `group` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_comment
-- ----------------------------
DROP TABLE IF EXISTS `card_comment`;
CREATE TABLE `card_comment`  (
  `cc_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '卡牌评论的唯一id',
  `c_id` bigint(20) UNSIGNED NOT NULL COMMENT 'c_id为cards表的主键，不是card_id',
  `openid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户openid',
  `comment` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评论内容',
  `create_time` datetime(0) NOT NULL COMMENT '评论提交时间',
  `create_timestamp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态：0为禁用，1为启用',
  `like` int(12) NULL DEFAULT NULL COMMENT '点赞数',
  `last_change_timestamp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最近修改时间',
  PRIMARY KEY (`cc_id`) USING BTREE,
  INDEX `openid`(`openid`) USING BTREE,
  INDEX `cc_id`(`cc_id`) USING BTREE,
  INDEX `card_comment_ibfk_1`(`c_id`) USING BTREE,
  CONSTRAINT `openid` FOREIGN KEY (`openid`) REFERENCES `wechat_user` (`openid`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `card_comment_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `card` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_type
-- ----------------------------
DROP TABLE IF EXISTS `card_type`;
CREATE TABLE `card_type`  (
  `t_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '唯一id',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡牌类型',
  `type_id` int(10) NULL DEFAULT NULL COMMENT '类型id，手动填写',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片链接',
  PRIMARY KEY (`t_id`) USING BTREE,
  UNIQUE INDEX `t_id`(`t_id`) USING BTREE,
  UNIQUE INDEX `type_id`(`type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
