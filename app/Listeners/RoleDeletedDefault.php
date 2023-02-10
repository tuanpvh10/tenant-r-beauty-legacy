<?php

namespace App\Listeners;


use App\Events\Role\RoleDeleted;

class RoleDeletedDefault
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RoleDeleted $event)
    {
        $event->model->permissions()->delete();
    }
}
