<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Position;
use App\Models\Role;
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

        if ($student->save()) {
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
     * @return Student
     */
    public function delete(Student $student)
    {
        try {
            $student->delete();
        } catch (\Exception $e) {
            return response('Student couldn\'t be deleted', 500);
        }

        return $student;
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
        $students = Student::where('uc_uid', $request->input('uc_uid'))->get();

        abort_if(count($students) === 0, 404, 'No students found');

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
        return $student->tags()->with('category')->get();
    }

    public function linkStudentTags(Request $request, Student $student)
    {

        $request->validate([
            'id' => 'required|array',
            'id.*' => 'exists:student_tags,id',
            'data' => 'sometimes|array'
        ]);
        if ($request->has('data')) {
            $attachArray = [];
            for ($i = 0; $i < count($request->input('id')); $i++) {
                $attachArray[$request->input('id')[$i]] = ['data' => (isset($request->input('data')[$i]) ? $request->input('data')[$i] : null)];
            }
            $student->tags()->attach($attachArray);
        } else {
            $student->tags()->attach($request->input('id'));
        }

        $studentTags = StudentTag::find($request->input('id'))->each(function ($studentTag) {
            return array_flip(array_map(function ($u) {
                return 'student_tags_' . $u;
            }, array_flip($studentTag->only(['id', 'name', 'description']))));
        });

        return array_merge(
            array_flip(array_map(function ($u) {
                return 'student_' . $u;
            }, array_flip($student->only(['id', 'uc_uid'])))),
            ["student_tags" => $studentTags]
        );
    }

    public function deleteStudentTags(Request $request, Student $student, StudentTag $studentTag)
    {

        if ($student->tags()->detach($studentTag)) {
            return array_merge(
                array_flip(array_map(function ($u) {
                    return 'tag_' . $u;
                }, array_flip($studentTag->only(['id', 'name', 'description'])))),
                array_flip(array_map(function ($u) {
                    return 'student_' . $u;
                }, array_flip($student->only(['id', 'uc_uid']))))
            );
        }
        return response('Student couldn\'t be detached', 500);
    }

    public function deleteStudentTagsWithRelationship(Request $request, Student $student, StudentTag $studentTag)
    {

        if ($student->tags()->wherePivot('data', '=', json_encode($request->toArray()))->detach($studentTag)) {

            return array_merge(
                array_flip(array_map(function ($u) {
                    return 'tag_' . $u;
                }, array_flip($studentTag->only(['id', 'name', 'description'])))),
                array_flip(array_map(function ($u) {
                    return 'student_' . $u;
                }, array_flip($student->only(['id', 'uc_uid']))))
            );
        }

        return response('Student couldn\'t be detached.', 500);
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

        $student->groups()->attach($request->input('id'));

        $groups = Group::find($request->input('id'))->each(function ($group) {
            return array_flip(array_map(function ($u) {
                return 'group_' . $u;
            }, array_flip($group->only(['id', 'name', 'unioncloud_id', 'email']))));
        });

        return array_merge(
            array_flip(array_map(function ($u) {
                return 'student_' . $u;
            }, array_flip($student->only(['id', 'uc_uid'])))),
            ["groups" => $groups]
        );
    }

    public function deleteGroups(Student $student, Group $group)
    {

        if ($student->groups()->detach($group)) {
            return array_merge(
                array_flip(array_map(function ($u) {
                    return 'group_' . $u;
                }, array_flip($group->only(['id', 'name', 'unioncloud_id', 'email'])))),
                array_flip(array_map(function ($u) {
                    return 'student_' . $u;
                }, array_flip($student->only(['id', 'uc_uid']))))
            );
        }
        return response('Group couldn\'t be removed from the student', 500);
    }

    /*
    |--------------------------------------------------------------------------
    | Student -> PositionStudentGroups Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to Many relationship between students and PositionStudentGroups
    |
    */


    public function getRoles(Student $student)
    {
        return $student->roles()->where('deleted_at', null)->with(['group', 'position'])->get();
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
    public function linkRoles(Request $request, Student $student)
    {

        $request->validate([
            'data' => 'required|array',
            'data.*.position_id' => 'exists:positions,id',
            'data.*.group_id' => 'exists:groups,id'
        ]);

        $positionIds = [];
        foreach ($request->input('data') as $data) {
            $role = new Role([
                'group_id' => $data['group_id'],
                'position_id' => $data['position_id'],
            ]);
            $student->roles()->save($role);
            $positionIds[] = $data['position_id'];
        }

        $positions = Position::find($positionIds)->each(function ($position) {
            return array_flip(array_map(function ($u) {
                return 'position_' . $u;
            }, array_flip($position->only(['id', 'name', 'description']))));
        });

        return array_merge(
            array_flip(array_map(function ($u) {
                return 'student_' . $u;
            }, array_flip($student->only(['id', 'uc_uid'])))),
            ["positions" => $positions]
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
    public function deleteRoles(Request $request, Student $student, Position $position)
    {

        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $groupId = (int)$request->input('group_id');

        $roles = $student->roles()->where([
            'position_id' => $position->id,
            'group_id' => $groupId
        ])->get()->first();

        if ($roles !== null && $roles->delete()) {
            return array_merge(
                array_flip(array_map(function ($u) {
                    return 'student_' . $u;
                }, array_flip($student->only(['id', 'uc_uid'])))),
                ["positions" => $roles]
            );
        }

        return response('Student couldn\'t be removed from the position', 500);

    }
}
