<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentTag;
use App\Models\StudentTagCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StudentTagCategoryAPIController extends Controller
{

    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Student Tag Category Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */

    /**
     * Get all studentTagCategorys registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $studentTagCategorys = StudentTagCategory::all();

        return $studentTagCategorys;
    }

    /**
     * Get an studentTagCategory by ID. Route model binding will pass the studentTagCategory
     *
     * @param StudentTagCategory $studentTagCategory
     *
     * @return StudentTagCategory
     */
    public function get(StudentTagCategory $studentTagCategory)
    {
        return $studentTagCategory;
    }

    /**
     * Create an studentTagCategory
     *
     * @param Request $request
     *
     * @return StudentTagCategory
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'description' => 'required|min:3|max:65535',
            'reference' => 'required|min:1|max:255'
        ]);

        $studentTagCategory = new StudentTagCategory($request->only([
            'name',
            'description',
            'reference'
        ]));

        if($studentTagCategory->save())
        {
            return $studentTagCategory;
        }
        return response()->json([
            'error' => 'StudentTagCategory not saved'
        ], 500);
    }

    /**
     * Update an studentTagCategory.
     *
     * @param Request $request
     * @param StudentTagCategory $studentTagCategory
     *
     * @return StudentTagCategory
     */
    public function update(Request $request, StudentTagCategory $studentTagCategory)
    {
        $request->validate([
            'name' => 'sometimes|min:1|max:255',
            'description' => 'sometimes|min:3|max:65535',
            'reference' => 'sometimes|min:1|max:255'
        ]);

        $studentTagCategory->fill($request->input());

        $studentTagCategory->save();
        return $studentTagCategory;
    }

    /**
     * Delete an studentTagCategory
     *
     * @param StudentTagCategory $studentTagCategory
     *
     * @return Response
     */
    public function delete(StudentTagCategory $studentTagCategory)
    {
        try {
            $studentTagCategory->delete();
        } catch (\Exception $e)
        {
            return response('StudentTagCategory couldn\'t be deleted', 500);
        }

        return response('', 204);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Tag Category -> Student Tag
    |--------------------------------------------------------------------------
    |
    | Enable the One to Many relationship between student tag categories
    | and student tags
    |
    */

    public function getStudentTags(StudentTagCategory $studentTagCategory)
    {
        return $studentTagCategory->tags;
    }

    public function linkStudentTags(Request $request, StudentTagCategory $studentTagCategory)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:student_tags,id'
        ]);

        foreach($request->input('id') as $id)
        {
            $studentTag = StudentTag::find((int) $id);
            $studentTag->student_tag_category = $studentTagCategory->id;
            $studentTag->save();
        }

        return response('', 204);
    }

    public function deleteStudentTags(Request $request, StudentTagCategory $studentTagCategory, StudentTag $studentTag)
    {

        if($studentTag->student_tag_category === $studentTagCategory->id)
        {
            $studentTag->student_tag_category= null;
            $studentTag->save();
            return response('', 204);

        }
        return response('Student Tag wasn\'t assigned to student tag category', 500);

    }
}
