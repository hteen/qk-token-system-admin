<?php namespace App\Listeners;

use Illuminate\Auth\Events\Logout;


/**
 * 注销
 * Class LogSuccessfulLogout
 * @package App\Listeners
 */
class LogSuccessfulLogout
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
     * @param  Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        
    }
}
