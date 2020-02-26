<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Base;
use App\Model\Manage\Manager as Managers;
use App\Model\Manage\ManagerList;
use App\Model\Manage\Nodes;
use App\Model\OperationLog;
use App\Model\QueryLogs;
use App\Service\OperationLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * 管理员管理
 * Created by PhpStorm.
 * Date: 2016/10/8 0008
 * Time: 10:27
 */
class Manager extends Base
{
    protected $logic;
    protected $model;

    public function __construct(\App\Libs\Manager\Manager $logic, \App\Model\Manage\ManagerList $model)
    {
        parent::__construct();
        $this->logic = $logic;
        $this->model = $model;
    }

    /**
     * 管理员列表
     */
    public function index()
    {
        return $this->render(__METHOD__, '管理员列表');
    }

    /**
     * 分页数据
     */
    public function page()
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;
        $callback = function ($data) {
            $data->status_text = ManagerList::$status[$data->status];
            return $data;
        };
        
        $fields = ['id', 'username', 'cn_name', 'status', 'login_times', 'last_time','last_ip'];
        $data = $this->model->getPaginateDataAjax($fields, null, $callback, $limit, $offset);
        $result = [];
        foreach ($data as $v) {
            $result[] = $v;
        }
        return response()->json(['data' => $result, 'total' => $data->total()]);
    }

    /**
     * 操作日志
     */
    public function getLog()
    {
        return $this->render(__METHOD__, '操作日志');
    }

    public function getLogData(Request $request)
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;
        $fields = ['*'];
        $where = function ($query) use ($request) {

            return $query;
        };
        $ol = new OperationLog();
        $data = $ol->getPaginateDataAjax($fields, $where, null, $limit, $offset);
        $result = [];
        foreach ($data as &$v) {
            $v->username = \App\Model\Manage\Manager::where('id', $v->admin_id)->value('username');
            $v->type_name = OperationLogService::$types[$v->type];
            $result[] = $v;
        }
        return response()->json(['data' => $result, 'total' => $data->total()]);
    }

    public function queryLog()
    {
        return $this->render(__METHOD__, '查询日志');
    }

    public function queryData(Request $request)
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;
        $fields = ['*'];
        $where = function ($query) use ($request) {

            return $query;
        };
        $ol = new QueryLogs();
        $data = $ol->getPaginateDataAjax($fields, $where, null, $limit, $offset);
        $result = [];
        foreach ($data as &$v) {
            $v->username = Managers::where('id', $v->uid)->value('username');
            $result[] = $v;
        }
        return response()->json(['data' => $result, 'total' => $data->total()]);
    }

    /**
     * 添加/编辑
     * @param int|null $id
     * @return \Illuminate\Http\Response
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     */
    public function edit(int $id = null)
    {
        $title = '添加';
        
        $this->vars['data'] = null;
        if ($id) {
            $title = '编辑';
            $data = $this->model->find($id);
            $this->vars['data'] = $data;
        }
        return $this->render(__METHOD__, $title . '管理员');
    }

    public function editSubmit(Request $request)
    {
        $rules = [
            'cn_name' => 'required|min:2|max:12|regex:/^[\x{4e00}-\x{9fa5}]+$/u',
        ];
        $data = $request->all();
        $id = (int)$data['id'];

        if (!$id) {
            $rules['password'] = 'required|confirmed|min:6|max:16';
            $rules['username'] = 'required|regex:/^[a-z][a-z0-9_]{3,14}[a-z0-9]$/|unique:managers';
        } else {
            if ($data['password']) {
                $rules['password'] = 'required|confirmed|min:6|max:16';
            }
        }

        $this->validate($request, $rules);

        if ($id) {
            $r = $this->logic->edit($data);
        } else {
            $r = $this->logic->create($data['username'], $data['password'], $data['cn_name']);
        }
        return $r ? $this->ajaxSuccess() : $this->ajaxError($this->logic->getError());
    }

    /**
     * 禁用
     * @param int $id
     * @return JsonResponse
     */
    public function disable(int $id)
    {
        if ($id == 1) {
            return $this->ajaxError('不能这么干啊');
        }
        $data = $this->model->find($id);
        $data->status = 2;
        $data->save();
        return $this->ajaxSuccess();
    }

    /**
     * 启用
     * @param int $id
     * @return JsonResponse
     */
    public function enable(int $id)
    {
        if ($id == 1) {
            return $this->ajaxError('不能这么干啊');
        }
        $data = $this->model->find($id);
        $data->status = 1;
        $data->save();
        return $this->ajaxSuccess();
    }

    /**
     * 删除,注意id1不能删
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id)
    {
        if ($id == 1) {
            return $this->ajaxError('不能这么干啊');
        }
        $this->model->where('id', $id)->delete();
        return $this->ajaxSuccess();
    }

    /**
     * 设置
     */
    public function profile()
    {
        $data = \Auth::user();
        $this->vars['data'] = $data;
        return $this->render(__METHOD__, '修改资料');
    }

    /**
     * 资料更新
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function profileSubmit(Request $request)
    {
        $rules = [
            'cn_name' => 'required|min:2|max:12|regex:/^[\x{4e00}-\x{9fa5}]+$/u',
        ];
        $data = $request->all();
        if (!empty($data['password'])) {
            $rules['password'] = 'confirmed|min:6|max:16';
        }
        $this->validate($request, $rules);
        $data['id'] = \Auth::user()->id;

        return $this->logic->edit($data) ? $this->ajaxSuccess() : $this->ajaxError($this->logic->getError());
    }

    public function power($id)
    {
        $this->vars['id'] = $id;
        return $this->render(__METHOD__, '权限设置');
    }

    public function getPower($id)
    {
        $this->vars['nodes'] = Nodes::get(['name', 'uri', 'level', 'id']);
        $data = ManagerList::find($id);
        $this->vars['data'] = $data;
        $this->vars['powers'] = explode(',', $this->vars['data']->power);
        $tree = [];
        $checked = [];
        foreach ($this->vars['nodes'] as $v){
            if ($v->level==2&&in_array($v->uri, $this->vars['powers'])){
                $checked[] = $v->id;
            }
            if ($v->level==1){
                $tree[] = $v->toArray();
            }
        }
        foreach ($tree as $k=>$x){
            foreach ($this->vars['nodes'] as $v){
                if ($v->level!=1){
                    $uriArr = explode('/', $v->uri);
                    if (count($uriArr)>1 && $uriArr[0]==$x['uri']){
                        $tree[$k]['children'][] = $v->toArray();
                    }
                }
            }
        }
        foreach ($tree as $v){
            if (!isset($v['children']) && in_array($v['uri'], $this->vars['powers'])){
                $checked[] = $v['id'];
            }
        }
        return $this->ajaxSuccess('', ['tree'=>$tree, 'data'=>$data, 'checked'=>$checked]);
    }

    public function powerSubmit(Request $request)
    {

        $powers = $request->input('power', null);
        if (!is_array($powers)) {
            $power = '';
        } else {
            $power = implode(',',$powers);
        }
        $id = $request->input('id');
        $data = ManagerList::find($id);
        $data->power = $power;
        $data->save();
        return $this->ajaxSuccess();
    }
}