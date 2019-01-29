<?php

namespace App\Http\Controllers\Admin;

use App\Events\GroupTagged;
use App\Events\GroupUntagged;
use App\Models\Group;
use App\Models\GroupTag;
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
        $this->crud->allowAccess('details_row');
        $this->crud->enableDetailsRow();
        $this->crud->with('revisionHistory');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

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
        $this->crud->addColumn([
            'name' => 'email',
            'label' => 'Group Email',
            'type' => 'email'
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
            'name' => 'email',
            'label' => 'Group Email Address',
            'type' => 'email'
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




        // add asterisk for fields that are required in GroupRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {

        $redirect_location = parent::storeCrud($request);

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // Save the group
        $redirect_location = parent::updateCrud($request);


        return $redirect_location;
    }

    public function delete(Group $group)
    {
        return (string) $group->forceDelete();
    }

    public function restore(Group $group)
    {
        if(! $group->restore())
        {
            return response('Group not restored', 500);
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
