<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use App\Models\Position;
use App\Models\PositionStudentGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        foreach($psgs as $psg) {
            $psg->group = Group::find($psg->group_id);
            $psg->position = Position::find($psg->position_id);
        }
        return $psgs;
    }

    /**
     * Get an account by ID. Route model binding will pass the account
     *
     * @param PositionStudentGroup $account
     *
     * @return PositionStudentGroup
     */
    public function get(PositionStudentGroup $positionStudentGroup)
    {
        $positionStudentGroup->group = Group::find($positionStudentGroup->group_id);
        $positionStudentGroup->position = Position::find($positionStudentGroup->position_id);
        return $positionStudentGroup;
    }

}