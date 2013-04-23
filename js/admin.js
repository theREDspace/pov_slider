(function ($) {
	"use strict";
	$(function () {

		var t;
		$('#search-field').keyup(function() { 
			clearTimeout(t);
			if ($('#search-field').val() == '') {
				$('.waiting').hide();
				$('.clear-btn').hide();
				$('#search-query-notice').hide();
				$('#sortable-search').hide();
				$('#recent-sortable').show();
				$('#recent-query-notice').show();
				clearTimeout(t);
			} else {
				$('.waiting').show();
				$('#recent-sortable').hide();
				$('#recent-query-notice').hide();
				t = setTimeout( function() {
						var data = {
							action: 'pov_slider_homepage_slider_search',
							s: $('#search-field').val()
						}
						$('#sortable-search li').remove();
						$('#search-results div.query-notice').remove();
						$.ajax({
							url: ajaxurl,
							data: data,
							type: 'POST',
							dataType: 'json',
							success: function(msg) {
								$('.waiting').hide();
								$('.clear-btn').show();
								$('#search-query-notice').hide();
								if (msg.length > 0) {
									$('#sortable-search').show();
									for(var post in msg) {
										var p = msg[post];
										$('#sortable-search').append('<li id="' + p.id + '"><span class="item-title">' + p.title + '</span><span class="item-info">' + p.type + '</span>');
									}
								} else {
										$('#search-query-notice').show();
								}
							}
						});
				}, 1000);
			}
		});

		$('.clear-btn').on('click', function(e) {
			e.preventDefault();
			$('#search-field').val('').trigger('keyup');

		});

		$( "#sortable-search, #recent-sortable, #featured-sortable" ).sortable({
			connectWith: ".connectedSortable"
		}).disableSelection();
				
		$( "#slider" ).submit(function() {
			$("#featured-sortable li").each(function() {
				$("#slider").append('<input type="hidden" name="pov_slider_featured_posts[]" value="' + $(this).attr('id') + '" />');
			});
		});

	});
}(jQuery));