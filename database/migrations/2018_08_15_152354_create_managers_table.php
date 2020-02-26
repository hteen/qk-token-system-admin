<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `cn_name` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '真实姓名',
  `remember_token` char(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_ip` char(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `login_times` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否禁用  1：否  2：是',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `token` char(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '防止多点登陆',
  `power` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_time` datetime DEFAULT NULL COMMENT '最近登陆时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uname` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员'";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
