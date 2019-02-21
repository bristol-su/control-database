<?php

namespace App\Http\Controllers\Admin;

use App\Models\PositionStudentGroup;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PositionStudentGroupRequest as StoreRequest;
use App\Http\Requests\PositionStudentGroupRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class PositionStudentGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PositionStudentGroupCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PositionStudentGroup');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/position_student_group');
        $this->crud->setEntityNameStrings('Committee Member', 'Committee Members');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');
        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumn([
            'name' => "group_id",
            'label' => "Group", // Table column heading
            'type' => "select",
            'entity' => 'group',
            'attribute' => 'name',
            'model' => 'App\Models\Group',
        ]);

        $this->crud->addColumn([
            'name' => "position_id",
            'label' => "Position Type", // Table column heading
            'type' => "select",
            'entity' => 'position',
            'attribute' => 'name',
            'model' => 'App\Models\Position',
        ]);

        $this->crud->addColumn([
            'name' => 'position_name',
            'label' => 'Position Name',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => "student_id",
            'label' => "Student", // Table column heading
            'type' => "select",
            'entity' => 'student',
            'attribute' => 'uc_uid',
            'model' => 'App\Models\Student',
        ]);


        $this->crud->addField([  // Select
            'label' => "Group",
            'type' => 'select',
            'name' => 'group_id', // the db column for the foreign key
            'entity' => 'group', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Group",
        ]);


        $this->crud->addField([  // Select
            'label' => "Student",
            'type' => 'select',
            'name' => 'student_id', // the db column for the foreign key
            'entity' => 'student', // the method that defines the relationship in your Model
            'attribute' => 'uc_uid', // foreign key attribute that is shown to user
            'model' => "App\Models\Student",
        ]);

        $this->crud->addField([
            'name' => 'position_name',
            'label' => 'Position Name',
            'type' => 'text'
        ]);

        $this->crud->addField([  // Select
            'label' => "Position",
            'type' => 'select',
            'name' => 'position_id', // the db column for the foreign key
            'entity' => 'position', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Position",
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




        // add asterisk for fields that are required in PositionStudentGroupCrudControllerRequest
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
    
    public function delete(PositionStudentGroup $positionStudentGroup)
    {
        return (string) $positionStudentGroup->forceDelete();
    }

    public function restore(PositionStudentGroup $positionStudentGroup)
    {
        if(! $positionStudentGroup->restore())
        {
            return response('Committee Member not restored', 500);
        }
        return response('', 200);
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('deactivate');
        $this->crud->setOperation('deactivate');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        return (string) $this->crud->delete($id);
    }
}
