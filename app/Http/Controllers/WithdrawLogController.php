<?php

namespace App\Http\Controllers;

use App\Model\Users;
use App\Model\WithdrawLog;
use Illuminate\Http\Request;
use Zhuzhichao\IpLocationZh\Ip;

class WithdrawLogController extends Base
{
    /**
     * 提现列表
     */
    public function index()
    {
        return $this->render('Withdraw::index', '提现列表');
    }

    /**
     *商品列表
     *
     * @param Request $request
     * @return mixed
     */
    public function page(Request $request)
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;

        $where = function ($query) use ($request) {
            $nickname = \Request::input('nickname');
            if ($nickname) {
                $ids = Users::where('nickname', 'like', $nickname . '%')->pluck('id');
                if ($ids) {
                    $query = $query->whereIn('uid', $ids);
                }
            }
            $start_time = \Request::input('start_time');
            if($start_time)
            {
                $query = $query->where('created_at', ">",$start_time);
            }
            $end_time = \Request::input('end_time');
            if($end_time)
            {
                $query = $query->where('created_at', "<",$end_time);
            }
            return $query;
        };

        //要查询的字段，查所有字段传['*']
        $fields = ['*'];
        $data =  (new WithdrawLog())->getPaginateDataAjax($fields, $where, null, $limit, $offset);
        $result = [];
        foreach ($data as $v) {
            if ($v->ip) {
                $addressArray = Ip::find($v->ip);
                //var_dump($addressArray);
                $addressString = '';
                if ($addressArray[0]) {
                    $addressString .= $addressArray[0] . ' ';
                }
                if ($addressArray[1]) {
                    $addressString .= $addressArray[1] . ' ';
                }
                if ($addressArray[2]) {
                    $addressString .= $addressArray[2] . ' ';
                }

                $v->ip_address =  trim($addressString);
            }
            $v->status_name = WithdrawLog::$status_label[$v->status];
            $v->tx_status_name = WithdrawLog::$txStatusLabel[$v->tx_status];
            $result[] = $v;
        }

        return response()->json(['data' => $result, 'total' => $data->total()]);
    }

}
