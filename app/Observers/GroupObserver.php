<?php

namespace App\Observers;

use App\Events\GroupTagged;
use App\Events\GroupUntagged;
use App\Events\StudentAddedToGroup;
use App\Events\StudentRemovedFromGroup;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class GroupObserver
{
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        // Group Tagged
        if($relationName === 'tags')
        {
            foreach($pivotIds as $groupTagID)
            {
                event(new GroupTagged($model, GroupTag::find($groupTagID)));
            }
        } elseif($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentAddedToGroup(Student::findOrFail($studentID), $model));
            }
        }
    }

    public function pivotDetached($model, $relationName, $pivotIds)
    {
        // Group Untagged
        if($relationName === 'tags')
        {
            foreach($pivotIds as $groupTagID)
            {
                event(new GroupUntagged($model, GroupTag::find($groupTagID)));
            }
        } elseif($relationName === 'students')
        {
            foreach($pivotIds as $studentID)
            {
                event(new StudentRemovedFromGroup(Student::findOrFail($studentID), $model));
            }
        }
    }
}
