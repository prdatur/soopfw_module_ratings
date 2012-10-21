<?php
/**
 * Provides an ajax request to delete the given rating.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 * @package modules.ratings.ajax
 * @category Module.Ratings
 */
class AjaxRatingsDeleteRating extends AjaxModul {

	/**
	 * This function will be executed after ajax file initializing.
	 */
	public function run() {

		// Initalize param struct.
		$params = new ParamStruct();
		$params->add_required_param("widget_id", PDT_STRING);
		$params->add_required_param("user_identifier", PDT_STRING);

		// Fill the params.
		$params->fill();

		// Parameters are missing.
		if (!$params->is_valid()) {
			throw new SoopfwMissingParameterException();
		}

		// Right missing.
		if (!$this->core->get_right_manager()->has_perm("admin.ratings.delete")) {
			throw new SoopfwNoPermissionException();
		}

		// Load objects.
		$rating_obj = new RatingsObj($params->widget_id);
		$rating_log_entry = new RatingsLogObj($params->widget_id, $params->user_identifier);

		// If provided id is not valid.
		if (!$rating_obj->load_success() || !$rating_log_entry->load_success()) {
			throw new SoopfwWrongParameterException(t('No such rating.'));
		}

		// Remove the rated value from the rating sum and decrease the rate count.
		$rating_obj->rating_count -= 1;
		$rating_obj->rating_sum -= $rating_log_entry->rating;

		// Save the new rating.
		if ($rating_obj->save()) {
			// Delete the log entry.
			if ($rating_log_entry->delete()) {
				AjaxModul::return_code(AjaxModul::SUCCESS);
			}
		}
		AjaxModul::return_code(AjaxModul::ERROR_DEFAULT);
	}
}
