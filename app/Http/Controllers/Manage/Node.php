<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Base;
use Illuminate\Http\Request;

/**
 * 后台菜单管理
 * Created by PhpStorm.
 * Date: 2016/10/8 0008
 * Time: 10:27
 */
class Node extends Base
{
    /**
     * 后台首页
     */
    public function index()
    {

        return $this->render(__METHOD__, '节点列表');
    }

    public function page()
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;
        $function = function ($model) {
            return $model->where('level', 1);
        };
        $data = app(\App\Model\Manage\Nodes::class)->getPaginateDataAjax(['*'], $function, null, $limit, $offset);
        $result = [];
        foreach ($data as $v) {
            $result[] = $v;
        }
        return response()->json(['data' => $result, 'total' => $data->total()]);
    }

    /**
     * 加载路由数据并写入数据库
     * @param \App\Libs\Node\Nodes $nodes
     * @return \Illuminate\Http\JsonResponse
     */
    public function load(\App\Libs\Node\Nodes $nodes)
    {
        $nodes->load();
        return $this->ajaxSuccess();
    }

    /**
     * 快速更新数据
     * @param int $id
     * @param string $field
     * @param string $value
     * @param \App\Model\Manage\Node $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, string $field, string $value, \App\Model\Manage\Nodes $model)
    {
        $field = trim($field);
        $data = $model->find($id);
        $data->$field = $value;
        $data->save();
        return $this->ajaxSuccess();
    }
}