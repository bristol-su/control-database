<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GroupTag;
use App\Models\GroupTagCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class GroupTagCategoryAPIController extends Controller
{

    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Group Tag Category Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */

    /**
     * Get all groupTagCategorys registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $groupTagCategorys = GroupTagCategory::all();

        return $groupTagCategorys;
    }

    /**
     * Get an groupTagCategory by ID. Route model binding will pass the groupTagCategory
     *
     * @param GroupTagCategory $groupTagCategory
     *
     * @return GroupTagCategory
     */
    public function get(GroupTagCategory $groupTagCategory)
    {
        return $groupTagCategory;
    }

    /**
     * Create an groupTagCategory
     *
     * @param Request $request
     *
     * @return GroupTagCategory
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'description' => 'required|min:3|max:65535',
            'reference' => 'required|min:1|max:255'
        ]);

        $groupTagCategory = new GroupTagCategory($request->only([
            'name',
            'description',
            'reference'
        ]));

        if($groupTagCategory->save())
        {
            return $groupTagCategory;
        }
        return response()->json([
            'error' => 'GroupTagCategory not saved'
        ], 500);
    }

    /**
     * Update an groupTagCategory.
     *
     * @param Request $request
     * @param GroupTagCategory $groupTagCategory
     *
     * @return GroupTagCategory
     */
    public function update(Request $request, GroupTagCategory $groupTagCategory)
    {
        $request->validate([
            'name' => 'sometimes|min:1|max:255',
            'description' => 'sometimes|min:3|max:65535',
            'reference' => 'sometimes|min:1|max:255'
        ]);

        $groupTagCategory->fill($request->input());

        $groupTagCategory->save();
        return $groupTagCategory;
    }

    /**
     * Delete an groupTagCategory
     *
     * @param GroupTagCategory $groupTagCategory
     *
     * @return Response
     */
    public function delete(GroupTagCategory $groupTagCategory)
    {
        try {
            $groupTagCategory->delete();
        } catch (\Exception $e)
        {
            return response('GroupTagCategory couldn\'t be deleted', 500);
        }

        return response('', 204);
    }

    /*
    |--------------------------------------------------------------------------
    | Group Tag Category -> Group Tag
    |--------------------------------------------------------------------------
    |
    | Enable the One to Many relationship between Group tag categories
    | and Group tags
    |
    */

    public function getGroupTags    (GroupTagCategory $groupTagCategory)
    {
        return $groupTagCategory->tags;
    }

    public function linkGroupTags(Request $request, GroupTagCategory $groupTagCategory)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:group_tags,id'
        ]);

        foreach($request->input('id') as $id)
        {
            $groupTag = GroupTag::find((int) $id);
            $groupTag->group_tag_category = $groupTagCategory->id;
            $groupTag->save();
        }

        return response('', 204);
    }

    public function deleteGroupTags(GroupTagCategory $groupTagCategory, GroupTag $groupTag)
    {

        if($groupTag->group_tag_category === $groupTagCategory->id)
        {
            $groupTag->group_tag_category= null;
            $groupTag->save();
            return response('', 204);

        }
        return response('Group Tag wasn\'t assigned to group tag category', 500);

    }


}
