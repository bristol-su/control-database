<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupTagCategoryRequest as StoreRequest;
use App\Http\Requests\GroupTagCategoryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class Group_tag_categoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupTagCategoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupTagCategory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group_tag_category');
        $this->crud->setEntityNameStrings('Group Tag Category', 'Group Tag Categories');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Category Name',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Category Description',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'reference',
            'label' => 'Category Reference',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Category Name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Category Description',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'reference',
            'label' => 'Category Reference',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'name' => 'tags', // the method that defines the relationship in your Model
            'label' => "Tags",
            'type' => 'select2_multiple',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\GroupTag", // foreign key model
            'entity' => 'tags'
            // 'select_all' => true, // show Select All and Clear buttons?

        ]);

        // TODO more to see all tags

        // add asterisk for fields that are required in Group_tag_categoryRequest
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
