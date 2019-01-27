<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\GroupTagged;
use App\Models\GroupTagCategory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AnyGroupTaggedWithAnyTagFromGroupTagCategory extends ZapierWebhookListener
{
    protected $eventSubscribeName = 'AnyGroupTaggedWithAnyTagFromGroupTagCategory';
    protected $tag;
    protected $group;

    protected function formatForZapier($filter)
    {
        $group_tag_category = GroupTagCategory::findOrFail($filter['group_tag_category']);
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

    public function passFilter($filter)
    {
        $group_tag_category = GroupTagCategory::findOrFail($filter['group_tag_category']);
        if(in_array($this->tag->id, $group_tag_category->tags->pluck('id')->toArray())) {
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
     * - Any group is tagged with any group tag falling under
     *      a specific group tag category
     *
     * @param  GroupTagged  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(GroupTagged $event)
    {
        $this->tag = $event->tag;
        $this->group = $event->group;
        $this->trigger();
    }
}
