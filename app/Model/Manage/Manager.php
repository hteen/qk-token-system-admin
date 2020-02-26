<?php namespace App\Model\Manage;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Model\Manage\Manager
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereCnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereLastIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereLastTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereLoginTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Manage\Manager whereUsername($value)
 */
class Manager extends Authenticatable
{
    use Notifiable;

    protected $table = 'managers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
