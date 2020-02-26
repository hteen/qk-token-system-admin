<?php

namespace App\Http\Controllers;

use App\Model\Transactions;
use Illuminate\Http\Request;


class TransactionsController extends Base
{
    /**
     * 申请列表
     * @return mixed
     */
    public function index()
    {
        //申请状态
        $statusLabel = Transactions::$statusLabel;
        $statusLabel[0] = '状态';
        $data['statusLabel'] = $statusLabel;

        // 处理类型
        $data['dealTypeLabel'] = array_merge(['' => '处理类型'], Transactions::$dealTypeLabel);

        return $this->render('Transactions::index', '交易记录列表', $data);
    }

    /**
     * 申请列表
     * @param Request $request
     * @return mixed
     */
    public function indexPage(Request $request)
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;

        $function = function ($query) use ($request) {
            $tx_status = $request->input('tx_status');
            if ($tx_status == 1) {
                $query = $query->where('tx_status', 1);
            } else if ($tx_status == 2) {
                $query = $query->where('tx_status', 0);
            }

            return $query;
        };

        //查询出数据后的处理，不处理直接传null
        $callback = function ($data) {
            $data->deal_type_name = '';
            $data->token_name = '';
        };
        //要查询的字段，查所有字段传['*']
        $fields = ['*'];
        $data = (new Transactions())->getPaginateDataAjax($fields, $function, $callback, $limit, $offset);

        $result = [];
        foreach ($data as $v) {
            $result[] = $v;
        }

        return response()->json(['data' => $result, 'total' => $data->total()]);
    }
}
