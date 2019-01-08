<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentTagRequest as StoreRequest;
use App\Http\Requests\StudentTagRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class Student_tagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentTagCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentTag');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student_tag');
        $this->crud->setEntityNameStrings('student Tag', 'student Tags');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Tag Name',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Tag Description',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'reference',
            'label' => 'Tag Reference',
            'type' => 'model_function',
            'function_name' => 'getFullReference'
        ]);
        $this->crud->addColumn([
            'name' => 'student_tag_category',
            'label' => 'Tag Category',
            'type' => 'model_function',
            'function_name' => 'getCategoryName'
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Tag Name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Tag Description',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'reference',
            'label' => 'Tag Reference',
            'type' => 'text',
            'prefix' => 'category_tag.'
        ]);
        $this->crud->addField([
            'name' => 'student_tag_category',
            'label' => 'Category',
            'type' => 'select2',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => 'App\Models\StudentTagCategory'
        ]);
        $this->crud->addField([
            'name' => 'students', // the method that defines the relationship in your Model
            'label' => "Students",
            'type' => 'select2_multiple',
            'attribute' => 'uc_uid', // foreign key attribute that is shown to user
            'model' => "App\Models\Student", // foreign key model,
            'pivot' => true,
            'entity' => 'students'
        ]);

        // add asterisk for fields that are required in Student_tagRequest
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
