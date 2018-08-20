/*
 Navicat MySQL Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : cpmapping

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 20/08/2018 17:56:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_token
-- ----------------------------

-- ----------------------------
-- Table structure for font
-- ----------------------------
DROP TABLE IF EXISTS `font`;
CREATE TABLE `font`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `font_fullname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `filepath` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '状态：0为禁用，1为启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_admin
-- ----------------------------
DROP TABLE IF EXISTS `gp_admin`;
CREATE TABLE `gp_admin`  (
  `id` bigint(20) NOT NULL,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `last_login_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '上一次登录时间',
  `create_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_item
-- ----------------------------
DROP TABLE IF EXISTS `gp_item`;
CREATE TABLE `gp_item`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` datetime(0) NOT NULL,
  `create_timestamp` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '状态：0为禁用，1为启用  默认为0',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_project
-- ----------------------------
DROP TABLE IF EXISTS `gp_project`;
CREATE TABLE `gp_project`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `item_id` int(20) NOT NULL COMMENT '对应gp_item表的id',
  `credential_id` int(20) NULL DEFAULT NULL,
  `pic` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '证书id',
  `coordinate_x` float(20, 0) NULL DEFAULT NULL COMMENT '0为居中对齐，其他数字表示真实坐标',
  `coordinate_y` float(20, 0) NOT NULL,
  `font_color` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `font_size` float NOT NULL,
  `font` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字体的id',
  `textkerning` float(2, 0) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '启用状态：0为禁用，1为启用 默认为1',
  `font_fullname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_result
-- ----------------------------
DROP TABLE IF EXISTS `gp_result`;
CREATE TABLE `gp_result`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `result_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_user
-- ----------------------------
DROP TABLE IF EXISTS `gp_user`;
CREATE TABLE `gp_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `credential_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户对应projec的tid',
  `status` tinyint(255) NULL DEFAULT NULL COMMENT '用户状态：0为禁用,1为启用',
  `result_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


SET FOREIGN_KEY_CHECKS = 1;
