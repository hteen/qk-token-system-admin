<?php namespace App\Libs\Node;

use App\Exceptions\AjaxExceptions;

/**
 * 节点(集合)相关功能
 * Date: 2016/10/10 0010
 * Time: 11:31
 */
class Nodes
{
    protected $model;

    public function __construct(\App\Model\Manage\Nodes $model)
    {
        $this->model = $model;
    }

    /**
     * 将所有节点生成树状结构
     * @param null $function
     * @param bool $menu
     * @return array
     */
    public function getTree($function = null, bool $menu = false): array
    {
        if ($function) {
            $model = call_user_func($function, $this->model);
        } else {
            $model = $this->model;
        }
        if ($menu) {
            $model = $model->select(['id', 'parent_uri', 'uri', 'name', 'style'])->where('hide', 1)->where('level', '<', 3);
        }
        $data = $model->orderBy('weight', 'desc')->orderBy('id', 'asc')->get();
        if ($menu) {
            $data = $this->formatMenuData($data);
        }
        return $this->getTreeData($data->toArray());
    }

    /**
     * 处理菜单数据
     * @param $data
     * @return mixed
     */
    protected function formatMenuData($data)
    {
        $node = $this->getCurrentNode();
        if (empty($node)) {
            return $data;
        }

        $uri = explode('?', $node->uri)[0];
        foreach ($data as $k => $v) {
            if ($v['uri'] == $uri || $v['uri'] == $node->parent_uri) {
                $data[$k]['active'] = true;
            } else {
                $data[$k]['active'] = false;
            }
        }
        return $data;
    }

    /**
     * 获取当前访问节点
     */
    protected function getCurrentNode()
    {
        $current_uri = \Route::current()->uri();
        return $this->model->where('uri', $current_uri)->first();
    }

    /**
     * 递归生成树状结构
     * @param string|null $parent_uri
     * @return array
     */
    protected function getTreeData(array $data, string $parent_uri = null): array
    {
        $tree = [];
        $parent_uris = array_pluck($data, 'parent_uri');
        foreach ($data as $key => $item) {
            if ($item['parent_uri'] == $parent_uri) {
                if (in_array($item['uri'], $parent_uris)) {
                    $item['children'] = $this->getTreeData($data, $item['uri']);
                }
                $tree[] = $item;
                unset($data[$key]);
            }
        }
        return $tree;
    }

    /**
     * 开始加载路由
     */
    public function load()
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
    protected function groupByLevel(array $routes, int $level = 1): array
    {
        $count = 0;
        foreach ($routes as $key => $route) {
            if (!empty($route['level'])) {
                //已经分析，跳过
                continue;
            }
            if ($level == 2) {
                $route['uri'];
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
    protected function getLevel(string $uri): int
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
     * @throws AjaxExceptions
     */
    protected function getRoutes(): array
    {
        $routes = [];
        $data = \Route::getRoutes();
        foreach ($data as $v) {
            $name = $v->getName();
            if (!in_array($name, ['login', null])) {
                $tmp = [];
                $tmp['uri'] = $v->uri();
                $tmp['method'] = implode(',', $v->methods());
                $tmp['name'] = $v->getName();
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
     * @param array $routes
     * @return string
     * @throws AjaxExceptions
     */
    protected function getParentUri(string $uri, int $level, array $routes): string
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
            throw new AjaxExceptions('缺少路由[' . $result . ']，请定义后重试', 422);
        }
        return $result;
    }

    /**
     * 检测路由是否存在
     * @param string $uri
     * @param array $data
     * @return bool
     */
    protected function routeExists(string $uri, array $data): bool
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
            $this->model->create($route);
        }
    }

    /**
     * 更新路由并将更新过的内容从数据库中清除
     * @param array $routes
     * @return array
     */
    protected function update(array $routes): array
    {
        $data = $this->model->get(); //数据库中存在的路由
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
        $this->model->whereNotIn('uri', $uris)->delete();
    }

    /**
     * 获取所有子节点id(包括子节点的子节点)
     * @param array $routes
     * @return array
     */
    public function getChildrenIds(array $routes): array
    {
        foreach ($routes as &$v) {
            if (!empty($v['children'])) {
                foreach ($v['children'] as $k1 => &$v1) {
                    $v['cid'][] = $v1['id'];
                    if (!empty($v1['children'])) {
                        foreach ($v1['children'] as $k2 => &$v2) {
                            $v['cid'][] = $v2['id'];
                            $v1['cid'][] = $v2['id'];
                        }
                    }
                }
            }
        }
        return $routes;
    }

    /**
     * 获取当前位置
     */
    public function getNavigation()
    {
        $current_node = $this->getCurrentNode();
        if (!$current_node || $current_node->uri == '/') {
            return null;
        }
        $data = $this->getParents($current_node->parent_uri);
        $data[] = ['name' => $current_node->name, 'uri' => $current_node->uri, 'level' => $current_node->level, 'active' => true];
        $data = multi_sort($data, 'level');
        return $data;
    }

    /**
     * (递归)获取上级节点
     * @param string $parent_uri
     * @param array $data
     * @return array
     */
    protected function getParents(string $parent_uri, array $data = []): array
    {
        if (!$parent_uri) {
            return $data;
        }
        $node = $this->model->where('uri', $parent_uri)->select('parent_uri', 'name', 'uri', 'level')->first();
        $data[] = ['name' => $node->name, 'uri' => $node->uri, 'level' => $node->level, 'active' => false];
        if ($node->level > 1) {
            $data = $this->getParents($node->parent_uri, $data);
        }
        return $data;
    }
}