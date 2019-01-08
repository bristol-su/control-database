<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentRequest as StoreRequest;
use App\Http\Requests\StudentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Student');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student');
        $this->crud->setEntityNameStrings('student', 'students');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumn([
            'name' => 'uc_uid',
            'label' => 'UnionCloud UID',
            'type' => 'number',
            'decimals' => 0,
        ]);

        $this->crud->addField([
            'name' => 'uc_uid',
            'label' => 'UnionCloud UID',
            'type' => 'unioncloud_student_search'
        ]);
        $this->crud->addField([
            'name' => 'tags', // the method that defines the relationship in your Model
            'label' => "Tags",
            'type' => 'select2_multiple',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\StudentTag", // foreign key model,
            'pivot' => true,
            'entity' => 'tags'
        ]);
        $this->crud->addField([
            'name' => 'groups', // the method that defines the relationship in your Model
            'label' => "Committee for",
            'type' => 'select2_multiple',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Group", // foreign key model,
            'pivot' => true,
            'entity' => 'groups'
        ]);

        // add asterisk for fields that are required in StudentRequest
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
