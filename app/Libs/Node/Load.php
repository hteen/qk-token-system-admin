<?php namespace App\Libs\Node;

use App\Exceptions\AjaxExceptions;
use App\Model\Manage\Nodes;

/**
 * 从路由获取节点数据并保存
 * Date: 2016/10/10 0010
 * Time: 9:39
 */
class Load
{
    protected $model;

    public function __construct(Nodes $model)
    {
        $this->model = $model;
    }

    /**
     * 开始加载路由
     */
    public function start()
    {
        $routes = $this->getRoutes();

        $this->delete($routes);
        $routes = $this->update($routes);
        if (empty($routes)) {
            return true;
        }
        $this->create($routes);
    }

    /**
     * 确定层级关系
     * @param array $routes
     * @return array
     */
    protected function groupByLevel(array $routes, int $level = 1):array
    {
        $count = 0;
        foreach ($routes as $key => $route) {
            if (!empty($route['level'])) {
                //已经分析，跳过
                continue;
            }
            if ($level == 2) {
                var_dump($route['uri']);
            }
            $route['level'] = $this->getLevel($route['uri']);
            if ($level == $route['level']) {
                $routes[$key]['level'] = $level;
                $count++;
            }
        }
        $level++;
        if ($count > 0) {
            $routes = $this->groupByLevel($routes, $level);
        }
        return $routes;
    }

    /**
     * 获取层级
     * @param string $uri
     * @return int
     */
    protected function getLevel(string $uri):int
    {
        if ($uri == '/') {
            return 1;
        }
        $level = count(explode('/', $uri));
        $level -= substr_count($uri, '{');
        return $level;
    }

    /**
     * 获取所有路由信息
     * @return array
     */
    protected function getRoutes():array
    {
        $routes = [];
        $data = \Route::getRoutes();
        foreach ($data as $v) {
            if ($v->getName()) {
                $tmp = [];
                $tmp['uri'] = $v->uri();
                $tmp['method'] = implode(',', $v->getMethods());
                $tmp['name'] = $v->getName();
                $tmp['type'] = 1;
                $tmp['level'] = $this->getLevel($v->uri());
                $routes[] = $tmp;
            }
        }
        foreach ($routes as $k => $v) {
            $routes[$k]['parent_uri'] = $this->getParentUri($v['uri'], $v['level'], $routes);
        }
        return $routes;
    }

    /**
     * 获取父级URI
     * @param string $uri
     * @param int $level
     * @return string
     */
    protected function getParentUri(string $uri, int $level, array $routes):string
    {
        if ($level == 1) {
            return '';
        }
        $data = explode('/', $uri);
        $result = [];
        for ($i = 0; $i < $level - 1; $i++) {
            $result[] = $data[$i];
        }
        //验证路由是否存在
        $result = implode('/', $result);
        if (!$this->routeExists($result, $routes)) {
            throw new AjaxExceptions('缺少路由[' . $result . ']，请定义后重试', 500);
        }
        return $result;
    }

    /**
     * 检测路由是否存在
     * @param string $uri
     * @param array $data
     * @return bool
     */
    protected function routeExists(string $uri, array $data):bool
    {
        $uris = array_pluck($data, 'uri');
        return in_array($uri, $uris) ? true : false;
    }

    /**
     * 添加路由到节点表
     * @param array $routes
     */
    protected function create(array $routes)
    {
        foreach ($routes as $key => $route) {
            //$routes[$key]['creator'] = \Auth::user()->id;
            $this->model->create($route);
        }
    }

    /**
     * 更新路由并将更新过的内容从数据库中清除
     * @param array $routes
     * @param $routes_db
     * @return array
     */
    protected function update(array $routes):array
    {
        $data = $this->model->whereType(1)->get(); //数据库中存在的路由
        foreach ($data as $v) {
            foreach ($routes as $key => $route) {
                if ($v->uri == $route['uri']) {
                    $v->fill($route)->save();
                    unset($routes[$key]);
                }
            }
        }
        return $routes;
    }

    /**
     * 从数据库中删除不存在的路由
     * @param $routes
     */
    protected function delete(array $routes)
    {
        $uris = array_pluck($routes, 'uri');
        $this->model->whereType(1)->whereNotIn('uri', $uris)->delete();
    }

}