<?php

/**
 * Stores a rating.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 */
class RatingsObj extends AbstractDataManagment
{
	/**
	 * Define constances.
	 */
	const TABLE = "ratings";

	/**
	 * Construct.
	 *
	 * @param string $widget_id
	 *   the unique widget id. (optional, default = "")
	 * @param boolean $force_db
	 *   if we want to force to load the data from the database. (optional, default = false)
	 */
	public function __construct($widget_id = "", $force_db = false) {
		parent::__construct();

		$this->db_struct = new DbStruct(self::TABLE);

		$this->db_struct->set_cache(true);
		$this->db_struct->add_reference_key(array("widget_id"));
		
		$this->db_struct->add_field("widget_id", t("widget id"), PDT_STRING, '', '32');
		$this->db_struct->add_field("rating_count", t("The ratings count"), PDT_INT, 0, 'UNSIGNED');
		$this->db_struct->add_field("rating_sum", t("The ratings sum"), PDT_INT, 0, 'UNSIGNED');

		$this->set_default_fields();

		if (!empty($widget_id)) {
			if (!$this->load(array($widget_id), $force_db)) {
				return false;
			}
		}
	}

	/**
	 * Returns the current rating.
	 *
	 * @param boolean $rounded
	 *   Wether to get the raw or the rounded number.
	 *   If we want to round, we get a decimal without floating point values. (optional, default = false)
	 *
	 * @return float The current rating.
	 */
	public function get_rating($rounded = false) {
		if ($this->load_success() === false || $this->rating_sum <= 0 || $this->rating_count <= 0) {
			return 0;
		}
		$rating = ($this->rating_sum / $this->rating_count);
		if ($rounded === true) {
			$rating = round($rating);
		}
		return $rating;
	}

	/**
	 * Returns if the current user already has rated.
	 *
	 * @return boolean Returns true if the user already rated, else false.
	 *   If the rating object is currently not loaded it will return also false.
	 */
	public function has_already_rated() {
		if ($this->load_success() === false) {
			return false;
		}
		$log = new RatingsLogObj($this->widget_id, NetTools::get_user_identification());
		return $log->load_success();
	}

	/**
	 * Returns the rating which the user has choosen.
	 *
	 * @return int The user rating.
	 */
	public function get_user_rating() {
		$log = new RatingsLogObj($this->widget_id, NetTools::get_user_identification());
		return $log->rating;
	}

	/**
	 * Insert the current data.
	 *
	 * It will also try save a log entry (The rate time).
	 *
	 * @param boolean $ignore
	 *   Don't throw an error if data is already there. (optional, default=false)
	 *
	 * @return boolean true on success, else false.
	 */
	public function insert($ignore = false) {
		if (parent::insert($ignore)) {
			$rating_log = new RatingsLogObj($this->widget_id, NetTools::get_user_identification());
			$rating_log->date = TIME_NOW;
			return $rating_log->save();
		}
		return false;
	}

	/**
	 * Save the given data.
	 *
	 * It will also try save a log entry (The rate time).
	 *
	 * @param boolean $save_if_unchanged
	 *   Save this object even if no changes to it's values were made. (optional, default = false)
	 *
	 * @return boolean true on success, else false.
	 */
	public function save($save_if_unchanged = false) {
		if (parent::save($save_if_unchanged)) {
			$rating_log = new RatingsLogObj($this->widget_id, NetTools::get_user_identification());
			$rating_log->date = TIME_NOW;
			return $rating_log->save();
		}
		return false;
	}

}

