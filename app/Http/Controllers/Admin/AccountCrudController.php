<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AccountRequest as StoreRequest;
use App\Http\Requests\AccountRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AccountCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Account');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/account');
        $this->crud->setEntityNameStrings('account', 'accounts');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $this->crud->addColumn([
            'name' => 'code',
            'label' => 'Account Code',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => "model_function",
            'function_name' => 'getGroupName',
        ]);

        $this->crud->addField([
            'name' => 'code',
            'label' => 'Account Code',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => 'select2',
            'entity' => 'group',
            'attribute' => 'name',
            'model' => 'App\Models\Group',
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            })
        ]);
        $this->crud->addField([
            'name' => 'is_department_code',
            'label' => 'Primary Account',
            'type' => 'checkbox'
        ]);


        // add asterisk for fields that are required in AccountRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
