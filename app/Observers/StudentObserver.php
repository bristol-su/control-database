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
        } elseif($relationName === 'groups')
        {
            foreach($pivotIds as $groupID)
            {
                event(new StudentAddedToGroup($model, Group::findOrFail($groupID)));
            }
        } elseif($relationName === 'positions')
        {
            foreach($pivotIds as $positionID)
            {
                event(new StudentGivenPosition($model, Position::findOrFail($positionID)));
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
        } elseif($relationName === 'positions')
        {
            foreach($pivotIds as $positionID)
            {
                event(new StudentRemovedFromPosition($model, Position::findOrFail($positionID)));
            }
        }
    }
}
