<?php

namespace App\Observers;

use App\Events\StudentAddedToGroup;
use App\Events\StudentGivenPosition;
use App\Events\StudentRemovedFromGroup;
use App\Events\StudentRemovedFromPosition;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Models\Group;
use App\Models\Position;
use App\Models\StudentTag;

class StudentObserver
{
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        // Student Tagged
        if($relationName === 'tags')
        {
            foreach($pivotIds as $studentTagID)
            {
                event(new StudentTagged($model, StudentTag::find($studentTagID)));
            }
        }
    }

    public function pivotDetached($model, $relationName, $pivotIds)
    {
        // Student Untagged
        if($relationName === 'tags')
        {
            foreach($pivotIds as $studentTagID)
            {
                event(new StudentUntagged($model, StudentTag::find($studentTagID)));
            }
        }
    }
}
