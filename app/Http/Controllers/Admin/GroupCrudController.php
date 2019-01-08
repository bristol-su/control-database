<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupRequest as StoreRequest;
use App\Http\Requests\GroupRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class GroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Group');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group');
        $this->crud->setEntityNameStrings('group', 'groups');
        $this->crud->allowAccess('revisions');
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');
        $this->crud->with('revisionHistory');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->addColumn([
            'name' => 'id',
            'label' => 'Group ID',
            'type' => 'number',
            'prefix' => '#'
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Group Name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Group Name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'unioncloud_id',
            'label' => 'UnionCloud',
            'type' => 'unioncloud_group'
        ]);
        $this->crud->addField([
            'name' => 'tags', // the method that defines the relationship in your Model
            'label' => "Tags",
            'type' => 'select2_multiple',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\GroupTag", // foreign key model,
            'pivot' => true,
            'entity' => 'tags'
        ]);
        $this->crud->addField([
            'name' => 'students', // the method that defines the relationship in your Model
            'label' => "Committee",
            'type' => 'select2_multiple',
            'attribute' => 'uc_uid', // foreign key attribute that is shown to user
            'model' => "App\Models\Student", // foreign key model,
            'pivot' => true,
            'entity' => 'students'
        ]);
        // add asterisk for fields that are required in GroupRequest
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
