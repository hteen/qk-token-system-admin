<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\AdminLogin
 *
 * @property int $id
 * @property string $username
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class AdminLogin extends Base
{
    public $table = 'admin_login';

    public $field_search = [/*'status' => 'eq', */'username' => 'eq'];
}
