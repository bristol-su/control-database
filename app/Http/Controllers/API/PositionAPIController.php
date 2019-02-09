<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\PositionStudentGroup;
use App\Models\Student;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PositionAPIController extends Controller
{

    use SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Position Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */


    /**
     * Get all positions registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $positions = Position::all();

        return $positions;
    }

    /**
     * Get an position by ID. Route model binding will pass the position
     *
     * @param Position $position
     *
     * @return Position
     */
    public function get(Position $position)
    {
        return $position;
    }

    /**
     * Create an position
     *
     * @param Request $request
     *
     * @return Position
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|min:3|unique:positions,name',
            'description' => 'required|max:255'
        ]);

        $position = new Position($request->only([
            'name',
            'description'
        ]));

        if($position->save())
        {
            return $position;
        }
        return response()->json([
            'error' => 'Position not saved'
        ], 500);
    }

    /**
     * Update an position.
     *
     * @param Request $request
     * @param Position $position
     *
     * @return Position
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'sometimes|max:255|min:3|',
            'description' => 'required|max:255'
        ]);

        $position->fill($request->input());

        $position->save();

        return $position;
    }

    /**
     * Delete an position
     *
     * @param Position $position
     *
     * @return Position
     */
    public function delete(Position $position)
    {
        try {
            $position->delete();
        } catch (\Exception $e)
        {
            return response('Position couldn\'t be deleted', 500);
        }
        return $position;
    }


    /*
    |--------------------------------------------------------------------------
    | Position -> Student Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between positions and students
    |
    */

    public function getStudents(Position $position)
    {
        return $position->positionStudentGroups;
    }

    /**
     * Pass in the position in the URL, and the students and their groups as follows:
     * data => [
     *  0 => [
     *      'student_id' => 5,
     *      'group_id' => 10
     *  ],
     *  ...
     * ]
     *
     * @param Request $request
     * @param Position $position
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function linkStudents(Request $request, Position $position)
    {

        $request->validate([
            'data' => 'required|array',
            'data.*.student_id' => 'exists:students,id',
            'data.*.group_id' => 'exists:groups,id'
        ]);

        $studentIds = [];
        foreach($request->input('data') as $data)
        {
            $positionStudentGroup = new PositionStudentGroup([
                'group_id' => $data['group_id'],
                'student_id' => $data['student_id'],
            ]);
            $position->positionStudentGroups()->save( $positionStudentGroup );
            $studentIds[] = $data['student_id'];
        }

        $students = Student::find($studentIds)->each(function($student) {
            return array_flip(array_map(function($u){ return 'student_'.$u; }, array_flip($student->only(['id', 'uc_uid']))));
        });

        return array_merge(
            array_flip(array_map(function($u){ return 'position_'.$u; }, array_flip($position->only(['id', 'name', 'description'])))),
            ["students" => $students]
        );
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
    public function deleteStudents(Request $request, Position $position, Student $student)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $groupId = (int) $request->input('group_id');

        $positionStudentGroups = $position->positionStudentGroups()->where([
            'student_id' => $student->id,
            'group_id' => $groupId
        ])->get()->first();

        if( $positionStudentGroups !== null && $positionStudentGroups->delete())
        {
            return array_merge(
                array_flip(array_map(function($u){ return 'position_'.$u; }, array_flip($position->only(['id', 'name', 'description'])))),
                ["students" => $student]
            );
        }

        return response('Students couldn\'t be removed from the position', 500);
    }
}
