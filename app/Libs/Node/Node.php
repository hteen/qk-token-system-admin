<?php namespace App\Libs\Node;

/**
 * 节点(单个)相关功能
 * Date: 2016/10/10 0010
 * Time: 11:31
 */

class Node
{
    protected $model;

    public function __construct(\App\Model\Manage\Nodes $model)
    {
        $this->model = $model;
    }

    /**
     * 获取当前页面面包屑
     */
    public function getPosition()
    {
    }

}