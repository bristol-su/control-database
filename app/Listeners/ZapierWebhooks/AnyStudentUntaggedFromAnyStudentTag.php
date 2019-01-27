<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\GroupTagged;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\Student;
use App\Models\StudentTag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AnyStudentUntaggedFromAnyStudentTag extends ZapierWebhookListener
{
    protected $eventSubscribeName = "AnyStudentUntaggedFromAnyStudentTag";

    /** @var Student */
    private $student;

    /** @var StudentTag */
    private $tag;

    public function formatForZapier($filter)
    {
        // TODO Too complex?
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
     * @param  StudentTagged  $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(StudentUntagged $event)
    {
        $this->student = $event->student;
        $this->tag = $event->tag;
        $this->trigger();
    }
}
