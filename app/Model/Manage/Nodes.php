<?php namespace App\Model\Manage;

use App\Model\Base;
use Illuminate\Support\Facades\Cache;


/**
 * 菜单
 * Class Node
 *
 * @package App\Model\Manage
 * @mixin \Eloquent
 * @property int $id
 * @property string $parent_uri 父节点uri
 * @property string $name 节点名
 * @property int $weight 排序
 * @property string $style 样式
 * @property int $hide 是否隐藏菜单，1：不隐藏 2：隐藏
 * @property string $uri 链接或路由uri
 * @property int $level 层级
 * @property string $method 请求类型
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Nodes extends Base
{
    protected $table = 'nodes';

    public function parent()
    {
        return $this->hasOne(self::class, 'uri', 'parent_uri');
    }
    
    
    /**
     * 菜单缓存 key
     *
     * @return string
     */
    public static function nodesCacheKey(): string
    {
        return 'nodes_kefu_'.sha1(static::class);;
    }
    
    
    /**
     * 从缓存中获取全部菜单
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function cachedNodes()
    {
        return Cache::remember(static::nodesCacheKey(), 3600, function () {
            return static::query()->get();
        });
    }
}
