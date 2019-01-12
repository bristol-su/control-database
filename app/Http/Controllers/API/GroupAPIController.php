<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use App\Models\GroupTag;
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
            'unioncloud_id' => 'required'
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
     * @return Response
     */
    public function delete(Group $group)
    {
        try {
            $group->delete();
        } catch (\Exception $e)
        {
            return response('Group couldn\'t be deleted', 500);
        }

        return response('', 204);
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
        return $group->tags;
    }

    public function linkGroupTags(Request $request, Group $group)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:group_tags,id'
        ]);

        $group->tags()->syncWithoutDetaching( $request->input('id') );

        return response('', 204);
    }

    public function deleteGroupTags(Request $request, Group $group, GroupTag $groupTag)
    {

        if($group->tags()->detach( $groupTag ))
        {
            return response('', 204);
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

        $group->students()->syncWithoutDetaching( $request->input('id') );

        return response('', 204);
    }

    public function deleteStudents(Request $request, Group $group, Student $student)
    {

        if($group->students()->detach( $student ))
        {
            return response('', 204);
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

        return response('', 204);
    }

    public function deleteAccounts(Request $request, Group $group, Account $account)
    {

        if($account->group_id === $group->id)
        {
            $account->group_id = null;
            $account->save();
            return response('', 204);

        }
        return response('Account wasn\'t assigned to group', 204);

    }
}
