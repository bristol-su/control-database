<?php

namespace App\Observers;

use App\Events\GroupTagged;
use App\Events\GroupUntagged;
use App\Models\Group;
use Illuminate\Support\Facades\Log;

class GroupTagObserver
{
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        // Group Tagged
        if($relationName === 'groups')
        {
            foreach($pivotIds as $groupID)
            {
                event(new GroupTagged(Group::findOrFail($groupID), $model));
            }
        }
    }

    public function pivotDetached($model, $relationName, $pivotIds)
    {
        // Group Untagged
        if($relationName === 'groups')
        {
            foreach($pivotIds as $groupID)
            {
                event(new GroupUntagged(Group::findOrFail($groupID), $model));
            }
        }
    }
}
