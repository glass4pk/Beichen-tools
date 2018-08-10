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

 Date: 10/08/2018 16:57:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_token
-- ----------------------------
DROP TABLE IF EXISTS `access_token`;
CREATE TABLE `access_token`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` datetime(0) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '是否是测试：1不是测试，2为测试',
  `appid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for auth_operator_record
-- ----------------------------
DROP TABLE IF EXISTS `auth_operator_record`;
CREATE TABLE `auth_operator_record`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `operator` bigint(20) NULL DEFAULT NULL COMMENT '操作者的id',
  `operate_time` int(10) NULL DEFAULT NULL COMMENT '操作时间',
  `operate_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务名称',
  `status` bigint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auth_user
-- ----------------------------
DROP TABLE IF EXISTS `auth_user`;
CREATE TABLE `auth_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户登录名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户登录密码',
  `qq` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系qq',
  `mail` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系邮箱',
  `phone` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注说明',
  `login_num` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '登录次数',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '状态(0:禁用,1:启用)',
  `id_deleted` tinyint(1) UNSIGNED NULL DEFAULT NULL COMMENT '删除状态(0:未删, 1:删除)',
  `create_by` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '创建者',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `last_login_time` datetime(0) NULL DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_data_list
-- ----------------------------
DROP TABLE IF EXISTS `cp_data_list`;
CREATE TABLE `cp_data_list`  (
  `data_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '数据集的唯一id',
  `data_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '数据集的名称',
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '状态，1为启用，0为停用',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`data_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 309 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 161 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 161 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_2_hobby
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_2_hobby`;
CREATE TABLE `cp_user_2_hobby`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NULL DEFAULT NULL,
  `hobby` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cp_user_2_plan
-- ----------------------------
DROP TABLE IF EXISTS `cp_user_2_plan`;
CREATE TABLE `cp_user_2_plan`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NULL DEFAULT NULL,
  `plan` varchar(2024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 310 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 161 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jsapi_ticket
-- ----------------------------
DROP TABLE IF EXISTS `jsapi_ticket`;
CREATE TABLE `jsapi_ticket`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jsapi_ticket` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'jsapi_ticket',
  `type` tinyint(1) NOT NULL COMMENT '是否是测试：1为成功，2为测试',
  `create_time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ps_item
-- ----------------------------
DROP TABLE IF EXISTS `ps_item`;
CREATE TABLE `ps_item`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '项目id',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '项目名称',
  `create_timestamp` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '项目创建时间戳',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '项目创建时间',
  `create_user_id` bigint(20) UNSIGNED NOT NULL COMMENT '创建者id',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '项目描述',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '项目状态，1为启用，0为禁用',
  `background` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '背景图片',
  `cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_item_element
-- ----------------------------
DROP TABLE IF EXISTS `ps_item_element`;
CREATE TABLE `ps_item_element`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) UNSIGNED NOT NULL COMMENT '项目id',
  `element_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '元素类型',
  `element_type` int(2) UNSIGNED NOT NULL COMMENT '元素类型：1为图片，2为单行文本，3为多行文本',
  `element_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '元素内容，存储文字内容，如果元素类型为图片则存储图片的路径',
  `height` double NULL DEFAULT NULL COMMENT '元素高度，单位像素',
  `width` double NULL DEFAULT NULL COMMENT '元素宽度，单位像素',
  `coordinate_x` double NOT NULL COMMENT '第四象限坐标规范，X轴，单位像素',
  `coordinate_y` double NOT NULL COMMENT '第四象限坐标规范，Y轴，单位像素',
  `font_family` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT ' 字体类型',
  `font_size` double NULL DEFAULT NULL COMMENT '字体大小',
  `font_color` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字体颜色',
  `word_maxnum` int(5) NULL DEFAULT NULL COMMENT '字数最大限制',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_item_modify_record
-- ----------------------------
DROP TABLE IF EXISTS `ps_item_modify_record`;
CREATE TABLE `ps_item_modify_record`  (
  `id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL COMMENT '项目id',
  `operation_type` int(10) NOT NULL COMMENT '操作类型',
  `operation_user_id` bigint(20) NOT NULL COMMENT '操作者id',
  `operation_time` int(10) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_user_item
-- ----------------------------
DROP TABLE IF EXISTS `ps_user_item`;
CREATE TABLE `ps_user_item`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户当前使用项目id',
  `user_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户id',
  `input_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户输入内容。根据元素id识别用户输入的内容，如果为图片则存储图片的路径',
  `element_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户输入内容的元素id，用于识别元素',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_user_share
-- ----------------------------
DROP TABLE IF EXISTS `ps_user_share`;
CREATE TABLE `ps_user_share`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户user_id',
  `item_id` int(11) NOT NULL COMMENT '用户参与的项目id',
  `self_share_num` int(10) NULL DEFAULT NULL COMMENT '用户自己分享次数',
  `other_share_num` int(10) NULL DEFAULT NULL COMMENT '被其他用户分享次数',
  `clicks` int(15) NULL DEFAULT NULL COMMENT '点击次数',
  `share_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户分享链接',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_weixinuser
-- ----------------------------
DROP TABLE IF EXISTS `ps_weixinuser`;
CREATE TABLE `ps_weixinuser`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户的标识，对当前公众号唯一',
  `unionid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。',
  `subscribe` tinyint(1) NOT NULL COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。',
  `groupid` int(11) NULL DEFAULT NULL COMMENT '用户所在的分组ID（兼容旧的用户分组接口',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户的昵称',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `sex` tinyint(255) NULL DEFAULT NULL COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `coutry` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户所在国家',
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户所在省份',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户所在城市',
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户的语言，简体中文为zh_CN',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。',
  `subscribe_time` int(11) NULL DEFAULT NULL COMMENT '	用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `subscribe_scene` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '返回用户关注的渠道来源，ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENEPROFILE LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_OTHERS 其他',
  `qr_scene` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码扫码场景（开发者自定义）',
  `qr_scene_str` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码扫码场景描述（开发者自定义）',
  `taglist` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户被打上的标签ID列表',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '1为启用，0为禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
