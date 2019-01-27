<?php

namespace App\Observers;

use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Models\Student;

class StudentTagObserver
{
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        // Student Tagged
        if($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentTagged(Student::findOrFail($studentID), $model));
            }
        }
    }

    public function pivotDetached($model, $relationName, $pivotIds)
    {
        // Student Untagged
        if($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentUntagged(Student::findOrFail($studentID), $model));
            }
        }
    }
}
