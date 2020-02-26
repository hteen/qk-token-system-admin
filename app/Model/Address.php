<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FreezeLog
 *
 * @package App\Model
 * @property int $id
 * @property int $uid 用户id
 * @property $address 地址
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $remark 备注
 */
class Address extends Base
{
    protected $table = "address";
    public $field_search = [/*'status' => 'eq', */'id' => 'eq', 'uid' => 'eq', 'address' => 'eq'];
    public $with = ['user'];

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'uid');
    }
    
    
    /**
     * 筛选出一组地址中已经被绑定的地址
     * @param array $addre 待筛选地址
     */
    public static function getBinds($addre){
        if(!is_array($addre)){
            return array();
        }
        
        $binded = self::whereIn('address', $addre)
                      ->pluck('address');
        
        return array_map('strtolower', $binded->toArray());
    }
}
