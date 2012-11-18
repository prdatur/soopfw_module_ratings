<?php
/**
 * Configuration for ratings widgets.
 *
 * @copyright Christian Ackermann (c) 2010 - End of life
 * @author Christian Ackermann <prdatur@gmail.com>
 * @category Configurations
 */
class RatingsWidgetConfiguration extends Configuration {

	/**
	 * How many stars will be used?
	 *
	 * @use: set(), get()
	 * @return int
	 */
	const MAX_STARS = 1;

	/**
	 * The star size
	 *
	 * @use: set(), get()
	 * @return int
	 */
	const STAR_SIZE = 2;

	/**
	 * Defineable STAR_SIZE size.
	 * Value is px.
	 */
	const DEFAULT_STAR_SIZE_24 = 24;

	/**
	 * If only registered users can rate
	 *
	 * @use: enable(), disable(), is_enabled(), is_disabled()
	 * @return boolean
	 */
	const ALLOW_ONLY_REGISTERED_USER = 3;

	/**
	 * The tooltip style.
	 *
	 * @use: set(), get()
	 * @return string
	 */
	const TOOLTIP_TEMPLATE = 4;

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_RED = 'red';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_BLUE = 'blue';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_DARK = 'dark';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_LIGHT = 'light';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_GREEN = 'green';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_JTOOLS = 'jtools';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_PLAIN = 'plain';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 * (default one)
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_YOUTUBE = 'youtube';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_CLUETIP = 'cluetip';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_TIPSY = 'tipsy';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_TIPPED = 'tipped';

	/**
	 * Defineable TOOLTIP_TEMPLATE template.
	 */
	const DEFAULT_TOOLTIP_TEMPLATE_BOOTSTRAP = 'bootstrap';

	/**
	 * The tooltip position
	 *
	 * @use: set(), get()
	 * @return string
	 */
	const TOOLTIP_POSITION = 5;

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_TOP_LEFT = 'top left';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_TOP_RIGHT = 'top right';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_TOP_CENTER = 'top center';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_RIGHT_CENTER = 'right center';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_RIGHT_TOP = 'right top';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_RIGHT_BOTTOM = 'right bottom';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_LEFT_CENTER = 'left center';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_LEFT_TOP = 'left top';

	/**
	 * Defineable TOOLTIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_POSITION_LEFT_BOTTOM = 'left bottom';

	/**
	 * The tooltip tip position
	 *
	 * @use: set(), get()
	 * @return string
	 */
	const TOOLTIP_TIP_POSITION = 6;

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_TOP_LEFT = 'top left';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_TOP_RIGHT = 'top right';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_TOP_CENTER = 'top center';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_LEFT = 'bottom left';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_RIGHT = 'bottom right';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_BOTTOM_CENTER = 'bottom center';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_CENTER = 'right center';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_TOP = 'right top';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_RIGHT_BOTTOM = 'right bottom';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_LEFT_CENTER = 'left center';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_LEFT_TOP = 'left top';

	/**
	 * Defineable TOOLTIP_TIP_POSITION position.
	 */
	const DEFAULT_TOOLTIP_TIP_POSITION_LEFT_BOTTOM = 'left bottom';

	/**
	 * If enabled tooltips will be visible.
	 *
	 * @use: enable(), disable(), is_enabled(), is_disabled()
	 * @return boolean
	 */
	const TOOLTIP_ENABLED = 7;

}

