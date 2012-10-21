
Soopfw.behaviors.ratings_admin_manage_ratings = function() {
	// Handle multi actions.
	$("#multi_action").prop("value", "");
	$("#multi_action").off('change').on('change', function() {
		var value = $(this).prop("value");

		// If empty value selected, return.
		if(value == "") {
			return false;
		}

		// Delete entries.
		if(value == "delete") {

			// Display confirm dialog.
			confirm(Soopfw.t("Really want to delete the selected ratings?"), Soopfw.t("delete?"), function() {

				// Iterate through all entries.
				$(".dmySelect").each(function(a, obj) {

					// Check if we checked the checkbox.
					if($(obj).prop("checked") == true) {
						var tmp_arr = $(obj).prop("value").split("|", 2);
						// Delete the server.
						ajax_request("/ratings/delete_rating.ajax",{widget_id: tmp_arr[0], user_identifier: tmp_arr[1]},function() {
							$('*[row="' + $(obj).prop("value") + '"]').remove();
						});
					}
				});

				// Reset multi action.
				$(".dmySelect").prop("checked", false);
				$("#dmySelectAll").prop("checked", false);
			});
		}

		// Reset multi action.
		$("#multi_action").prop("value", "");

	});

	// Handle "select all" checkbox.
	$("#dmySelectAll").off('click').on('click', function() {
		$(".dmySelect").prop("checked", $("#dmySelectAll").prop("checked"));
	});

	// Delete a single entry.
	$(".dmyDelete").off('click').on('click', function() {
		var values = $(this).attr('did');
		var tmp_arr = values.split("|", 2);
		confirm(Soopfw.t("Really want to delete this rating?"), Soopfw.t("delete?"), function() {
			ajax_success("/ratings/delete_rating.ajax",{widget_id: tmp_arr[0], user_identifier: tmp_arr[1]},Soopfw.t("Rating deleted"), Soopfw.t("delete?"),function() {
				$('*[row="' + values + '"]').remove();
			});
		});
	});

};