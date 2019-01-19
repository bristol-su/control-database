<?php

namespace App\Http\Controllers\Admin;

use App\Events\GroupTagged;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\GroupTagCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupTagRequest as StoreRequest;
use App\Http\Requests\GroupTagRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class Group_tagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupTagCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupTag');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group_tag');
        $this->crud->setEntityNameStrings('group Tag', 'group Tags');

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
            'name' => 'group_tag_category',
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
            'name' => 'group_tag_category',
            'label' => 'Category',
            'type' => 'select2',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => 'App\Models\GroupTagCategory',
            'pivot' => false,
        ]);
        $this->crud->addField([
            'name' => 'groups', // the method that defines the relationship in your Model
            'label' => "Groups",
            'type' => 'select2_multiple',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Group", // foreign key model
            'pivot' => true,
            'entity' => 'groups'
        ]);

        /*
         * Buttons allowed are:
         *
         * Preview - access 'show'. Always on
         * Edit = access 'update'. Always on
         * Deactivate - access 'deactivate'. On in the non filter
         * Permanently Delete - access 'delete'. On in the trash
         * Restore - access 'restore' on in trash
         * Revisions: access 'revisions' on always
         */
        # Allow buttons which are always available:
        $this->crud->allowAccess('show');
        $this->crud->allowAccess('update');
        $this->crud->allowAccess('revisions');

        # Add all custom buttons.
        $this->crud->addButtonFromView('line', 'deactivate', 'deactivate', 'end');
        $this->crud->addButtonFromView('line', 'delete', 'delete', 'end');
        $this->crud->addButtonFromView('line', 'restore', 'restore', 'end');

        # By default, assume we're not in the trash view.
        $this->crud->allowAccess('deactivate');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('restore');

        # If we are in the trash view, change the buttons
        $this->crud->addFilter([ // simple filter
            'type' => 'simple',
            'name' => 'deactivated',
            'label'=> 'Deactivated'
        ],
            false,
            function() { // if the filter is active
                // Only allow trashed items
                $this->crud->addClause('onlyTrashed');
                $this->crud->denyAccess('deactivate');
                $this->crud->denyAccess('show');
                $this->crud->denyAccess('update');
                $this->crud->denyAccess('revisions');
                $this->crud->allowAccess('delete');
                $this->crud->allowAccess('restore');
            });




        // add asterisk for fields that are required in Group_tagRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Save Group
        $redirect_location = parent::storeCrud($request);

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        // Save tag
        $redirect_location = parent::updateCrud($request);


        return $redirect_location;
    }



    public function delete(GroupTag $groupTag)
    {
        return (string) $groupTag->forceDelete();
    }

    public function restore(GroupTag $groupTag)
    {
        return (string) $groupTag->restore();
    }
}
