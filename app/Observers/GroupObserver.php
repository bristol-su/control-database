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
        // TODO Is this check enough? It may be letting stuff through
        if($relationName === 'tags')
        {
            foreach($pivotIds as $groupTagID)
            {
                event(new GroupTagged($model, GroupTag::find($groupTagID)));
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
        }
    }
}
