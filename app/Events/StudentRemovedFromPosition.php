<?php

namespace App\Events;

use App\Models\PositionStudentGroup;
use App\Models\Student;
use App\Models\Position;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StudentRemovedFromPosition
{
    use Dispatchable, SerializesModels;

    public $psg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PositionStudentGroup $psg)
    {
        $this->psg = $psg;
    }
}
