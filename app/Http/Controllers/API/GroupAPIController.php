<?php

namespace App\Http\Controllers\API;

use App\Events\GroupTagged;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\Position;
use App\Models\PositionStudentGroup;
use App\Models\Student;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class GroupAPIController extends Controller
{

    use SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Group Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */


    /**
     * Get all groups registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $groups = Group::all();

        return $groups;
    }

    /**
     * Get an group by ID. Route model binding will pass the group
     *
     * @param Group $group
     *
     * @return Group
     */
    public function get(Group $group)
    {
        return $group;
    }

    /**
     * Create an group
     *
     * @param Request $request
     *
     * @return Group
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|min:3|',
            'unioncloud_id' => 'sometimes'
        ]);

        $group = new Group($request->only([
            'name',
            'unioncloud_id'
        ]));

        if($group->save())
        {
            return $group;
        }
        return response()->json([
            'error' => 'Group not saved'
        ], 500);
    }

    /**
     * Update an group.
     *
     * @param Request $request
     * @param Group $group
     *
     * @return Group
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'sometimes|max:255|min:3|',
            'unioncloud_id' => 'sometimes'
        ]);

        $group->fill($request->input());

        $group->save();

        return $group;
    }

    /**
     * Delete an group
     *
     * @param Group $group
     *
     * @return Group
     */
    public function delete(Group $group)
    {
        try {
            $group->delete();
        } catch (\Exception $e)
        {
            return response('Group couldn\'t be deleted', 500);
        }
        return $group;
    }

    /**
     * Search for a Group
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function search(Request $request)
    {
        $groups = Group::where('unioncloud_id', $request->input('unioncloud_id'))->get();

        return $groups;
    }

    /*
    |--------------------------------------------------------------------------
    | Group -> Group Tag Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between groups and group tags
    |
    */


    public function getGroupTags(Group $group)
    {
        return $group->tags()->with('category')->get();
    }

    public function linkGroupTags(Request $request, Group $group)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:group_tags,id',
            'data' => 'sometimes|array'
        ]);
        if($request->has('data'))
        {
            $attachArray = [];
            for($i=0;$i<count($request->input('id'));$i++)
            {
                $attachArray[$request->input('id')[$i]] = ['data' => (isset($request->input('data')[$i])?$request->input('data')[$i]:null)];
            }
            $group->tags()->attach($attachArray);
        } else
        {
            $group->tags()->attach( $request->input('id'));
        }


        $groupTags = GroupTag::find($request->input('id'))->each(function($groupTag) {
            return array_flip(array_map(function($u){ return 'tag_'.$u; }, array_flip($groupTag->only(['id', 'name', 'description']))));
        });

        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'email', 'unioncloud_id'])))),
            ["group_tags" => $groupTags]
        );
        
    }

    public function deleteGroupTags(Request $request, Group $group, GroupTag $groupTag)
    {

        if($group->tags()->detach( $groupTag ))
        {
            return array_merge(
                array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'email', 'unioncloud_id'])))),
                array_flip(array_map(function($u){ return 'group_tag_'.$u; }, array_flip($groupTag->only(['id', 'name', 'description']))))
            );
        }
        return response('Group couldn\'t be detached', 500);
    }

    /*
    |--------------------------------------------------------------------------
    | Group -> Student Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between groups and students
    |
    */

    public function getStudents(Group $group)
    {
        return $group->students;
    }

    public function linkStudents(Request $request, Group $group)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:students,id'
        ]);

        $group->students()->attach( $request->input('id') );

        $students = Group::find($request->input('id'))->each(function($student, $item) {
            return array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($student->only(['id', 'uc_uid']))));
        });

        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'email', 'unioncloud_id'])))),
            ["students" => $students]
        );
    }

    public function deleteStudents(Request $request, Group $group, Student $student)
    {

        if($group->students()->detach( $student ))
        {
            return array_merge(
                array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'email', 'unioncloud_id'])))),
                array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($student->only(['id', 'uc_uid']))))
            );
        }
        return response('Student couldn\'t be removed from the group', 500);
    }



    /*
    |--------------------------------------------------------------------------
    | Group -> Accounts
    |--------------------------------------------------------------------------
    |
    | Enable the One to Many relationship between groups and accounts
    |
    */

    public function getAccounts(Group $group)
    {
        return $group->accounts;
    }

    public function linkAccounts(Request $request, Group $group)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:accounts,id'
        ]);

        foreach($request->input('id') as $id)
        {
            $account = Account::find((int) $id);
            $account->group_id = $group->id;
            $account->save();
        }

        $accounts = Account::find($request->input('id'))->each(function($account) {
            return array_flip(array_map(function($u){ return 'account_'.$u; }, array_flip($account->only(['id', 'description', 'code', 'is_department_code']))));
        });

        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'email', 'unioncloud_id'])))),
            ["accounts" => $accounts]
        );
    }

    public function deleteAccounts(Request $request, Group $group, Account $account)
    {

        if($account->group_id === $group->id)
        {
            $account->group_id = null;
            $account->save();

            return array_merge(
                array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'description', 'unioncloud_id'])))),
                array_flip(array_map(function($u){ return 'account_'.$u; }, array_flip($account->only(['id', 'uc_uid']))))
            );
        }
        return response('Account wasn\'t assigned to group', 200);

    }

    /*
|--------------------------------------------------------------------------
| Student -> PositionStudentGroups Relationships
|--------------------------------------------------------------------------
|
| Enable the Many to Many relationship between students and PositionStudentGroups
|
*/


    public function getPositionStudentGroups(Group $group)
    {
        $positionStudentGroups = $group->positionStudentGroups;

        foreach($positionStudentGroups as $psg) {
            $psg->student = Student::find($psg->student_id);
            $psg->position = Position::find($psg->position_id);
        }

        return $positionStudentGroups;
    }



}

