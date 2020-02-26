<?php namespace App\Model\Manage;

use App\Model\Base;

/**
 * 管理员列表
 * Class ManagerList
 *
 * @package App\Model\Manage
 * @mixin \Eloquent
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $avatar 头像
 * @property string $cn_name 真实姓名
 * @property string $remember_token
 * @property string $last_ip
 * @property int $login_times
 * @property int $status 是否禁用  1：否  2：是
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $token 防止多点登陆
 * @property string $power
 * @property string|null $last_time 最近登陆时间
 */
class ManagerList extends Base
{
    protected $table = 'managers';

    public static $status = [0 => '不限', 1 => '正常', 2 => '已禁用'];

    protected $fillable = ['username', 'cn_name', 'last_ip', 'last_time', 'status', 'password', 'salt', 'store_id'];

    protected $field_search = ['username' => 'like', 'cn_name' => 'like', 'mobile' => 'like', 'last_time' => 'data_range', 'status' => 'eq', 'store_id' => 'eq'];


}
