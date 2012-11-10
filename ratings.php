<?php
/**
 * Ratings module.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 */
class Ratings extends ActionModul implements Widget {

	/**
	 * Define provided widgets.
	 */
	const WIDGET_RATEINGS = 'ratings';

	/**
	 * Define config constances.
	 */
	const CONFIG_DEFAULT_ALLOW_ONLY_REGISTERED_USER = 'default_only_allow_registered_user';
	const CONFIG_DEFAULT_MAX_STARS = 'default_max_stars';
	const CONFIG_DEFAULT_STAR_SIZE = 'default_star_size';
	const CONFIG_DEFAULT_TOOLTIP_ENABLED = 'default_tooltip_enabled';
	const CONFIG_DEFAULT_TOOLTIP_TEMPLATE = 'default_tooltip_template';
	const CONFIG_DEFAULT_TOOLTIP_POSITION = 'default_tooltip_position';
	const CONFIG_DEFAULT_TOOLTIP_TIP_POSITION = 'default_tooltip_tip_positionr';

	/**
	 * Default method.
	 *
	 * @var string
	 */
	protected $default_methode = self::NO_DEFAULT_METHOD;

	/**
	 * Implements hook: admin_menu
	 *
	 * Returns an array which includes all links and childs for the admin menu.
	 * There are some special categories in which the module can be injected.
	 * The following categories are current supported:
	 *   style, security, content, structure, authentication, system, other
	 *
	 * @return array the menu
	 */
	public function hook_admin_menu() {
		return array(
			AdminMenu::CATEGORY_STRUCTURE => array(
				'#id' => 'soopfw_ratings', //A unique id which will be needed to generate the submenu
				'#title' => t("Rating"), //The main title
				'#link' => "#", // The main link
				'#perm' => 'admin.ratings', //Perm needed to show this entry and subentries.
				'#childs' => array(
					array(
						'#title' => t("Config"), //The sub title
						'#link' => "/admin/ratings/config", // The sub link
						'#perm' => 'admin.ratings.manage', // The sub perm
					),
				)
			)
		);
	}

	/**
	 * Action: config
	 *
	 * Configurate the rating settings.
	 */
	public function config() {
		// Check perms, second parameter is set to true so we get redirected
		// to the login page if we are not logged in.
		if (!$this->right_manager->has_perm('admin.ratings.manage', true)) {
			throw new SoopfwNoPermissionException();
		}

		//Setting up title and description
		$this->title(t("Ratings Config"), t("Here we can configure the main ratings settings"));

		//Configurate the settings form
		$form = new SystemConfigForm($this, "ratings_config");

		// Add a fieldset to "categorize" configuration elements.
		$form->add(new Fieldset('main', t('Default values')));

		$form->add(new YesNoSelectfield(self::CONFIG_DEFAULT_ALLOW_ONLY_REGISTERED_USER, $this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_ALLOW_ONLY_REGISTERED_USER, 'yes'), t('Allow only registered users')));
		$form->add(new Textfield(self::CONFIG_DEFAULT_MAX_STARS, (int)$this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_MAX_STARS, 5), t('Max stars'), t('This determines how much "stars" a user can choose to rate by default')));

		$star_sizes = array(
			24 => 24,
		);
		$form->add(new Selectfield(self::CONFIG_DEFAULT_STAR_SIZE, $star_sizes, (int)$this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_STAR_SIZE, 24), t('Star size'), t('This determines how "big" in px the stars will be.')));

		$form->add(new YesNoSelectfield(self::CONFIG_DEFAULT_TOOLTIP_ENABLED, $this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_TOOLTIP_ENABLED, 'yes'), t('Show help tooltips?')));

		$templates = array(
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_RED => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_RED,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_BLUE => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_BLUE,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_BOOTSTRAP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_BOOTSTRAP,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_CLUETIP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_CLUETIP,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_DARK => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_DARK,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_GREEN => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_GREEN,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_JTOOLS => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_JTOOLS,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_LIGHT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_LIGHT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_PLAIN => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_PLAIN,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_TIPPED => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_TIPPED,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_TIPSY => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_TIPSY,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_YOUTUBE => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_YOUTUBE,
		);
		$form->add(new Selectfield(self::CONFIG_DEFAULT_TOOLTIP_TEMPLATE, $templates, $this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_TOOLTIP_TEMPLATE, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_YOUTUBE), t('Tooltip template')));

		$positions = array(
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_LEFT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_LEFT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_RIGHT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_RIGHT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_BOTTOM => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_BOTTOM,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_TOP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_LEFT_TOP,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_BOTTOM => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_BOTTOM,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_TOP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_RIGHT_TOP,
		);
		$form->add(new Selectfield(self::CONFIG_DEFAULT_TOOLTIP_POSITION, $positions, $this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_TOOLTIP_POSITION, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_CENTER), t('Tooltip position')));

		$tip_positions = array(
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_LEFT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_LEFT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_RIGHT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_TOP_RIGHT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_LEFT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_LEFT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_RIGHT => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_RIGHT,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_BOTTOM => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_BOTTOM,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_TOP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_LEFT_TOP,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_BOTTOM => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_BOTTOM,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_CENTER => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_CENTER,
			RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_TOP => RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_TOP,
		);
		$form->add(new Selectfield(self::CONFIG_DEFAULT_TOOLTIP_TIP_POSITION, $tip_positions, $this->core->get_dbconfig('ratings', self::CONFIG_DEFAULT_TOOLTIP_TIP_POSITION, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_CENTER), t('Tooltip tip position')));

		//Execute the settings form, will assign a submit button and if form is submitted it will save the values
		$form->execute();
	}

	/**
	 * Action: manage_ratings
	 *
	 * Displays all ratings for the given widget id.
	 *
	 * @param string $widget_id
	 *   The widget id.
	 *
	 * @throws SoopfwNoPermissionException
	 * @throws SoopfwWrongParameterException
	 */
	public function manage_ratings($widget_id) {
		if (!$this->right_manager->has_perm('admin.ratings.manage')) {
			throw new SoopfwNoPermissionException();
		}

		// Try to load the rating object.
		$rating_obj = new RatingsObj($widget_id);
		if (!$rating_obj->load_success()) {
			throw new SoopfwWrongParameterException(t('No such object.'));
		}

		// Get all ratings for the widget id.
		$filter = DatabaseFilter::create(RatingsLogObj::TABLE)
			->add_where('widget_id', $widget_id)
			->order_by('date', DatabaseFilter::DESC);
		$entries = $filter->select_all();

		// Provide additional information to each rating.
		foreach ($entries AS &$row) {

			// Build up the user identifcation.
			list($type, $id) = explode(":", $row['user_identifier']);

			// Check the identifier type.
			if ($type === 'ip') {

				// We have an ip.
				$row['user'] = t('Anonymous (@ip)', array('@ip' => $id));

			}
			elseif ($type === 'user_id') {

				// We have an user.
				$user_obj = new UserObj($id);

				// Check if the user exist.
				if ($user_obj->load_success()) {
					$row['user'] = '<a href="/user/edit/' . $id . '">' . $user_obj->username . '</a>';
				}
				else {
					$row['user'] = t('(Not existing user: @id)', array('@id' => $id));
				}

			}
		}
		$this->smarty->assign_by_ref('entries', $entries);
	}

	/**
	 *
	 * Initialize the widget, will also perform form handlings for the widget if needed.
	 * This method must perform all actions what the widget should can do.
	 *
	 * Use only the returned uuid to access the widget because non "word" character will be replaced
	 * to _ (underline)
	 *
	 * @param string $name
	 *   the widget name.
	 * @param string $unique_id
	 *   the unique id for this widget.
	 * @param Configuration $widget_config
	 *   the widget configuration object. (optional, default = null)
	 *
	 * @return mixed the cleaned uuid or null if the widget name is not supported.
	 */
	public function get_widget($name, $unique_id, Configuration $widget_config = null) {

		// Clean the uuid
		$clean_uuid = WidgetHelper::clean_widget_id($unique_id);

		// Initialize default RatingsWidgetConfiguration if nothin is provided or not a Configuration object.
		if (($widget_config instanceof Configuration) === false || $widget_config === null) {
			$widget_config = new RatingsWidgetConfiguration();
		}

		switch ($name) {
			case self::WIDGET_RATEINGS:
				// Setup default configurations.

				// Default allow only registered users.
				if ($widget_config->is_set(RatingsWidgetConfiguration::ALLOW_ONLY_REGISTERED_USER) === false) {
					if ($this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_ALLOW_ONLY_REGISTERED_USER, 'yes') === 'yes') {
						$widget_config->enable(RatingsWidgetConfiguration::ALLOW_ONLY_REGISTERED_USER);
					}
				}

				// Default max stars.
				if ($widget_config->is_set(RatingsWidgetConfiguration::MAX_STARS) === false) {
					$widget_config->set(RatingsWidgetConfiguration::MAX_STARS, (int)$this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_MAX_STARS, 5));
				}

				// Default star size.
				if ($widget_config->is_set(RatingsWidgetConfiguration::STAR_SIZE) === false) {
					$widget_config->set(RatingsWidgetConfiguration::STAR_SIZE, (int)$this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_STAR_SIZE, 24));
				}

				// Default tooltip enabled.
				if ($widget_config->is_set(RatingsWidgetConfiguration::TOOLTIP_ENABLED) === false) {
					if ($this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_TOOLTIP_ENABLED, 'yes') === 'yes') {
						$widget_config->enable(RatingsWidgetConfiguration::TOOLTIP_ENABLED);
					}
				}

				// Default tooltip template.
				if ($widget_config->is_set(RatingsWidgetConfiguration::TOOLTIP_TEMPLATE) === false) {
					$widget_config->set(RatingsWidgetConfiguration::TOOLTIP_TEMPLATE, $this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_TOOLTIP_TEMPLATE, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TEMPLATE_YOUTUBE));
				}

				// Default tooltip position.
				if ($widget_config->is_set(RatingsWidgetConfiguration::TOOLTIP_POSITION) === false) {
					$widget_config->set(RatingsWidgetConfiguration::TOOLTIP_POSITION, $this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_TOOLTIP_POSITION, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_POSITION_TOP_CENTER));
				}

				// Default tooltip tip position.
				if ($widget_config->is_set(RatingsWidgetConfiguration::TOOLTIP_TIP_POSITION) === false) {
					$widget_config->set(RatingsWidgetConfiguration::TOOLTIP_TIP_POSITION, $this->core->get_dbconfig('ratings', Ratings::CONFIG_DEFAULT_TOOLTIP_TIP_POSITION, RatingsWidgetConfiguration::DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_CENTER));
				}

				// Register the widget and the template file.
				$this->core->register_widget('ratings', "modules/ratings/templates/widget_ratings.tpl");

				 // Add the style.
				$this->core->add_css('/modules/ratings/css/widget_ratings.css');
				$this->core->add_js('/modules/ratings/js/widget_ratings.js');

				// Determine if the user can rate or not.
				$can_rate = (!$widget_config->is_enabled(RatingsWidgetConfiguration::ALLOW_ONLY_REGISTERED_USER, false) || $this->session->is_logged_in());

				// Get the rating object.
				$rating_object = new RatingsObj($clean_uuid);

				// We need to store the cconfigurartion also within the session, because the ajax rate request needs to verify the user input.
				$config_array = array(
					'max_stars' => $widget_config->get(RatingsWidgetConfiguration::MAX_STARS),
					'star_size' => $widget_config->get(RatingsWidgetConfiguration::STAR_SIZE),
					'tooltip_template' => $widget_config->get(RatingsWidgetConfiguration::TOOLTIP_TEMPLATE),
					'tooltip_position' => $widget_config->get(RatingsWidgetConfiguration::TOOLTIP_POSITION),
					'tooltip_tip_position' => $widget_config->get(RatingsWidgetConfiguration::TOOLTIP_TIP_POSITION),
					'tooltip_enabled' => $widget_config->is_enabled(RatingsWidgetConfiguration::TOOLTIP_ENABLED),
					'current_ratings' => $rating_object->get_rating(),
					'already_rated' => $rating_object->has_already_rated(),
					'user_rating' => $rating_object->get_user_rating(),
					'can_rate' => $can_rate,
					'can_manage_tips' => $this->right_manager->has_perm('admin.ratings.manage'),
				);

				// Setup the configuration array to the session, this is needed because we need to check the configuration within the rate ajax,
				// else we would have no option to validate the configurated settings like max stars or that the user needs to be logged in.
				$this->session->set('ratings_widget_configuration_' . $clean_uuid, $config_array);

				// Provide js our current configurations.
				$this->core->js_config('widget_ratings', $config_array, true, $clean_uuid);

				// Append the ratings result.
				$this->smarty->append(array('widget_ratings' => array($clean_uuid => array(
					'rating_config' => &$widget_config,
				))), '', true);

				return $clean_uuid;
		}
		return null;
	}
}
