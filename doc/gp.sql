/*
 Navicat MySQL Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : gp

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 27/08/2018 14:04:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for font
-- ----------------------------
DROP TABLE IF EXISTS `font`;
CREATE TABLE `font`  (
  `font_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `font_fullname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `font_filepath` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `font_status` tinyint(1) NOT NULL COMMENT '状态：0为禁用，1为启用',
  PRIMARY KEY (`font_id`) USING BTREE,
  UNIQUE INDEX `fontfilepath`(`font_filepath`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_auth_user
-- ----------------------------
DROP TABLE IF EXISTS `gp_auth_user`;
CREATE TABLE `gp_auth_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `last_login_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上一次登录时间',
  `create_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_item
-- ----------------------------
DROP TABLE IF EXISTS `gp_item`;
CREATE TABLE `gp_item`  (
  `gp_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gp_item_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` datetime(0) NOT NULL,
  `create_timestamp` int(10) NOT NULL,
  `gp_item_status` tinyint(1) NOT NULL COMMENT '状态：0为禁用，1为启用  默认为0',
  `gp_item_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `extend_url` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享链接',
  `data_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户数据上传文件的名称',
  `data_upload_time` datetime(0) NULL DEFAULT NULL COMMENT '用户数据上传时间',
  `share_title` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享链接title',
  `share_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享链接内容',
  `share_pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享链接图片',
  PRIMARY KEY (`gp_item_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_project
-- ----------------------------
DROP TABLE IF EXISTS `gp_project`;
CREATE TABLE `gp_project`  (
  `gp_project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gp_project_name` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `gp_item_id` int(20) NOT NULL COMMENT '对应gp_item表的id',
  `credential_id` int(20) NULL DEFAULT NULL COMMENT '证书id',
  `pic` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '证书图片',
  `coordinate_x` float(20, 0) NULL DEFAULT NULL COMMENT '0为居中对齐，其他数字表示真实坐标',
  `coordinate_y` float(20, 0) NOT NULL,
  `font_color` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `font_size` float NOT NULL,
  `textkerning` float(2, 0) NOT NULL,
  `gp_project_status` tinyint(1) NOT NULL COMMENT '启用状态：0为禁用，1为启用 默认为1',
  `font_filepath` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字体filepath，对应font表的filepath',
  PRIMARY KEY (`gp_project_id`) USING BTREE,
  INDEX `font_file_path`(`font_filepath`) USING BTREE,
  CONSTRAINT `font_file_path` FOREIGN KEY (`font_filepath`) REFERENCES `font` (`font_filepath`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_result
-- ----------------------------
DROP TABLE IF EXISTS `gp_result`;
CREATE TABLE `gp_result`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `result_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gp_user
-- ----------------------------
DROP TABLE IF EXISTS `gp_user`;
CREATE TABLE `gp_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gp_item_id` bigint(20) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `credential_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户对应projec的tid',
  `status` tinyint(255) NULL DEFAULT NULL COMMENT '用户状态：0为禁用,1为启用',
  `result_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 98 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
