<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Position;
use App\Models\PositionStudentGroup;
use App\Rules\IsCommitteeYearRule;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use phpseclib\Crypt\Base;

class PositionStudentGroupAPIController extends Controller
{

    use SoftDeletes;

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
    public function getAll(Request $request)
    {
        $request->validate([
            'year' => ['sometimes', new IsCommitteeYearRule]
        ]);
        $year = ($request->has('year')?$request->input('year'):config('app.committee_year'));
        return PositionStudentGroup::with(['group', 'position'])->where('committee_year', $year)->all();
    }

    /**
     * Get an account by ID. Route model binding will pass the account
     *
     * @param PositionStudentGroup $positionStudentGroup
     *
     * @return PositionStudentGroup
     */
    public function get($positionStudentGroupID)
    {
        return PositionStudentGroup::with(['group', 'position', 'student'])->find($positionStudentGroupID);
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
            'committee_year' => ['sometimes', new IsCommitteeYearRule]
        ]);

        $positionStudentGroup = new PositionStudentGroup($request->only([
            'student_id',
            'position_id',
            'position_name',
            'group_id',
            'committee_year'
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
            'student_id' => 'sometimes|exists:students,id',
            'position_id' => 'sometimes|exists:positions,id',
            'position_name' => 'sometimes|string',
            'group_id' => 'sometimes|exists:groups,id',
            'committee_year' => ['sometimes', new IsCommitteeYearRule]
        ]);

        $positionStudentGroup->fill($request->input());

        if ($positionStudentGroup->save()) {
            return $positionStudentGroup;
        }
        return response()->json([
            'error' => 'Committee Role not saved'
        ], 500);
    }

    /**
     * Delete a position student group
     *
     * @param Request $request
     *
     * @return PositionStudentGroup
     */
    public function delete(Request $request, $positionStudentGroupID)
    {
        abort_if(!PositionStudentGroup::destroy($positionStudentGroupID), 500, 'Could not delete committee role');

        return response('', 200);
    }


}
