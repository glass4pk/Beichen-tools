/*
 Navicat MySQL Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : hey_card

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 27/08/2018 16:59:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for card
-- ----------------------------
DROP TABLE IF EXISTS `card`;
CREATE TABLE `card`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡牌类型名',
  `card_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `create_timestamp` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建时间（时间戳）',
  `status` tinyint(1) NOT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '卡牌图片url',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `card_type`(`type_name`) USING BTREE,
  CONSTRAINT `card_type` FOREIGN KEY (`type_name`) REFERENCES `card_type` (`name`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_comment
-- ----------------------------
DROP TABLE IF EXISTS `card_comment`;
CREATE TABLE `card_comment`  (
  `id` bigint(20) NOT NULL,
  `openid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户openid',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '评论',
  `create_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '评论提交时间',
  `status` tinyint(1) NULL DEFAULT NULL,
  `card_id` bigint(20) NULL DEFAULT NULL,
  `like` int(12) NULL DEFAULT NULL COMMENT '点赞数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `openid`(`openid`) USING BTREE,
  CONSTRAINT `openid` FOREIGN KEY (`openid`) REFERENCES `card_wechat_user` (`openid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_type
-- ----------------------------
DROP TABLE IF EXISTS `card_type`;
CREATE TABLE `card_type`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡牌类型',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type_id` int(5) NULL DEFAULT NULL COMMENT '类型id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `card_type`(`name`) USING BTREE COMMENT 'type_name字段：UNIQUE\r\n作为card表的外键'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for card_wechat_user
-- ----------------------------
DROP TABLE IF EXISTS `card_wechat_user`;
CREATE TABLE `card_wechat_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `openid` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户的标识，对当前公众号唯一',
  `unionid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '全网唯一',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '1正常用户，0为为黑名单用户，',
  `groupid` int(11) NOT NULL COMMENT '用户所在的分组ID',
  `user_type` tinyint(1) NULL DEFAULT NULL,
  `nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户的昵称',
  `remark` varchar(0) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '公众号运营者对粉丝的备注',
  `sex` tinyint(1) NOT NULL COMMENT '值为1时是男性，值为2时是女性，值为0时是未知',
  `country` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `province` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `language` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `headimgurl` varchar(8190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户微信头像url',
  `subscribe` tinyint(1) NOT NULL COMMENT '用户是否订阅.1为订阅，',
  `subscribe_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `subscribe_scene` varbinary(20) NOT NULL COMMENT '返回用户关注的渠道来源',
  `tagid_list` varchar(0) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户被打上的标签ID列表',
  `qr_scene` tinyint(1) NOT NULL COMMENT '二维码扫码场景（开发者自定义）',
  `qr_scene_str` varchar(0) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '二维码扫码场景描述（开发者自定义）',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid`) USING BTREE COMMENT '公众号唯一openid\r\n',
  UNIQUE INDEX `unionid`(`unionid`) USING BTREE COMMENT '微信开放平台unionid'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
