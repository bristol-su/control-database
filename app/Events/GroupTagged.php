<?php

namespace App\Events;

use App\Models\Group;
use App\Models\GroupTag;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class GroupTagged
{
    use Dispatchable, SerializesModels;

    public $group;

    public $tag;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Group $group, GroupTag $tag)
    {
        $this->group = $group;
        $this->tag = $tag;
    }
}
