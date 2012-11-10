<?php

/**
 * Stores the user rating to prevent double ratings.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 */
class RatingsLogObj extends AbstractDataManagement
{
	/**
	 * Define constances
	 */
	const TABLE = "ratings_log";

	/**
	 * Construct
	 *
	 * @param string $widget_id
	 *   the unique widget id (optional, default = "")
	 * @param
	 * @param boolean $force_db
	 *   if we want to force to load the data from the database (optional, default = false)
	 */
	public function __construct($widget_id = "", $user_identifier = '', $force_db = false) {
		parent::__construct();

		$this->db_struct = new DbStruct(self::TABLE);

		$this->db_struct->set_cache(true);
		$this->db_struct->add_reference_key(array("widget_id", "user_identifier"));

		$this->db_struct->add_field("widget_id", t("Widget id"), PDT_STRING, '', '32');
		$this->db_struct->add_field("user_identifier", t("The user identification"), PDT_STRING, '');
		$this->db_struct->add_field("rating", t("The posted rating for this user and widget"), PDT_INT, 0, 'UNSIGNED');
		$this->db_struct->add_field("date", t("The time when the user rated"), PDT_DATETIME, date(DB_DATETIME, TIME_NOW));

		$this->set_default_fields();

		if (!empty($widget_id) && !empty($user_identifier)) {
			if (!$this->load(array($widget_id, $user_identifier), $force_db)) {
				return false;
			}
		}
	}
}

