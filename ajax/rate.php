<?php
/**
 * Provides an ajax request to handle ratings.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 * @category Module.ratings
 */
class AjaxRatingsRate extends AjaxModul {

	/**
	 * This function will be executed after ajax file initializing
	 */
	public function run() {

		$params = new ParamStruct();
		$params->add_required_param('widget_id', PDT_STRING);
		$params->add_required_param('rating', PDT_INT);

		$params->fill();

		if (!$params->is_valid()) {
			throw new SoopfwWrongParameterException(t('Invalid request.'));
		}

		$rating = new RatingsObj($params->widget_id);

		// Validate the widget configuration.
		$config_array = $this->session->get('ratings_widget_configuration_' . $params->widget_id);
		if (empty($config_array)) {
			throw new SoopfwNoPermissionException(t('Security error, could not verify the rating.'));
		}

		// Check if the user is allowed to rate.
		if (empty($config_array['can_rate'])) {
			throw new SoopfwNoPermissionException(t('You are not allowed to rate this element.'));
		}

		// Check if the provided params are acceptable with our configuration.
		if ($params->rating < 0 || $params->rating > (int)$config_array['max_stars']) {
			throw new SoopfwNoPermissionException(t('You have tried to rate a higher star-count as it is configured or a negative value. The action has been logged, your rate has not been accepted.'));
		}

		// Try to load a log entry which would show us that the user already rated this item.
		$log = new RatingsLogObj($params->widget_id, NetTools::get_user_identification());

		// Check if we have already rated, if so we remove the previous rating value first.
		if ($log->load_success()) {
			$rating->rating_sum -= $log->rating;
		}
		else {
			// If we have not rated before we increase the rate count.
			$rating->rating_count = $rating->rating_count + 1;
		}

		// Increment the new rating value.
		$rating->rating_sum += $params->rating;
		$rating->transaction_auto_begin();
		if ($rating->save_or_insert()) {

			$log->rating = $params->rating;
			$log->time = TIME_NOW;

			if ($log->load_success()) {
				$desc = t('Your rating was updated.');
			}
			else {
				$desc = t('Your rating was stored.');
			}
			if ($log->save_or_insert()) {
				$rating->transaction_auto_commit();
				/**
				 * Provides hook: ratings_rate
				 *
				 * Allow other modules to do things if a user rates something.
				 *
				 * @param RatingsObj
				 *   the rating object.
				 * @param int $rating
				 *   The provided rating.				 *
				 */
				$this->core->hook('ratings_rate', array(
					'rating_obj' => &$rating,
					'rating' => $params->rating,
				));

				AjaxModul::return_code(AjaxModul::SUCCESS, array(
					'widget_id' => $params->widget_id,
					'current_rating' => $rating->get_rating(),
				), true, $desc);
			}
		}
		$rating->transaction_auto_rollback();
		AjaxModul::return_code(AjaxModul::ERROR_DEFAULT, null, true, t('Could not save the rating.'));
	}
}
