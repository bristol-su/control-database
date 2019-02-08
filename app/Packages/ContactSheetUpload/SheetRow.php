<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/02/19
 * Time: 16:45
 */

namespace App\Packages\ContactSheetUpload;


use App\Jobs\SaveStudentInCache;
use App\Models\Group;
use App\Models\Position;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Twigger\UnionCloud\API\UnionCloud;

class SheetRow extends SheetRowInterface
{

    protected $unionCloudStudent;

    public function generateData()
    {
        $unionCloudStudent = $this->getUnionCloudStudent($this->student->uc_uid);
        if($unionCloudStudent === false) {
            return false;
        }

        $this->unionCloudStudent = $unionCloudStudent;

        $this->elements = [
            'group_status' => $this->getGroupStatus(),
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'group_email' => $this->group->email,
            'group_accounts' => implode(', ', $this->group->accounts->pluck('code')->toArray()),
            'position' => $this->position->name,
            'uid' => $this->student->uc_uid,
            'forename' => $this->unionCloudStudent->forename,
            'surname' => $this->unionCloudStudent->surname,
            'student_id' => $this->unionCloudStudent->id,
            'email' => $this->unionCloudStudent->email
        ];

        return true;
    }

    private function getUnionCloudStudent($uid)
    {

        if(Cache::has('command:contactsheet:unioncloud:uid.'.$uid))
        {
            return json_decode(Cache::get('command:contactsheet:unioncloud:uid.'.$uid));
        }
        // Dispatch job to collect information and save it in the cache
        SaveStudentInCache::dispatch($uid);

        return false;
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
            'Group Accounts',
            'Position',
            'UnionCloud UID',
            'Forename',
            'Surname',
            'Student ID',
            'Email'
        ];
    }

}