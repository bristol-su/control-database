<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twigger\UnionCloud\API\UnionCloud;
use IteratorAggregate;

class UnionCloudController extends Controller
{
    /**
     * @var UnionCloud
     */
    protected $unionCloud;

    public function getAllGroups()
    {
        $this->unionCloud = resolve('Twigger\UnionCloud\API\UnionCloud');
        $groups = $this->unionCloud->groups()->setMode('basic')->paginate()->getAll()->getAllPages();
        $reduced_groups = [];
        foreach($groups as $group)
        {

            $reduced_groups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'status' => $group->status
            ];
        }
        return json_encode($reduced_groups);
    }

    public function searchStudents()
    {
        $parameters = request()->get('parameters');
        $this->unionCloud = resolve('Twigger\UnionCloud\API\UnionCloud');
        $studentsArray = [];
        $students = $this->unionCloud->users()->setMode('standard')->paginate()->search($parameters)->get();
        // TODO allow for pagination here
        foreach($students as $student)
        {
            $reduced_students[] = [
                'uid' => $student->uid,
                'student_information' => $student->forename.' '.$student->surname.' ('.$student->dob->format('d-m-Y').') - '.$student->uid
            ];
        }
        return json_encode($reduced_students);
    }
}
