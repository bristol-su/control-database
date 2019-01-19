<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\GroupTagged;
use App\Events\StudentAddedToGroup;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\Student;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CommitteeMemberAssignedToGroup extends ZapierWebhookListener
{
    protected $eventSubscribeName = "CommitteeMemberAssignedToGroup";

    /** @var Group */
    private $group;

    /** @var Student */
    private $student;

    public function formatForZapier($filter)
    {
        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($this->group->only(['id', 'name', 'unioncloud_id', 'email'])))),
            array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($this->student->only(['id', 'uc_uid']))))
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
    public function handle(StudentAddedToGroup $event)
    {
        $this->group = $event->group;
        $this->student = $event->student;
        $this->trigger();
    }
}
