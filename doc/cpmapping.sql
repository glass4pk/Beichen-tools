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

 Date: 26/07/2018 00:34:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cp_partner_attention
-- ----------------------------
DROP TABLE IF EXISTS `cp_partner_attention`;
CREATE TABLE `cp_partner_attention`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `attention` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14707 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_result_user
-- ----------------------------
DROP TABLE IF EXISTS `cp_result_user`;
CREATE TABLE `cp_result_user`  (
  `id` bigint(22) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户的姓名',
  `sex` tinyint(1) NULL DEFAULT NULL COMMENT '用户的性别,值为1时是男性,值为2时是女性,值为0时是未知',
  `data_id` bigint(20) NOT NULL COMMENT '数据集的id,',
  `task_id` bigint(20) NOT NULL COMMENT '匹配任务id,与cp_task中task_id对应',
  `term` int(10) NULL DEFAULT NULL COMMENT '参加的学期,纯数字',
  `identity` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户身份',
  `match_sex` tinyint(1) NULL DEFAULT NULL COMMENT '匹配的性别，值为1时是男性,值为2时是女性,值为0时是全部',
  `match_random` tinyint(1) NULL DEFAULT NULL COMMENT '是否随机匹配，值为0时是不随机，值为1时是随机',
  `is_map` tinyint(1) NULL DEFAULT NULL COMMENT '是否匹配完，0没有匹配，1匹配了',
  `partner_id` bigint(20) NULL DEFAULT NULL COMMENT '伙伴id',
  `province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省份（根据ip）',
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市（根据ip）',
  `channel` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `submit_time` datetime(0) NULL DEFAULT NULL COMMENT '提交时间',
  `my_remarks` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '我的备注',
  `reason_come_here` varchar(2024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '参见的原因',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `submit_timestamp` int(10) NULL DEFAULT NULL COMMENT '提交时间时间戳',
  `score` int(10) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '1启用，0不启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8893 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_task_list
-- ----------------------------
DROP TABLE IF EXISTS `cp_task_list`;
CREATE TABLE `cp_task_list`  (
  `task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_name` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务名称',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '记录创建时间',
  `operator_user` bigint(20) NULL DEFAULT NULL COMMENT '操作用户',
  `data_id` bigint(20) NULL DEFAULT NULL COMMENT '要匹配的数据集id',
  PRIMARY KEY (`task_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user
-- ----------------------------
DROP TABLE IF EXISTS `cp_user`;
CREATE TABLE `cp_user`  (
  `userid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户的姓名',
  `sex` tinyint(1) NULL DEFAULT NULL COMMENT '用户的性别,值为1时是男性,值为2时是女性,值为0时是未知',
  `data_id` bigint(20) NOT NULL COMMENT '数据集的id,',
  `term` int(10) NULL DEFAULT NULL COMMENT '参加的学期,纯数字',
  `identity` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户身份',
  `match_sex` tinyint(1) NULL DEFAULT NULL COMMENT '匹配的性别，值为1时是男性,值为2时是女性,值为0时是全部',
  `match_random` tinyint(1) NULL DEFAULT NULL COMMENT '是否随机匹配，值为0时是不随机，值为1时是随机',
  `province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省份（根据ip）',
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市（根据ip）',
  `channel` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `submit_time` datetime(0) NULL DEFAULT NULL COMMENT '提交时间',
  `my_remarks` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '我的备注',
  `reason_come_here` varchar(2024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '参见的原因',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `submit_timestamp` int(10) NULL DEFAULT NULL COMMENT '提交时间时间戳',
  `score` int(10) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '1启用，0不启用',
  PRIMARY KEY (`userid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9242 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_2
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_2`;
CREATE TABLE `cp_user_2`  (
  `userid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户的姓名',
  `sex` tinyint(1) NULL DEFAULT NULL COMMENT '用户的性别,值为1时是男性,值为2时是女性,值为0时是未知',
  `term` int(10) NULL DEFAULT NULL COMMENT '参加的学期,纯数字',
  `identity` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户身份',
  `match_sex` tinyint(1) NULL DEFAULT NULL COMMENT '匹配的性别，值为1时是男性,值为2时是女性,值为0时是全部',
  `match_random` tinyint(1) NULL DEFAULT NULL COMMENT '是否随机匹配，值为0时是不随机，值为1时是随机',
  `is_map` tinyint(1) NULL DEFAULT NULL COMMENT '是否匹配完，0没有匹配，1匹配了',
  `partner_id` bigint(20) NULL DEFAULT NULL COMMENT '伙伴id',
  `province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省份（根据ip）',
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市（根据ip）',
  `channel` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `submit_time` datetime(0) NULL DEFAULT NULL COMMENT '提交时间',
  `my_remarks` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '我的备注',
  `reason_come_here` varchar(2024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '参见的原因',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `submit_timestamp` int(10) NULL DEFAULT NULL COMMENT '提交时间时间戳',
  `score` int(10) NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`userid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10186 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_2_hobby
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_2_hobby`;
CREATE TABLE `cp_user_2_hobby`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NULL DEFAULT NULL,
  `hobby` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3871 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_2_plan
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_2_plan`;
CREATE TABLE `cp_user_2_plan`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NULL DEFAULT NULL,
  `plan` varchar(2024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2195 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_attention
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_attention`;
CREATE TABLE `cp_user_attention`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `attention` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14853 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_promote_section
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_promote_section`;
CREATE TABLE `cp_user_promote_section`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `promote_section` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14023 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for data_list
-- ----------------------------
DROP TABLE IF EXISTS `data_list`;
CREATE TABLE `data_list`  (
  `data_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '数据集的唯一id',
  `data_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '数据集的名称',
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '状态，1为启用，0为停用',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`data_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for operator_record
-- ----------------------------
DROP TABLE IF EXISTS `operator_record`;
CREATE TABLE `operator_record`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `operator` bigint(20) NULL DEFAULT NULL COMMENT '操作者的id',
  `operate_time` int(10) NULL DEFAULT NULL COMMENT '操作时间',
  `operate_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务名称',
  `status` bigint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for we
-- ----------------------------
DROP TABLE IF EXISTS `we`;
CREATE TABLE `we`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
