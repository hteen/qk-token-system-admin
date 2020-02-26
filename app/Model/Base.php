<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * model基类
 * Class Base
 *
 * @package App\Model
 * @mixin \Eloquent
 */
class Base extends Model
{
    /**
     * 是否自动排序
     * @var bool
     */
    protected $auto_order = true;

    /**
     * 搜索条件
     * @var array
     */
    protected $field_search = [];

    /**
     * 不能更改的字段
     * @var array
     */
    protected $guarded = ['id', '_token', '__no_repeat'];

    public $_with = [];

    /**
     * 生成搜索条件
     * @param $model
     * @return mixed
     */
    protected function getSearch($model)
    {
        $search = $this->field_search;
        if (empty($search)) {
            return $model;
        }
        $val = null;
        $tmp = null;
        foreach ($search as $k => $v) {

            $tmp = null;
            $val = \Request::input($k);
            if ($val) {
                switch ($v) {
                    case 'eq':
                        $model = $model->whereRaw("`{$k}`='" . $val . "'");
                        break;
                    case 'like':
                        $model = $model->whereRaw("`{$k}` like '%" . $val . "%'");
                        break;
                    case 'date_range':
                        $tmp = explode(' - ', $val);
                        $start = date('Ymd', strtotime($tmp[0]));
                        $end = date('Ymd', strtotime($tmp[1]));
                        if ($tmp) {
                            $model = $model->whereRaw("{$k} between $start and $end");
                        }
                        break;
                    case 'date':
                        $date = date('Ymd', strtotime($val));
                        if ($val) {
                            $model = $model->where($k, $date);
                        }
                        break;
                    case 'set':
                        $model = $model->whereRaw("find_in_set('{$val}',`{$k}`)");
                        break;
                    case 'in':
                        $model = $model->whereRaw("`{$k}` in (" . $val . ")");
                        break;
                }
            }
        }

        $created_at_begin = \Request::input('created_at_begin');
        $created_at_end = \Request::input('created_at_end');
        $created_at_begin = $created_at_begin ? $created_at_begin . ' : 00:00:00' : '';
        $created_at_end = $created_at_end ? $created_at_end . ' : 23:59:59' : '';

        if ($created_at_begin && $created_at_end) {
            $model = $model->whereRaw("created_at between '{$created_at_begin}' and '{$created_at_end}'");
        } else if ($created_at_begin) {
            $model = $model->whereRaw("created_at > '{$created_at_begin}'");
        } else if ($created_at_end) {
            $model = $model->whereRaw("created_at < '{$created_at_end}'");
        }

        return $model;
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

    /**
     * 获取分页数据
     * @param \Closure|null $function
     * @param \Closure|null $callback
     * @param int $per_page
     * @param bool $auto_order
     * @return array
     */
    public function getPaginateData(\Closure $function = null, \Closure $callback = null, int $per_page = 20, bool $auto_order = true): array
    {
        $with = $this->with;
        $model = $this;

        if ($function) {
            $model = call_user_func($function, $this);
        }
        $model = $this->getSearch($model);
        if ($with) {
            $model = $model->with($with);
        }
        if ($auto_order !== false) {
            $model = $this->getOrder($model);
        }
        $data = $model->paginate($per_page);
        if ($callback) {
            $data = call_user_func($callback, $data);
        }
        $page = $data->appends(\Request::except(['page']))->render();
        return ['data' => $data, 'pages' => $page];
    }

    /**
     * ajax方式获取数据
     * @param array $fields
     * @param \Closure|null $function
     * @param \Closure|null $callback
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getPaginateDataAjax(array $fields = ['*'], \Closure $function = null, \Closure $callback = null, int $limit = 20, int $offset = 0)
    {

        //这里由之前的$this->with修改成$this->_with
        //因为$this->with是框架原本的protected属性 外部设置不会生效 只会通过魔术方法__set到$this->attribute["with"]这里去  所以base添加一个_with public属性
        $with = $this->_with;

        $model = $this;
        if ($function) {
            $model = call_user_func($function, $this);
        }
        $model = $this->getSearch($model);
        if ($with) {
            $model = $model->with($with);
            $this->_with = [];//调用后置空
        }

        $model = $this->getOrder($model);
        $data = $model->paginate($limit, $fields, 'page', ceil($offset / $limit) + 1);
        if ($callback) {
            foreach ($data as &$v) {
                $v = call_user_func($callback, $v);
            }
        }

        return $data;
    }

}
