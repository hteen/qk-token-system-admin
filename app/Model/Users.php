<?php namespace App\Model;

use App\Model\Base;
use Illuminate\Support\Str;

/**
 * App\Model\Users
 */
class Users extends Base
{
    protected $table = 'users';

    protected $field_search = [
        'id' => 'eq',
        'username' => 'like',
        'status'=>'eq',
        'reg_ip'=>'like',
        'code_invite' => 'eq',
    ];
    
    public static $status_label = [0=>'不限',1 => '正常', 2 => '禁用' ];
    
    
    /**
     * 创建昵称
     */
    public static function createNickname()
    {
        while (true) {
            $nickname = '用户_' .Str::random(8);

            if (self::where('nickname', $nickname)->count() == 0) {
                return $nickname;
            }
        }
    }
    /**
     * 创建邀请码
     */
    public static function createCodeInvite()
    {
        while (true) {
            $code_invite = Str::random(6);

            if (self::where('code_invite', $code_invite)->count() == 0) {
                return $code_invite;
            }
        }
    }
}
