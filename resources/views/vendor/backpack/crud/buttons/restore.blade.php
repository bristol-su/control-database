@if ($crud->hasAccess('restore'))
	<a href="javascript:void(0)" onclick="restoreEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/restore') }}" class="btn btn-xs btn-default" data-button-type="restore"><i class="fa fa-trash"></i> Restore</a>
@endif

<script>
	if (typeof restoreEntry != 'function') {
	  $("[data-button-type=restore]").unbind('click');

	  function restoreEntry(button) {
	      // ask for confirmation before deleting an item
	      // e.preventDefault();
	      var button = $(button);
	      var route = button.attr('data-route');
	      var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

	      if (confirm("Are you sure you want to restore this item?") === true) {
	          $.ajax({
	              url: route,
	              type: 'POST',
	              success: function(result) {
	                  // Show an alert with the result
	                  new PNotify({
	                      title: "Item restored",
	                      text: "The item has been successfully restored",
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
	              	console.log(result);
	                  // Show an alert with the result
	                  new PNotify({
	                      title: "Item not restored",
	                      text: "Something went wrong whilst trying to restore the item",
	                      type: "warning"
	                  });
	              }
	          });
	      } else {
	      	  // Show an alert telling the user we don't know what went wrong
	          new PNotify({
	              title: "Item not restored",
	              text: "Your item wasn't restored.",
	              type: "info"
	          });
	      }
      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>