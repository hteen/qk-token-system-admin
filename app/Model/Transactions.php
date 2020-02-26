<?php

namespace App\Model;

use App\Service\CctService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Transactions
 *
 * @property int $id
 * @property string|null $from 转出地址
 * @property string|null $to 转入地址
 * @property string|null $hash 转账hash
 * @property string|null $block_hash 区块hash
 * @property int $block_number 区块高度
 * @property float $gas_price 矿工费
 * @property float $amount 数量
 * @property int $status 状态，1默认，2已处理
 * @property int $tx_status 交易状态，1成功，0失败
 * @property string $assets_type 资产类型 如果是token，说明是通证，需要通证类型id
 * @property int|null $token_id 通证类型id
 * @property string|null $deal_type 处理类型  +充值recharge  -提现withdraw  -退回refund,处理完毕后再补全
 * @property int|null $data_id 处理对应的数据id，充值为assets_logs数据id、提现为withdraw_id、退回为refund_id
 * @property string|null $remark 备注
 * @property int|null $admin_id 如果是管理员操作，则填写此字段
 * @property string|null $payee 合约地址(通证)
 * @property float|null $token_tx_amount 通证交易数量
 * @property int $uid 用户id
 */
class Transactions extends Base
{
    protected $table = "transactions";

    protected $field_search = [
        'uid' => 'eq',
        'status' => 'eq',
        'from' => 'like',
        'to' => 'like',
        'hash' => 'like',
        'block_hash' => 'like',
        'block_number' => 'eq',
        'assets_type' => 'eq',
        'payee' => 'eq'
    ];

    public $with = ['user'];

    /**
     * 状态
     * @var array
     */
    public static $statusLabel = [
        0 => '不限',
        1 => '未处理',
        2 => '已处理'
    ];

    /**
     * 处理类型
     */
    public static $dealTypeLabel = [
        'recharge' => '充值',
        'withdraw' => '提现',
        'refund' => '退回'
    ];

    /***
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'uid');
    }

}
