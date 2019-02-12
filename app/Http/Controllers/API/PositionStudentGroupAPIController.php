<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
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
        return PositionStudentGroup::all();
    }

    /**
     * Get an account by ID. Route model binding will pass the account
     *
     * @param Account $account
     *
     * @return Account
     */
    public function get(PositionStudentGroup $positionStudentGroup)
    {
        return $positionStudentGroup;
    }

}