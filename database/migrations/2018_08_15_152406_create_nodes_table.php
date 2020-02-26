<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '父节点uri',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '节点名',
  `weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `style` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '样式',
  `hide` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否隐藏菜单，1：不隐藏 2：隐藏',
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '链接或路由uri',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '层级',
  `method` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '请求类型',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='后台节点'";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
