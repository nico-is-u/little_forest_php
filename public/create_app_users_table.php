<?php
// 创建小程序用户表
require __DIR__ . '/../vendor/autoload.php';

$app = new think\App();
$app->initialize();

$db = \think\facade\Db::connect();

$sql = "
CREATE TABLE IF NOT EXISTS `fun_app_users` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `openid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信OpenID',
  `unionid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信UnionID',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `gender` tinyint(1) DEFAULT 0 COMMENT '性别 0:未知 1:男 2:女',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '城市',
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '省份',
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '国家',
  `language` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '语言',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态 1:正常 0:禁用',
  `last_login_time` int DEFAULT NULL COMMENT '最后登录时间',
  `create_time` int NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int DEFAULT NULL COMMENT '更新时间',
  `delete_time` int DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `mobile` (`mobile`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='小程序用户表';
";

try {
    $db->execute($sql);
    echo "成功创建 fun_app_users 表\n";
} catch (\Exception $e) {
    echo "创建表失败: " . $e->getMessage() . "\n";
}
