<?php namespace App\Listeners;

use Illuminate\Auth\Events\Login;

/**
 * 登陆成功
 * @package App\Listeners
 */
class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {

    }
}
