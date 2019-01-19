<?php

namespace App\Http\Controllers\API;

use App\Events\GroupTagged;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\GroupTagCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class GroupTagAPIController extends Controller
{

    use SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Group Tag Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */



    /**
     * Get all groupTags registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $groupTags = GroupTag::all();

        return $groupTags;
    }

    /**
     * Get an groupTag by ID. Route model binding will pass the groupTag
     *
     * @param GroupTag $groupTag
     *
     * @return GroupTag
     */
    public function get(GroupTag $groupTag)
    {
        return $groupTag;
    }

    /**
     * Create an groupTag
     *
     * @param Request $request
     *
     * @return GroupTag
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'description' => 'required|min:3|max:65535',
            'reference' => 'required|min:1|max:255'
        ]);

        $groupTag = new GroupTag($request->only([
            'name',
            'description',
            'reference'
        ]));

        if($groupTag->save())
        {
            return $groupTag;
        }
        return response()->json([
            'error' => 'GroupTag not saved'
        ], 500);
    }

    /**
     * Update an groupTag.
     *
     * @param Request $request
     * @param GroupTag $groupTag
     *
     * @return GroupTag
     */
    public function update(Request $request, GroupTag $groupTag)
    {
        $request->validate([
            'name' => 'sometimes|min:1|max:255',
            'description' => 'sometimes|min:3|max:65535',
            'reference' => 'sometimes|min:1|max:255'
        ]);

        $groupTag->fill($request->input());

        $groupTag->save();
        return $groupTag;
    }

    /**
     * Delete an groupTag
     *
     * @param GroupTag $groupTag
     *
     * @return Response
     */
    public function delete(GroupTag $groupTag)
    {
        try {
            $groupTag->delete();
        } catch (\Exception $e)
        {
            return response('GroupTag couldn\'t be deleted', 500);
        }

        return response('', 204);
    }






    /*
    |--------------------------------------------------------------------------
    | Group Tag -> Group Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between group tag and groups
    |
    */

    public function getGroups(GroupTag $groupTag)
    {
        return $groupTag->groups;
    }
    
    public function linkGroups(Request $request, GroupTag $groupTag)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:groups,id'
        ]);

        $groupTag->groups()->syncWithoutDetaching( $request->input('id') );


        return response('', 204);
    }

    public function deleteGroups(Request $request, GroupTag $groupTag, Group $group)
    {

        if($groupTag->groups()->detach( $group ))
        {
            return response('', 204);
        }
        return response('Group couldn\'t be detached', 500);

    }
        
    
    
    
    /*
    |--------------------------------------------------------------------------
    | Group Tag -> Group Tag Category Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to One relationship between group tag and group tag categories
    |
    */

    public function getGroupTagCategory(GroupTag $groupTag)
    {
        return $groupTag->category;
    }

    public function linkGroupTagCategory(Request $request, GroupTag $groupTag)
    {

        $request->validate([
            'id' => 'required|exists:group_tag_categories|nullable'
        ]);

        $groupTag->group_tag_category = (int) $request->input('id');
        $groupTag->save();

        return $groupTag;
    }

}
