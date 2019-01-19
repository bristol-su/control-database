<?php

namespace App\Events;

use App\Models\Group;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class GroupDeactivated
{
    use Dispatchable, SerializesModels;

    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
