<?php
/**
 * 用户验证信息验证自定义
 * Date: 2016/10/24 0024
 * Time: 9:43
 */

namespace App\Libs\Manager;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class DiyUserProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'] . $user->salt;
        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}