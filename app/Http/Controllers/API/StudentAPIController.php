<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Position;
use App\Models\Student;
use App\Models\StudentTag;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StudentAPIController extends Controller
{

    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Student Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */

    /**
     * Get all students registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $students = Student::all();

        return $students;
    }

    /**
     * Get an student by ID. Route model binding will pass the student
     *
     * @param Student $student
     *
     * @return Student
     */
    public function get(Student $student)
    {
        return $student;
    }

    /**
     * Create an student
     *
     * @param Request $request
     *
     * @return Student
     */
    public function create(Request $request)
    {
        $request->validate([
            'uc_uid' => 'required'
        ]);

        $student = new Student($request->only([
            'uc_uid'
        ]));

        if($student->save())
        {
            return $student;
        }
        return response()->json([
            'error' => 'Student not saved'
        ], 500);
    }

    /**
     * Update an student.
     *
     * @param Request $request
     * @param Student $student
     *
     * @return Student
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'uc_uid' => 'sometimes'
        ]);

        $student->fill($request->input());

        $student->save();

        return $student;
    }

    /**
     * Delete an student
     *
     * @param Student $student
     *
     * @return Response
     */
    public function delete(Student $student)
    {
        try {
            $student->delete();
        } catch (\Exception $e)
        {
            return response('Student couldn\'t be deleted', 500);
        }

        return response('', 204);
    }

    /**
     * Search for a Student
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function search(Request $request)
    {
        $students = Student::where($request->only(['uc_uid']))->get();

        return $students;
    }


    /*
    |--------------------------------------------------------------------------
    | Student -> Student Tag Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between students and student tags
    |
    */

    public function getStudentTags(Student $student)
    {
        return $student->tags;
    }

    public function linkStudentTags(Request $request, Student $student)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:student_tags,id',
            'data' => 'sometimes|array'
        ]);
        if($request->has('data'))
        {
            $attachArray = [];
            for($i=0;$i<count($request->input('id'));$i++)
            {
                $attachArray[$request->input('id')[$i]] = ['data' => (isset($request->input('data')[$i])?$request->input('data')[$i]:null)];
            }
            $student->tags()->attach($attachArray);
        } else
        {
            $student->tags()->attach( $request->input('id'));
        }

        return response('', 204);
    }

    public function deleteStudentTags(Request $request, Student $student, StudentTag $studentTag)
    {

        if($student->tags()->detach( $studentTag ))
        {
            return response('', 204);
        }
        return response('Student couldn\'t be detached', 500);
    }


    /*
    |--------------------------------------------------------------------------
    | Student -> Group Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between students and groups
    |
    */
    
    public function getGroups(Student $student)
    {
        return $student->groups;
    }

    public function linkGroups(Request $request, Student $student)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:groups,id'
        ]);

        $student->groups()->attach( $request->input('id') );

        return response('', 204);
    }

    public function deleteGroups(Student $student, Group $group)
    {

        if($student->groups()->detach( $group ))
        {
            return response('', 204);
        }
        return response('Group couldn\'t be removed from the student', 500);
    }

    /*
    |--------------------------------------------------------------------------
    | Student -> Position Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between students and positions
    |
    */


    public function getPositions(Student $student)
    {
        return $student->positions;
    }

    /**
     * Pass in the student in the URL, and the positions and their groups as follows:
     * data => [
     *  0 => [
     *      'position_id' => 5,
     *      'group_id' => 10
     *  ],
     *  ...
     * ]
     *
     * @param Request $request
     * @param Student $student
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function linkPositions(Request $request, Student $student)
    {

        $request->validate([
            'data' => 'required|array',
            'data.*.position_id' => 'exists:positions,id',
            'data.*.group_id' => 'exists:groups,id'
        ]);

        foreach($request->input('data') as $data)
        {
            $student->positions()->attach( $data['position_id'], ['group_id' => $data['group_id'] ] );
        }

        return response('', 204);

    }

    /**
     * The position to remove from the student is passed in the url, along with the student to remove
     * it from. The Group ID is also passed in the body. To have a position removed, the relevent
     * positions will be detached IF they have the right Group ID.
     *
     * @param Request $request
     * @param Position $position
     * @param Student $student
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function deletePositions(Request $request, Student $student, Position $position)
    {

        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $groupId = (int) $request->input('group_id');
        $positions = $student->positions()->where('position_id', $position->id)->get()->filter(function($position) use ($groupId) {
            return $position->pivot->group_id === $groupId;
        });

        if( $student->positions()->wherePivot('group_id', $groupId)->detach($positions) || count($positions) === 0)
        {
            return response('', 204);
        }

        return response('Student couldn\'t be removed from the position', 500);

    }

    
    
}
