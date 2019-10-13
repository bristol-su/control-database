<?php

namespace App\Events;

use App\Models\Role;
use App\Models\Student;
use App\Models\Position;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StudentGivenPosition
{
    use Dispatchable, SerializesModels;

    public $psg;

    /**
     * Create a new event instance.
     *
     * @param Student $student
     * @param Position $position
     *
     * @return void
     */
    public function __construct(Role $psg)
    {
        $this->psg = $psg;
    }
}
