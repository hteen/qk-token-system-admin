<?php namespace App\Http\Middleware;

use App\Exceptions\AjaxExceptions;

/**
 * 表单防重复提交中间件
 * Class NoRepeated
 * @package App\Http\Middleware
 */
class NoRepeated
{
    protected $except = [
        'common/*',
        'user/disable',
    ];

    public function handle($request, \Closure $next)
    {
        if ($this->needCheck($request)) {
            if (!check_repeat()) {
                throw new AjaxExceptions('请不要重复提交表单', 500);
            }
        }
        return $next($request);
    }

    /**
     * 当前是否需要检测
     * @param $request
     */
    protected function needCheck($request)
    {
        if (strtolower($request->method()) != 'post') {
            return false;
        }

        if (!$request->ajax()) {
            return false;
        }

        //例外
        foreach ($this->except as $item) {
            if ($request->is($item)) {
                return false;
            }
        }
        return true;
    }

}
