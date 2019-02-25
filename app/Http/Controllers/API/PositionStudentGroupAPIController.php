<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Position;
use App\Models\PositionStudentGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PositionStudentGroupAPIController extends Controller
{

    use SoftDeletes;


    // TODO For all api controllers, set up ordering for get commands, and return something useful.


    /*
    |--------------------------------------------------------------------------
    | Account Resource API Methods
    |--------------------------------------------------------------------------
    |
    | Enable the standard CRUD controller endpoints for this resource
    |
    */


    /**
     * Get all accounts registered with the union
     *
     * @return Collection
     */
    public function getAll()
    {
        $psgs = PositionStudentGroup::all();
        foreach ($psgs as $psg) {
            $psg->group = Group::find($psg->group_id);
            $psg->position = Position::find($psg->position_id);
        }
        return $psgs;
    }

    /**
     * Get an account by ID. Route model binding will pass the account
     *
     * @param PositionStudentGroup $positionStudentGroup
     *
     * @return PositionStudentGroup
     */
    public function get(PositionStudentGroup $positionStudentGroup)
    {
        $positionStudentGroup->group = Group::find($positionStudentGroup->group_id);
        $positionStudentGroup->position = Position::find($positionStudentGroup->position_id);
        return $positionStudentGroup;
    }

    /**
     * Create an position student group
     *
     * @param Request $request
     *
     * @return PositionStudentGroup
     */
    public function create(Request $request)
    {
        $request->validate([
            'student_id' => 'exists:students,id',
            'position_id' => 'exists:positions,id',
            'position_name' => 'sometimes|string',
            'group_id' => 'exists:groups,id',
        ]);

        $positionStudentGroup = new PositionStudentGroup($request->only([
            'student_id',
            'position_id',
            'position_name',
            'group_id',
        ]));

        if ($positionStudentGroup->save()) {
            return $positionStudentGroup;
        }
        return response()->json([
            'error' => 'Committee Role not saved'
        ], 500);
    }

    /**
     * Update an position student group
     *
     * @param Request $request
     *
     * @return PositionStudentGroup
     */
    public function update(PositionStudentGroup $positionStudentGroup, Request $request)
    {
        $request->validate([
            'student_id' => 'exists:students,id',
            'position_id' => 'exists:positions,id',
            'position_name' => 'sometimes|string',
            'group_id' => 'exists:groups,id',
        ]);

        $positionStudentGroup->fill($request->input());

        if ($positionStudentGroup->save()) {
            return $positionStudentGroup;
        }
        return response()->json([
            'error' => 'Committee Role not saved'
        ], 500);
    }

}