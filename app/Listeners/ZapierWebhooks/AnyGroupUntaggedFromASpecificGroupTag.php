<?php

namespace App\Listeners\ZapierWebhooks;

use App\Events\GroupTagged;
use App\Events\GroupUntagged;
use Illuminate\Support\Facades\Log;

class AnyGroupUntaggedFromASpecificGroupTag extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyGroupUntaggedFromASpecificGroupTag";

    protected $group;

    protected $tag;

    protected function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($this->group->only(['id', 'name', 'unioncloud_id', 'email'])))),
            array_flip(array_map(function($u){ return 'tag_'.$u; }, array_flip($this->tag->only(['id', 'name', 'description'])))),
            [
                'tag_category_name'=>$this->tag->category->name,
                'tag_category_description'=>$this->tag->category->description,
                'tag_reference'=>$this->tag->category->reference.'.'.$this->tag->reference,
            ]
        );
    }

    protected function passFilter($filter)
    {
        if($this->tag->id == $filter['tag'])
        {
            return true;
        }
        return false;
    }

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
     * Will update the webhooks in the following conditions:
     * - Any group is tagged with a specific group tag
     *
     * @param  GroupTagged  $event
     * @return void
     */
    public function handle(GroupUntagged $event)
    {
        $this->group = $event->group;
        $this->tag = $event->tag;
        $this->trigger();
    }
}
