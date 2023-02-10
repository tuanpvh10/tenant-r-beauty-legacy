<?php

namespace App\Events\MenuItem;

use App\MenuItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuItemDeleted
{
    use Dispatchable, SerializesModels;

    /** @var MenuItem $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
