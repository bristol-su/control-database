<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Models\StudentTagCategory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AnyStudentUntaggedFromAnyTagFromStudentTagCategory extends ZapierWebhookListener
{
    protected $eventSubscribeName = 'AnyStudentUntaggedFromAnyTagFromStudentTagCategory';
    protected $tag;
    protected $student;

    protected function formatForZapier($filter)
    {
        $student_tag_category = StudentTagCategory::findOrFail($filter['student_tag_category']);
        // If the tag is in the student category
        if(in_array($this->tag->id, $student_tag_category->tags->pluck('id')->toArray()))
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
        return false;
    }

    public function passFilter($filter)
    {
        $student_tag_category = StudentTagCategory::findOrFail($filter['student_tag_category']);
        if(in_array($this->tag->id, $student_tag_category->tags->pluck('id')->toArray())) {
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
     * - Any student is tagged with any student tag falling under
     *      a specific student tag category
     *
     * @param  StudentUntagged  $event
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
