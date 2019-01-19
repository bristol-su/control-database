<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTag;
use App\Models\StudentTagCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StudentTagAPIController extends Controller
{

    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Student Tag Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */

    /**
     * Get all studentTags registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $studentTags = StudentTag::all();

        return $studentTags;
    }

    /**
     * Get an studentTag by ID. Route model binding will pass the studentTag
     *
     * @param StudentTag $studentTag
     *
     * @return StudentTag
     */
    public function get(StudentTag $studentTag)
    {
        return $studentTag;
    }

    /**
     * Create an studentTag
     *
     * @param Request $request
     *
     * @return StudentTag
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'description' => 'required|min:3|max:65535',
            'reference' => 'required|min:1|max:255'
        ]);

        $studentTag = new StudentTag($request->only([
            'name',
            'description',
            'reference'
        ]));

        if($studentTag->save())
        {
            return $studentTag;
        }
        return response()->json([
            'error' => 'StudentTag not saved'
        ], 500);
    }

    /**
     * Update an studentTag.
     *
     * @param Request $request
     * @param StudentTag $studentTag
     *
     * @return StudentTag
     */
    public function update(Request $request, StudentTag $studentTag)
    {
        $request->validate([
            'name' => 'sometimes|min:1|max:255',
            'description' => 'sometimes|min:3|max:65535',
            'reference' => 'sometimes|min:1|max:255'
        ]);

        $studentTag->fill($request->input());

        $studentTag->save();
        return $studentTag;
    }

    /**
     * Delete an studentTag
     *
     * @param StudentTag $studentTag
     *
     * @return Response
     */
    public function delete(StudentTag $studentTag)
    {
        try {
            $studentTag->delete();
        } catch (\Exception $e)
        {
            return response('StudentTag couldn\'t be deleted', 500);
        }

        return response('', 204);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Tag -> Student Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between student tag and students
    |
    */

    public function getStudents(StudentTag $studentTag)
    {
        return $studentTag->students;
    }

    public function linkStudents(Request $request, StudentTag $studentTag)
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:students,id',
            'data' => 'sometimes|array'
        ]);
        if($request->has('data'))
        {
            $attachArray = [];
            for($i=0;$i<count($request->input('id'));$i++)
            {
                $attachArray[$request->input('id')[$i]] = ['data' => (isset($request->input('data')[$i])?$request->input('data')[$i]:null)];
            }
            $studentTag->students()->attach($attachArray);
        } else
        {
            $studentTag->students()->attach( $request->input('id'));
        }

        return response('', 204);
    }

    public function deleteStudents(Request $request, StudentTag $studentTag, Student $student)
    {

        if($studentTag->students()->detach( $student ))
        {
            return response('', 204);
        }
        return response('Student couldn\'t be detached', 500);

    }
    
    /*
    |--------------------------------------------------------------------------
    | Student Tag -> Student Tag Category Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to One relationship between Student tag and Student tag categories
    |
    */

    public function getStudentTagCategory(StudentTag $studentTag)
    {
        return $studentTag->category;
    }

    public function linkStudentTagCategory(Request $request, StudentTag $studentTag)
    {
        $request->validate([
            'id' => 'required|exists:student_tag_categories'
        ]);

        $studentTag->student_tag_category = (int) $request->input('id');
        $studentTag->save();

        return $studentTag;
    }

}
