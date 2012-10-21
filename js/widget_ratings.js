Soopfw.behaviors.ratings_widget_ratings = function() {

	var widgets = Soopfw.config.widget_ratings;

	if (!empty(widgets)) {
		foreach (widgets, function(widget_id, config) {

			// Get the widget container.
			var widget_container = $('div[widget_id="' + widget_id + '"]');

			// Configurate the widget container
			widget_container
				.css('width', (config.max_stars * config.star_size) + 'px')
				.css('height', config.star_size + 'px');

			// Configurate sub elements.
			$('div', widget_container)
				.css('height', config.star_size + 'px');

			// Pre init current rating.
			$('div.star_rating_rating', widget_container)
				.css('width', (config.star_size * config.current_ratings) + 'px');

			// Configurate the event layer.

			// If we leave the rating with the mouse, reset it to the current rating.
			$('div.star_rating_event_layer', widget_container).off('mouseout').on('mouseout', function() {
				var parent = $(this).parent();
				$('div.star_rating_rating', parent).css('width',(Soopfw.config.widget_ratings[$(parent).attr('widget_id')].current_ratings *  config.star_size) + "px");
			});

			// Setup rate event.
			$('div.star_rating_event_layer', widget_container).off('mousemove').on('mousemove', function(e) {
				var parent = $(this).parent();
				var over_width = parseInt((e.pageX - parent.position().left) / config.star_size) + 1;
				$('div.star_rating_rating',parent) .css('width', (over_width * config.star_size) + 'px');
			});

			// Get a help tooltip and setup our rate click event.
			var tooltip_text = '';
			if (config.can_rate) {

				if (config.already_rated === true) {
					tooltip_text = Soopfw.t('Please select your voting. (You have already rated, your old voting will be updated.)');
				}
				else {
					tooltip_text = Soopfw.t('Please select your voting.');
				}

				$('div.star_rating_event_layer', widget_container).off('click').on('click', function(e) {
					var parent = $(this).parent();

					var post_data = {
						'widget_id': $(parent).attr('widget_id'),
						'rating': parseInt((e.pageX - parent.position().left) / config.star_size) + 1
					};

					ajax_request('/ratings/rate.ajax', post_data, function(results) {
						success_alert(Soopfw.t('Your rating was saved.'));
						Soopfw.config.widget_ratings[results.widget_id].current_ratings = results.current_rating;
					});
				});

			}
			else {
				tooltip_text = Soopfw.t('In order to rate, you need to be logged in. Please login first.');
				$('div.star_rating_event_layer', widget_container).off('click').on('click', function(e) {
					Soopfw.location(Soopfw.config.login_url);
				});
			}

			if (config.tooltip_enabled === true) {
				var tooltip = $('div.star_rating_event_layer', widget_container).qtip({
					content: {text: tooltip_text},
					position: {my: config.tooltip_tip_position, at: config.tooltip_position, target: this},
					style: {
						classes: 'ui-tooltip-shadow ui-tooltip-' + config.tooltip_template
					}
				});
			}
			if (config.can_manage_tips) {

				var manage_text = '<a href="/admin/ratings/manage_ratings/' + widget_id + '">' + Soopfw.t('Manage ratings') + '</a>';
				var manage_tip_options = {
					content: {text: manage_text},
					position: {my: 'top center', at: 'bottom center', target: this},
					hide: 'unfocus',
					style: {
						classes: 'ui-tooltip-shadow ui-tooltip-light'
					}
				};
				if (tooltip === undefined) {
					$('div.star_rating_event_layer', widget_container).qtip(manage_tip_options);
				}
				else {
					tooltip.removeData('qtip').qtip(manage_tip_options);
				}
			}

		});
	}
};