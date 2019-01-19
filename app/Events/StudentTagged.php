<?php

namespace App\Events;

use App\Models\Student;
use App\Models\StudentTag;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class StudentTagged
{
    use Dispatchable, SerializesModels;

    public $student;

    public $tag;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $student, StudentTag $tag)
    {
        $this->student = $student;
        $this->tag = $tag;
    }
}
