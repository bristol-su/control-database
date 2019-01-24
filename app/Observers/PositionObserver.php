<?php

namespace App\Observers;


use App\Events\StudentGivenPosition;
use App\Events\StudentRemovedFromPosition;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class PositionObserver
{
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        // Student Given Position
        if($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentGivenPosition(Student::findOrFail($studentID), $model));
            }
        }
    }

    public function pivotDetached($model, $relationName, $pivotIds)
    {
        // Student Loses Position
        if($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentRemovedFromPosition(Student::findOrFail($studentID), $model));
            }
        }
    }
}
