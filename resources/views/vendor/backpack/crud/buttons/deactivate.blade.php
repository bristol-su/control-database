@if ($crud->hasAccess('deactivate'))
	<a href="javascript:void(0)" onclick="deactivateEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default" data-button-type="deactivate"><i class="fa fa-trash"></i>Deactivate</a>
@endif

<script>
	if (typeof deactivateEntry != 'function') {
	  $("[data-button-type=deactivate]").unbind('click');

	  function deactivateEntry(button) {
	      // ask for confirmation before deleting an item
	      // e.preventDefault();
	      var button = $(button);
	      var route = button.attr('data-route');
	      var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

	      if (confirm('Are you sure you want to deactivate this item?') === true) {
	          $.ajax({
	              url: route,
	              type: 'DELETE',
	              success: function(result) {
	                  // Show an alert with the result
	                  new PNotify({
	                      title: "Item Deactivated",
	                      text: "The item has been deactivated successfully.",
	                      type: "success"
	                  });

	                  // Hide the modal, if any
	                  $('.modal').modal('hide');

	                  // Remove the details row, if it is open
	                  if (row.hasClass("shown")) {
	                      row.next().remove();
	                  }

	                  // Remove the row from the datatable
	                  row.remove();
	              },
	              error: function(result) {
	                  // Show an alert with the result
	                  new PNotify({
	                      title: "Not deactivated",
	                      text: "An error occured trying to deactivate the selected item",
	                      type: "warning"
	                  });
	              }
	          });
	      } else {
	      	  // Show an alert telling the user we don't know what went wrong
	          new PNotify({
	              title: "Not deactivated",
	              text: "The selected item wasn't deactivated",
	              type: "info"
	          });
	      }
      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>