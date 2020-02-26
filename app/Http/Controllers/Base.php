<?php namespace App\Http\Controllers;

use App\Libs\Manager\Manager;

/**
 * 后台控制器公用
 * Created by PhpStorm.
 * Date: 2016/10/8 0008
 * Time: 10:27
 */
class Base extends Controller

{
    protected $logic;
    protected $model;
    protected $auto_order = true;

    /**
     * 视图数据
     * @var array
     */
    protected $vars = [];

    /**
     * 验证方法
     * @var array
     */
    protected $validate_rules = [];

    public function __construct()
    {
        view()->share('site_name', config('app.name'));
        if (method_exists($this, 'boot')) {
            $this->boot();
        }

        $this->middleware(function ($request, $next) {
            $menus = app(\App\Libs\Node\Nodes::class, [new \App\Model\Manage\Nodes()]) ->getTree(null, true);//print_r($menus);exit;
            foreach ($menus as $k => &$menu) {
                if (!app(Manager::class)->checkPower($menu['uri'])) {
                    unset($menus[$k]);
                }else{
                    if (isset($menu['children'])){
                        foreach ($menu['children'] as $k2=>$m){
                            if (!app(Manager::class)->checkPower($m['uri'])) {
                                unset($menu['children'][$k2]);
                            }
                        }
                        if (empty($menu['children'])){
                            unset($menus[$k]);
                        }
                    }
                }
            }

            view()->share('system_menus', $menus);
            return $next($request);
        });

        view()->share('navigation', app(\App\Libs\Node\Nodes::class, [new \App\Model\Manage\Nodes()])->getNavigation());

    }

    /**
     * 返回视图
     * @param string $method
     * @param string $page_name
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    protected function render(string $method, string $page_name, array $data = [], int $status = 200, array $headers = []): \Illuminate\Http\Response
    {
        $prefix = 'App\Http\Controllers';
        $view = str_replace($prefix, '', $method);
        $view = str_replace('Controller', '', $view);
        $view = str_replace('\\', '.', $view);
        $view = str_replace('::', '.', $view);
        $view = trim($view, '.');
        view()->share('page_name', $page_name);
        $this->getBasePath($method);
        return response()->view($view, $data ? $data : $this->vars, $status, $headers);
    }

    /**
     * 获取前台使用的uri
     * @param string $method
     */
    protected function getBasePath(string $method)
    {
        $prefix = 'App\Http\Controllers';
        $path = str_replace($prefix, '', $method);
        $path = str_replace('Controller', '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('::', '/', $path);
        $path = explode('/', trim($path, '/'));
        foreach ($path as $k => $v) {
            $path[$k] = trim(snake_case($v, '-'), '-');
        }
        unset($path[count($path) - 1]);
        $path = '/' . implode('/', $path);
        view()->share('base_path', $path);
    }

    /**
     * ajax返回结果
     * @param array $data
     * @param string $redirect_url
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ajaxSuccess(string $redirect_url = '', array $data = []): \Illuminate\Http\JsonResponse
    {
        $result = [];
        $result['data'] = $data;
        $result['code'] = 200;
        $result['redirect_url'] = $redirect_url;
        return response()->json($result);
    }

    /**
     * 出现错误
     * @param $messages
     * @param int $code
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ajaxError($messages, $code = 500, array $data=[])
    {
        $result = [];
        $result['message'] = $messages;
        $result['code'] = $code;
        $result['data'] = $data;
        return response()->json($result);
    }

    /**
     * 获取分页数据
     * @param $model
     * @param \Closure|null $function 查询条件、排序等
     * @param \Closure|null $callback 数据回调
     * @param int $per_page 每页条数
     */
    protected function getPages($model, \Closure $function = null, \Closure $callback = null, int $per_page = 20, bool $auto_order = true)
    {
        $per_page = \Request::input('per_page') ? (int)\Request::input('per_page') : $per_page;
        view()->share('per_page', $per_page);
        $data = $model->getPaginateData($function, $callback, $per_page, $auto_order);
        $this->vars['data'] = $data['data'];
        $this->vars['pages'] = $data['pages'];
    }

    protected function insertSelectOption(array $data, array $toData)
    {
        foreach ($toData as $k => $value) {
            $data[$k] = $value;
        }

        return $data;
    }

    /**
     * 获取排序方式
     * @param $model
     * @return mixed
     */
    protected function getOrder($model)
    {
        $order_by = \Request::input('order_by');
        $order_way = \Request::input('order_way');

        if (!$this->auto_order) {
            return $model;
        } else {
            if (!$order_by) {
                $order_by = 'id';
            }
            if (!$order_way) {
                $order_way = 'desc';
            }
        }

        return $model->orderBy($order_by, $order_way);
    }
}