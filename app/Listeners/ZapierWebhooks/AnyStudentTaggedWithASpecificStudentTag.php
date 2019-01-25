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
            keyprefix('student_', $this->student->only(['id', 'uc_uid' ])),
            keyprefix('tag_', $this->tag->only(['id', 'name', 'description'])),
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
