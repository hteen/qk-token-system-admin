<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\WithdrawLog
 *
 * @property int $id
 * @property int $uid 用户id
 * @property string $assets_type 资产类型
 * @property string|null $address 提现到地址
 * @property float $amount 数量
 * @property int $status 状态 1默认 2成功
 * @property string|null $tx_hash 哈希
 * @property string $ip 操作IP
 * @property string $net_type
 * @property string|null $user_agent 浏览器信息
 * @property string|null $msg 转账错误信息
 * @property int|null $code 转账错误码
 * @property int|null $hour 提现时刻唯一标识
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 * @property float $fee 手续费
 */
class WithdrawLog extends Base
{
    public $table = "withdraw_log";

    const STATUS_DEFAULT = 1;
    const STATUS_SUCCESS = 2;

    protected $field_search = ['id' => 'eq', 'uid' => 'eq', 'address'=>'eq', 'tx_hash'=>'eq', 'status'=>'eq', 'tx_status' => 'eq', 'assets_type' => 'eq'];

    /**
     * 预加载关联模型数据
     * @var array
     */
    public $with = ['user'];

    public function user()
    {
        return $this->belongsTo('App\Model\Users', 'uid');
    }

    /**
     * 订单状态
     * @var array
     */
    public static $status_label = array(
        0 => '全部',
        1 => "默认",
        2 => "成功",
    );

    /**
     * 转账状态
     * @var array
     */
    public static $txStatusLabel = [0 => "全部", 1 => "默认", 2 => "成功", 3 => "失败"];
}
