<?php

namespace App\Events;

use App\Models\Group;
use App\Models\Student;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StudentAddedToGroup
{
    use Dispatchable, SerializesModels;

    public $student;

    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $student, Group $group)
    {
        $this->student = $student;
        $this->group = $group;
    }
}
