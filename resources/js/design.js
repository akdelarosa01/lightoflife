( function($) {
	"use strict"; // Start of use strict
	$('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
	  	if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
	  		var target = $(this.hash);
	  		target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	  		if (target.length) {
	  			$('html, body').animate({
	  				scrollTop: (target.offset().top - 56)
	  			}, 1000, "easeInOutExpo");
	  			return false;
	  		}
		}
	});

	// Closes responsive menu when a scroll trigger link is clicked
	$('.js-scroll-trigger').click(function() {
		$('.navbar-collapse').collapse('hide');
	});

	// Activate scrollspy to add active class to navbar items on scroll
	$('body').scrollspy({
		target: '#mainNav',
		offset: 56
	});

  $('.navbar .dropdown-item').on('click', function (e) {
		var $el = $(this).children('.dropdown-toggle');
		var $parent = $el.offsetParent(".dropdown-menu");
		$(this).parent("li").toggleClass('open');

		if (!$parent.parent().hasClass('navbar-nav')) {
			if ($parent.hasClass('show')) {
				$parent.removeClass('show');
				$el.next().removeClass('show');
				$el.next().css({"top": -999, "left": -999});
			} else {
				$parent.parent().find('.show').removeClass('show');
				$parent.addClass('show');
				$el.next().addClass('show');
				$el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
			}
			e.preventDefault();
			e.stopPropagation();
		}
	});

	$('.navbar .dropdown').on('hidden.bs.dropdown', function () {
		$(this).find('li.dropdown').removeClass('show open');
		$(this).find('ul.dropdown-menu').removeClass('show open');
	});

})(jQuery); // End of use strict