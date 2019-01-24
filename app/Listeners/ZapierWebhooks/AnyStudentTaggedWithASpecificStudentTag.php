<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\StudentTagged;

class AnyStudentTaggedWithASpecificStudentTag extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyStudentTaggedWithASpecificStudentTag";

    protected $student;

    protected $tag;

    protected function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($this->student->only(['id', 'uc_uid'])))),
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
     * - Any student is tagged with a specific student tag
     *
     * @param  StudentTagged  $event
     * @return void
     */
    public function handle(StudentTagged $event)
    {
        $this->student = $event->student;
        $this->tag = $event->tag;
        $this->trigger();
    }
}
