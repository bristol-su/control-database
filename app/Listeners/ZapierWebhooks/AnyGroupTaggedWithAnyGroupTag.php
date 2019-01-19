<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\GroupTagged;
use App\Models\Group;
use App\Models\GroupTag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AnyGroupTaggedWithAnyGroupTag extends ZapierWebhookListener
{
    protected $eventSubscribeName = "AnyGroupTaggedWithAnyGroupTag";

    /** @var Group */
    private $group;

    /** @var GroupTag */
    private $tag;

    public function formatForZapier($filter)
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
     * - Any group is tagged with any group tag
     *
     * @param  GroupTagged  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(GroupTagged $event)
    {
        $this->group = $event->group;
        $this->tag = $event->tag;
        $this->trigger();
    }
}
