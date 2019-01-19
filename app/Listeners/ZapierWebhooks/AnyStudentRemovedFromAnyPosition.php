<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnyStudentRemovedFromAnyPosition extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyStudentRemovedFromAnyPosition";

    protected $tag;

    protected $student;

    protected function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($this->student->only(['id', 'uc_uid'])))),
            array_flip(array_map(function($u){ return 'position_'.$u; }, array_flip($this->tag->only(['id', 'name', 'description'])))),
            [
                'tag_reference'=>$this->tag->category->reference.'.'.$this->tag->reference,
            ]
        );
    }

    protected function passFilter($filter)
    {
        return $this->tag->category->reference == config('app.student_tag_category_position_reference');
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
     * -Any student gets a position tag
     *
     * @param  StudentTagged  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(StudentUntagged $event)
    {
        $this->tag = $event->tag;
        $this->student = $event->student;
        $this->trigger();
    }
}
