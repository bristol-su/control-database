<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/02/19
 * Time: 16:45
 */

namespace App\Packages\ContactSheetUpload;


use App\Jobs\SaveGroupInCache;
use App\Jobs\SaveStudentInCache;
use Illuminate\Support\Facades\Cache;

class SheetRow extends BaseSheetRow
{

    protected $unionCloudStudent;

    public static function getHeaders()
    {
        return [
            'Group Status',
            'Group Control ID',
            'Group Name',
            'UnionCloud Group Name',
            'Group Email',
            'Group UnionCloud ID',
            'Group Accounts',
            'Role',
            'Position Title',
            'Active Year',
            'UnionCloud UID',
            'Forename',
            'Surname',
            'Student ID',
            'Email',
            'Position Started'
        ];
    }

    public function generateData()
    {
        $unionCloudStudent = $this->getUnionCloudStudent($this->student->uc_uid);
        $unionCloudGroup = $this->getUnionCloudGroup($this->group->unioncloud_id);

        if ($unionCloudStudent === false || $unionCloudGroup === false) {
            return false;
        }


        $this->unionCloudStudent = $unionCloudStudent;

        $this->elements = [
            'group_status' => $this->getGroupStatus(),
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'unioncloud_group_name' => $unionCloudGroup->name,
            'group_email' => $this->group->email,
            'group_unioncloud_id' => $this->group->unioncloud_id,
            'group_accounts' => implode(', ', $this->group->accounts->pluck('code')->toArray()),
            'role' => $this->position->name,
            'position_title' => ($this->positionStudentGroup->position_name === '' ? $this->position->name : $this->positionStudentGroup->position_name),
            'committee_year' => getAcademicYear($this->positionStudentGroup->committee_year),
            'uid' => $this->student->uc_uid,
            'forename' => $this->unionCloudStudent->forename,
            'surname' => $this->unionCloudStudent->surname,
            'student_id' => $this->unionCloudStudent->id,
            'email' => $this->unionCloudStudent->email,
            'started' => $this->positionStudentGroup->created_at
        ];
        return true;
    }

    private function getUnionCloudStudent($uid)
    {

        if (Cache::has('command:contactsheet:unioncloud:uid.' . $uid)) {
            return json_decode(Cache::get('command:contactsheet:unioncloud:uid.' . $uid));
        }
        // Dispatch job to collect information and save it in the cache
        SaveStudentInCache::dispatch($uid);

        return false;
    }

    public function getUnionCloudGroup($groupId)
    {
        if (Cache::has('command:contactsheet:unioncloud:group:id.' . $groupId)) {
            return json_decode(Cache::get('command:contactsheet:unioncloud:group:id.' . $groupId));
        }
        // Dispatch job to collect information and save it in the cache
        SaveGroupInCache::dispatch($groupId);

        return false;
    }

    public function getGroupStatus()
    {
        return ($this->group->trashed() ? 'Deactive' : 'Active');
    }

}
