<?php namespace App\Http\Middleware;

use App\Libs\Manager\Manager;

class Power
{
    protected $except = [
        'common/*',
    ];

    public function handle($request, \Closure $next)
    {
        if ($this->needCheck($request)) {
            if (!app(Manager::class)->checkPower()) {
                abort(403);
            }
        }
        return $next($request);
    }

    protected function needCheck($request)
    {
        //例外
        foreach ($this->except as $item) {
            if ($request->is($item)) {
                return false;
            }
        }
        return true;
    }

}
