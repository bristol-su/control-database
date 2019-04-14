<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\StudentGivenPosition;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class AnyStudentGivenAnyPosition extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyStudentGivenAnyPosition";

    protected $position;

    protected $student;

    protected $group;

    protected function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($this->student->only(['id', 'uc_uid'])))),
            array_flip(array_map(function($u){ return 'position_'.$u; }, array_flip($this->position->only(['id', 'name', 'description'])))),
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($this->group->only(['id', 'name']))))
        );
    }

    protected function passFilter($filter)
    {
        return true;
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
     * @param  StudentGivenPosition  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(StudentGivenPosition $event)
    {
        $this->position = $event->psg->position;
        $this->student = $event->psg->student;
        $this->group = $event->psg->group;
        $this->trigger();
    }
}
