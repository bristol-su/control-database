<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/02/19
 * Time: 16:45
 */

namespace App\Packages\GroupSheetUpload;


use App\Jobs\SaveStudentInCache;
use App\Models\Group;
use App\Models\Position;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Twigger\UnionCloud\API\UnionCloud;

class SheetRow extends BaseSheetRow
{

    protected $unionCloudStudent;

    public function generateData()
    {

        $this->elements = [
            'group_status' => $this->getGroupStatus(),
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'group_email' => $this->group->email,
            'group_unioncloud_id' => $this->group->unioncloud_id,
            'group_accounts' => implode(', ', $this->group->accounts->pluck('code')->toArray()),
        ];

        return true;
    }

    public function getGroupStatus()
    {
        return ($this->group->trashed()?'Deactive':'Active');
    }

    public static function getHeaders()
    {
        return [
            'Group Status',
            'Group Control ID',
            'Group Name',
            'Group Email',
            'Group UnionCloud ID',
            'Group Accounts'
        ];
    }

}