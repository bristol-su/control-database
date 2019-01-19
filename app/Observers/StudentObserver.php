<?php

namespace App\Observers;

use App\Events\StudentAddedToGroup;
use App\Events\StudentRemovedFromGroup;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Models\Group;
use App\Models\StudentTag;
use Illuminate\Support\Facades\Log;

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
        } elseif($relationName === 'groups')
        {
            foreach($pivotIds as $groupID)
            {
                event(new StudentAddedToGroup($model, Group::findOrFail($groupID)));
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
        } elseif($relationName === 'groups')
        {
            foreach($pivotIds as $groupID)
            {
                event(new StudentRemovedFromGroup($model, Group::findOrFail($groupID)));
            }
        }
    }
}
