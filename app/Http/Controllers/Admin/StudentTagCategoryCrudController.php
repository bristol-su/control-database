<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\StudentTagCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentTagCategoryRequest as StoreRequest;
use App\Http\Requests\StudentTagCategoryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class Student_tag_categoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentTagCategoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentTagCategory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student_tag_category');
        $this->crud->setEntityNameStrings('Student Tag Category', 'Student Tag Categories');

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



        // add asterisk for fields that are required in Student_tag_categoryRequest
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

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('deactivate');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        return $this->crud->delete($id);
    }

    public function delete(StudentTagCategory $studentTagCategory)
    {
        return (string) $studentTagCategory->forceDelete();
    }

    public function restore(StudentTagCategory $studentTagCategory)
    {
        return (string) $studentTagCategory->restore();
    }
}
