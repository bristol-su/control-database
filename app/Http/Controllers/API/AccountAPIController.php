<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class AccountAPIController extends Controller
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
        $accounts = Account::all();

        return $accounts;
    }

    /**
     * Get an account by ID. Route model binding will pass the account
     *
     * @param Account $account
     *
     * @return Account
     */
    public function get(Account $account)
    {
        return $account;
    }

    /**
     * Create an account
     *
     * @param Request $request
     *
     * @return Account
     */
    public function create(Request $request)
    {
        $request->validate([
            'description' => 'required|max:65535|min:3',
            'is_department_code' => 'required|boolean',
            'code' => 'required|size:3'
        ]);

        $account = new Account($request->only([
            'description',
            'is_department_code',
            'code'
        ]));

        if($account->save())
        {
            return $account;
        }
        return response()->json([
            'error' => 'Account not saved'
        ], 500);
    }

    /**
     * Update an account.
     *
     * @param Request $request
     * @param Account $account
     *
     * @return Account
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'description' => 'sometimes|max:65535|min:3',
            'is_department_code' => 'sometimes|boolean',
            'code' => 'sometimes|size:3'
        ]);

        $account->fill($request->input());

        $account->save();

        return $account;
    }

    /**
     * Delete an account
     *
     * @param Account $account
     *
     * @return Account
     */
    public function delete(Account $account)
    {
        try {
            $account->delete();
        } catch (\Exception $e)
        {
            return response('Account couldn\'t be deleted', 500);
        }

        return $account;
    }


    /*
    |--------------------------------------------------------------------------
    | Account -> Group Relationships
    |--------------------------------------------------------------------------
    |
    | Enable the Many to One relationship between accounts and groups
    |
    */

    public function getGroup(Account $account)
    {
        return $account->group;
    }

    public function linkGroup(Request $request, Account $account)
    {
        $request->validate([
            'id' => 'required|exists:groups|nullable'
        ]);

        $group = Group::findOrFail((int) $request->input('id'));
        $account->group_id = $group->id;
        $account->save();

        // TODO Put this in a trait or helper function
        return array_merge(
            array_flip(array_map(function($u){ return 'group_'.$u; }, array_flip($group->only(['id', 'name', 'unioncloud_id', 'email'])))),
            array_flip(array_map(function($u){ return 'account_'.$u; }, array_flip($account->only(['id', 'description', 'is_department_code', 'code']))))
        );
    }

}
