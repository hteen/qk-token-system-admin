<?php

namespace App\Model;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Assets extends Base
{
 
    protected $table = 'assets';

    public static $type_label = [
        '0' => '全部',
        'cct' => 'CCT',
    ];
    
    
    /**
     * 获取列表
     * @param type $cond  条件
     * @param bool $count 是否返回总条数，传递null将返回Query对象
     */
    public static function getList($cond, $count = false){
        
        $cond = onlys(true, $cond, 'limit', 'offset', 'order_by', 'order_way');
        
        $db = self::select('*');
        
        if(is_null($count)){
            return $db;
        }
        
        if($count){
            return $db->count();
        }
        
        if(!is_null($orderBy = $cond->get('order_by'))){
            $db->orderBy($orderBy, $cond->get('order_way') === 'desc' ? 'desc' : 'asc');
        }
        
        return $db->offset((int) $cond->get('offset', 0))
                  ->limit((int) $cond->get('limit', 20))
                  ->orderBy('id', 'desc')
                  ->get();
    }
    
    
    /**
     * 保存
     * @param type $datas 数据
     */
    public static function saveData($datas, $id = 0, $adminId = 0){
        $id    = (int) $id;
        $data  = onlys($datas, 'net_type', 'decimals', 'contract_address', 'assets_name', 'recharge_status');
        $isAdd = !$id;
        
        $validator = Validator::make($data, array(
            'net_type'         => 'string|min:1|max:16',
            'decimals'         => 'integer|min:0|max:100',
            'contract_address' => 'string|min:1|max:66',
            'assets_name'      => 'string|min:1|max:250',
            'recharge_status'  => 'integer|min:1|max:2',
        ), array(
            'contract_address.string' => '合约地址不能为空',
            'contract_address.max'    => '合约地址不能超过:max个字',
            'assets_name.string'      => '资产名称不能为空',
            'assets_name.max'         => '资产名称不能超过:max个字',
        ));
        
        if($validator->fails()){
            self::$err = $validator->errors()->first();
            return false;
        }
        
        if(self::isEmpty($isAdd, $data, 'contract_address') || self::isEmpty($isAdd, $data, 'assets_name')){
            
            self::$err = '数据错误';
            return false;
        }
        
        array_clear($data);
        
        if($isAdd){
            $res = self::insert($data + ['created_at' => date('Y-m-d H:i:s')]);
        }else{
            $res = $data ? self::where('id', $id)->update($data) : true;
        }
        
        if(empty($res)){
            self::$err = '数据操作失败';
            return false;
        }
        
        return true;
    }
    
    
    /**
     * 删除
     * @param int $id 删除ID
     */
    public static function del($id = 0){
        $id = (int) $id;
        
        if(!$id){
            self::$err = 'ID不能为空';
            return false;
        }
        
        return !!self::where('id', $id)->delete();
    }
    
    
    /**
     * 自适应空值判断
     */
    private static function isEmpty(){
        $args = func_get_args();
        
        if(!array_shift($args)){
            array_unshift($args, true);
        }
        return call_user_func_array('empty_input', $args);
    }
}
