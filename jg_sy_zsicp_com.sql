/*
 Navicat Premium Data Transfer

 Source Server         : 120.79.52.222
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 120.79.52.222:3306
 Source Schema         : sy_zsicp_com

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 24/06/2021 15:26:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_permission
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE `auth_permission`  (
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色',
  `permission_rule_id` int(11) NOT NULL DEFAULT 0 COMMENT '权限id',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限授权表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for auth_permission_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission_rule`;
CREATE TABLE `auth_permission_rule`  (
  `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则编号',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `name` char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：为1正常，为0禁用',
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `listorder` int(10) NOT NULL DEFAULT 0 COMMENT '排序，优先级，越小优先级越高',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for auth_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_admin`;
CREATE TABLE `auth_role_admin`  (
  `role_id` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '角色 id',
  `admin_id` int(11) NULL DEFAULT 0 COMMENT '管理员id'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色对应表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for business
-- ----------------------------
DROP TABLE IF EXISTS `business`;
CREATE TABLE `business`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家id',
  `business_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家名称',
  `responsible_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家负责人姓名',
  `responsible_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家负责人联系方式',
  `business_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家地址',
  `business_appraisal_id` int(11) NULL DEFAULT NULL COMMENT '商家企业证书id',
  `business_images` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家图片',
  `img_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家是否有图集',
  `business_introduction` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商户介绍',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '软删除判断',
  `create_time` datetime NULL DEFAULT NULL COMMENT '商家创建时间',
  `verify_if` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '审核状态1-通过 2-已拒绝 3-已提交(待审核)',
  `grant_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '授权操作员二维码',
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家状态1-正常 2-冻结',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for business_appraisal
-- ----------------------------
DROP TABLE IF EXISTS `business_appraisal`;
CREATE TABLE `business_appraisal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '证书id',
  `appraisal_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '证书图片',
  `appraisal_type` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '证书类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for business_img
-- ----------------------------
DROP TABLE IF EXISTS `business_img`;
CREATE TABLE `business_img`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家图集id',
  `business_id` int(11) NULL DEFAULT NULL COMMENT '商家id',
  `business_image_injson` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家图集图片',
  `business_img_contentjson` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家图片文案',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for business_notice
-- ----------------------------
DROP TABLE IF EXISTS `business_notice`;
CREATE TABLE `business_notice`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家须知id',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家须知内容',
  `onoff` tinyint(1) NULL DEFAULT NULL COMMENT '是否启用0-启用 1-禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for business_update_log
-- ----------------------------
DROP TABLE IF EXISTS `business_update_log`;
CREATE TABLE `business_update_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `responsible_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家负责人姓名',
  `responsible_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家负责人联系方式',
  `business_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家地址',
  `appraisal_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '证书图片',
  `business_id` int(11) NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for calendar_copy
-- ----------------------------
DROP TABLE IF EXISTS `calendar_copy`;
CREATE TABLE `calendar_copy`  (
  `datelist` date NOT NULL,
  PRIMARY KEY (`datelist`) USING BTREE,
  INDEX `datelist`(`datelist`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for common_problem
-- ----------------------------
DROP TABLE IF EXISTS `common_problem`;
CREATE TABLE `common_problem`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '常见问题id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '解决方案',
  `onoff` tinyint(1) NULL DEFAULT 0 COMMENT '是否启用0-启用 1-禁用 ',
  `orderid` int(11) NULL DEFAULT NULL COMMENT '自定义排序ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for file_resource
-- ----------------------------
DROP TABLE IF EXISTS `file_resource`;
CREATE TABLE `file_resource`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源id',
  `tag_id` int(11) NOT NULL DEFAULT 0 COMMENT '资源分组id',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '资源的类型（0：图片）',
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的原名',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的路径（不加 域名的地址）',
  `size` int(11) NOT NULL DEFAULT 0 COMMENT '大小',
  `ext` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的文件后缀',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '资源表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for file_resource_tag
-- ----------------------------
DROP TABLE IF EXISTS `file_resource_tag`;
CREATE TABLE `file_resource_tag`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源分组的id',
  `tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源分组的tag',
  `create_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '资源的分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for limit
-- ----------------------------
DROP TABLE IF EXISTS `limit`;
CREATE TABLE `limit`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `limit_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限名称',
  `limit_model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '模块名',
  `limit_controller` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '控制器名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `menu_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `menu_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生产原地',
  `menu_weight` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品规格',
  `production_time` datetime NULL DEFAULT NULL COMMENT '生产日期',
  `quality_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '保质日期',
  `menu_money` decimal(11, 2) NULL DEFAULT NULL COMMENT '商品售价',
  `menu_images_json` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品轮播图',
  `if_delete` int(11) NULL DEFAULT 0 COMMENT '软删除',
  `business_id` int(11) NULL DEFAULT NULL COMMENT '商品所属商家id',
  `update_user_id` int(11) NULL DEFAULT NULL COMMENT '商品编辑用户id',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `menu_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品购买链接',
  `recommend` tinyint(1) NULL DEFAULT 1 COMMENT '推荐商品-0-否 ，1-推荐',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu_certificate
-- ----------------------------
DROP TABLE IF EXISTS `menu_certificate`;
CREATE TABLE `menu_certificate`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '证书名称',
  `certificate_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '证书图片',
  `certificate_menu_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '对应商品名称',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '对应商品id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu_classify
-- ----------------------------
DROP TABLE IF EXISTS `menu_classify`;
CREATE TABLE `menu_classify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `classify_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `classify_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类缩略图',
  `classify_show` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '是否显示此分类',
  `createTime` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `deleteTime` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu_monitor
-- ----------------------------
DROP TABLE IF EXISTS `menu_monitor`;
CREATE TABLE `menu_monitor`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品检测报告id',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `monitor_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品检测报告图片',
  `sample_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '样品名称',
  `monitoring_time` datetime NULL DEFAULT NULL COMMENT '检测时间',
  `test_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '检测地点',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '批次id(批次表)',
  `business_id` int(11) NULL DEFAULT NULL COMMENT '商家id',
  `order_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '批次号',
  `source_injson` json NULL,
  `create_time` datetime NULL DEFAULT NULL COMMENT '记录创建时间',
  `user_id` int(11) NULL DEFAULT NULL COMMENT '操作员ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for recommend_menu
-- ----------------------------
DROP TABLE IF EXISTS `recommend_menu`;
CREATE TABLE `recommend_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '推荐id(商品推荐表)',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `recommend_money` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '推荐商品的金额',
  `recommend_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '推荐类型名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色名称',
  `role_juisdiction` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色权限',
  `pid` int(11) NULL DEFAULT NULL COMMENT '父角色id',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `listorder` int(255) NULL DEFAULT NULL COMMENT '优先级，越小越高',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '0禁用 1正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role_limit
-- ----------------------------
DROP TABLE IF EXISTS `role_limit`;
CREATE TABLE `role_limit`  (
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色id',
  `limit_id` int(11) NULL DEFAULT NULL COMMENT '对应权限id'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source
-- ----------------------------
DROP TABLE IF EXISTS `source`;
CREATE TABLE `source`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `monitoring_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单检测信息',
  `order_id` int(11) NULL DEFAULT NULL COMMENT '批次id',
  `business_id` int(11) NULL DEFAULT NULL COMMENT '商家id',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `source_number` int(11) NULL DEFAULT 0 COMMENT '溯源查询次数',
  `order_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '批次编号',
  `menu_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `menu_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生产源地',
  `menu_weight` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品规格',
  `production_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生产日期',
  `quality_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '保质日期',
  `menu_images_json` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品轮播图',
  `monitor_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品检测报告图片',
  `test_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '检测地点',
  `sample_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '样品名称',
  `monitoring_time` datetime NULL DEFAULT NULL COMMENT '检测时间',
  `source_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '溯源二维码',
  `source_code_number` int(11) NULL DEFAULT NULL COMMENT '溯源码数量/批',
  `order_key_number` int(11) UNSIGNED ZEROFILL NULL DEFAULT 00000000000 COMMENT '订单内批次扫描次数',
  `certificate_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '证书图片',
  `storage_time` datetime NULL DEFAULT NULL COMMENT '入库时间',
  `enter_user_id` int(11) NULL DEFAULT NULL COMMENT '入库操作员ID',
  `deliver_time` datetime NULL DEFAULT NULL COMMENT '出库时间',
  `out_user_id` int(11) NULL DEFAULT NULL COMMENT '出库操作员id',
  `scan_time` datetime NULL DEFAULT NULL COMMENT '扫描时间',
  `operator_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `goto_order` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号',
  `goto_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递目标用户',
  `goto_mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递目标用户手机号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 769 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_log
-- ----------------------------
DROP TABLE IF EXISTS `source_log`;
CREATE TABLE `source_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '查询记录表',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `source_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '溯源码',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `track` int(11) NULL DEFAULT NULL COMMENT '查询次数',
  `track_time` datetime NULL DEFAULT NULL COMMENT '(最近一次)查询时间',
  `state` int(255) NULL DEFAULT NULL COMMENT '1-有效 2-无效',
  `create_time` datetime NULL DEFAULT NULL COMMENT '记录创建时间',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `longitude` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '经度',
  `latitude` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '纬度',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '市',
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省',
  `county` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '县|区',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_order
-- ----------------------------
DROP TABLE IF EXISTS `source_order`;
CREATE TABLE `source_order`  (
  `id` int(11) NOT NULL COMMENT '订单表',
  `user_id` int(11) NULL DEFAULT NULL COMMENT '操作员id',
  `order_id` int(11) NOT NULL COMMENT '批次id',
  `source_order_number` int(11) NULL DEFAULT 0 COMMENT '数量',
  `create_time` datetime NULL DEFAULT NULL COMMENT '记录创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '(上次)更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for unit
-- ----------------------------
DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '单位设置表(各种单位规格存储)',
  `unit_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '单位名称',
  `unit_class` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '1、商品规格 2、保质期限规格',
  `business_id` int(11) NOT NULL COMMENT '所属商家',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `business_id`(`business_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名称（微信名称）',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录密码；sp_password加密',
  `gender` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户性别',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户手机号码',
  `user_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户头像',
  `unionid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信unionid',
  `open_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信的用户id',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色id',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '用户删除时间',
  `real_name_state` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户实名状态',
  `business_notice` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商家id',
  `subscribe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '(是否关注公众号)0-未关注 1-已关注 ',
  `create_time` datetime NULL DEFAULT NULL COMMENT '用户创建时间',
  `last_login_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `status` int(11) NULL DEFAULT NULL COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wechat_fans
-- ----------------------------
DROP TABLE IF EXISTS `wechat_fans`;
CREATE TABLE `wechat_fans`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `appid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公众号APPID',
  `unionid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '粉丝unionid',
  `openid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '粉丝openid',
  `tagid_list` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '粉丝标签id',
  `is_black` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否为黑名单状态',
  `subscribe` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '关注状态(0未关注,1已关注)',
  `nickname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `sex` tinyint(1) UNSIGNED NULL DEFAULT NULL COMMENT '用户性别(1男性,2女性,0未知)',
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户所在国家',
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户所在省份',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户所在城市',
  `language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户的语言(zh_CN)',
  `headimgurl` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户头像',
  `subscribe_time` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '关注时间',
  `subscribe_at` datetime NULL DEFAULT NULL COMMENT '关注时间',
  `remark` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `subscribe_scene` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '扫码关注场景',
  `qr_scene` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '二维码场景值',
  `qr_scene_str` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '二维码场景内容',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 531 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- View structure for view_menu
-- ----------------------------
DROP VIEW IF EXISTS `view_menu`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_menu` AS select `menu`.`id` AS `id`,`menu`.`menu_name` AS `menu_name`,`menu`.`menu_address` AS `menu_address`,`menu`.`menu_weight` AS `menu_weight`,`menu`.`production_time` AS `production_time`,`menu`.`quality_time` AS `quality_time`,`menu`.`menu_money` AS `menu_money`,`menu`.`menu_images_json` AS `menu_images_json`,`menu`.`if_delete` AS `if_delete`,`menu`.`business_id` AS `business_id`,`menu`.`update_user_id` AS `update_user_id`,`menu`.`create_time` AS `create_time`,`menu`.`update_time` AS `update_time`,`menu`.`menu_url` AS `menu_url`,`business`.`business_name` AS `business_name`,`menu`.`recommend` AS `recommend` from (`menu` left join `business` on((`menu`.`business_id` = `business`.`id`)));

-- ----------------------------
-- View structure for view_source_log
-- ----------------------------
DROP VIEW IF EXISTS `view_source_log`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_source_log` AS select `source_log`.`id` AS `id`,`source_log`.`user_id` AS `user_id`,`source_log`.`source_code` AS `source_code`,`source_log`.`menu_id` AS `menu_id`,`source_log`.`track` AS `track`,`source_log`.`track_time` AS `track_time`,`source_log`.`state` AS `state`,`source_log`.`create_time` AS `create_time`,`menu`.`menu_name` AS `menu_name`,`menu`.`business_id` AS `business_id`,`source_log`.`ip` AS `ip`,`source_log`.`longitude` AS `longitude`,`source_log`.`latitude` AS `latitude`,`source_log`.`city` AS `city`,`source_log`.`province` AS `province`,`source_log`.`county` AS `county` from (`source_log` left join `menu` on((`source_log`.`menu_id` = `menu`.`id`)));

-- ----------------------------
-- View structure for view_user
-- ----------------------------
DROP VIEW IF EXISTS `view_user`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_user` AS select `user`.`id` AS `id`,`user`.`username` AS `username`,`user`.`password` AS `password`,`user`.`gender` AS `gender`,`user`.`phone` AS `phone`,`user`.`user_image` AS `user_image`,`user`.`open_id` AS `open_id`,`user`.`role_id` AS `role_id`,`user`.`delete_time` AS `delete_time`,`user`.`real_name_state` AS `real_name_state`,`user`.`business_notice` AS `business_notice`,`user`.`subscribe` AS `subscribe`,`user`.`create_time` AS `create_time`,date_format(`user`.`create_time`,'%Y-%m-%d') AS `create_newtime`,`user`.`last_login_ip` AS `last_login_ip`,`user`.`last_login_time` AS `last_login_time`,`user`.`status` AS `status`,`role`.`role_name` AS `role_name`,`business`.`business_name` AS `business_name`,`user`.`unionid` AS `unionid` from ((`user` left join `role` on((`user`.`role_id` = `role`.`id`))) left join `business` on((`user`.`business_notice` = `business`.`id`)));

-- ----------------------------
-- View structure for view_wechat_fans
-- ----------------------------
DROP VIEW IF EXISTS `view_wechat_fans`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_wechat_fans` AS select `wechat_fans`.`id` AS `id`,`wechat_fans`.`appid` AS `appid`,`wechat_fans`.`unionid` AS `unionid`,`wechat_fans`.`openid` AS `openid`,`wechat_fans`.`tagid_list` AS `tagid_list`,`wechat_fans`.`is_black` AS `is_black`,`wechat_fans`.`subscribe` AS `subscribe`,`wechat_fans`.`nickname` AS `nickname`,`wechat_fans`.`sex` AS `sex`,`wechat_fans`.`country` AS `country`,`wechat_fans`.`province` AS `province`,`wechat_fans`.`city` AS `city`,`wechat_fans`.`language` AS `language`,`wechat_fans`.`headimgurl` AS `headimgurl`,`wechat_fans`.`subscribe_time` AS `subscribe_time`,`wechat_fans`.`subscribe_at` AS `subscribe_at`,`wechat_fans`.`remark` AS `remark`,`wechat_fans`.`subscribe_scene` AS `subscribe_scene`,`wechat_fans`.`qr_scene` AS `qr_scene`,`wechat_fans`.`qr_scene_str` AS `qr_scene_str`,`wechat_fans`.`create_at` AS `create_at`,`view_user`.`role_id` AS `role_id`,`view_user`.`role_name` AS `role_name`,if(isnull(`view_user`.`unionid`),0,1) AS `is_system`,`view_user`.`id` AS `user_id` from (`wechat_fans` left join `view_user` on((`wechat_fans`.`unionid` = convert(`view_user`.`unionid` using utf8mb4)))) where isnull(`view_user`.`delete_time`);

SET FOREIGN_KEY_CHECKS = 1;
