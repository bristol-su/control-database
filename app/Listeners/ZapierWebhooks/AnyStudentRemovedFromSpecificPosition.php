<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\StudentRemovedFromPosition;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnyStudentRemovedFromSpecificPosition extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyStudentRemovedFromSpecificPosition";

    protected $position;

    protected $student;

    protected $group;

    protected function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($this->student->only(['id', 'uc_uid'])))),
            array_flip(array_map(function($u){ return 'position_'.$u; }, array_flip($this->position->only(['id', 'name', 'description'])))),
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($this->position->only(['id', 'name']))))

        );
    }

    protected function passFilter($filter)
    {
        return $this->position->id == $filter['position_id'];
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
     * @param  StudentRemovedFromPosition  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(StudentRemovedFromPosition $event)
    {
        $this->position = $event->psg->position;
        $this->student = $event->psg->student;
        $this->group = $event->psg->group;
        $this->trigger();
    }
}
