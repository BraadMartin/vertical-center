/**
 * Vertical Center Public JS
 *
 * @since 1.0.0
 */

/**
 * Grab the items to center from our global and init the centering.
 *
 * @since  1.0.0
 */
( function( $ ) {

	// Call on load in case we're centering an image.
	$( window ).on( 'load', function() {

		// Only proceed if we have elements.
		if ( typeof verticalCenterElements == 'undefined' ) {
			return;
		}

		$.each( verticalCenterElements, function() {

			$.each( this, function() {
				var $selector, offset;

				// Confirm that each selector is valid.
				try {
					$selector = $( this.selector );
				} catch ( e ) {
					// If we have an error, the selector must not be valid,
					// so skip it and continue to the next selector.
					return true;
				}

				offset = this.offset;

				$selector.initVerticalCenter( offset );
			});
		});
	});

})( jQuery );

/**
 * Vertical Center Functions
 *
 * Kudos to John Hann for his debounce script and Paul Sprangers for his FlexVerticalCenter.js script.
 *
 * @since  1.0.0
 */
( function( $ ) {

	// Debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function( func, threshold ) {
		var timeout;
		return function debounced() {
			var obj = this;
			var args = arguments;
			function delayed() {
				func.apply( obj, args );
				timeout = null;
			}
			if ( timeout ) {
				clearTimeout( timeout );
			}
			timeout = setTimeout( delayed, threshold || 50 );
		};
	};

	// Main plugin function.
	$.fn.initVerticalCenter = function( offset ) {
		var selector, eventData, ourEvents, eventSet, thisEvent, eventName;

		selector = this.selector;
		offset   = offset || 0;

		// Check if our global already exists.
		if ( window.verticalCenterItems ) {
			// It does, so copy the current object in it.
			window.verticalCenterItems[selector] = offset;
		} else {
			// It doesn't, so create the global and store the current object in it.
			window.verticalCenterItems = {};
			window.verticalCenterItems[selector] = offset;
		}

		// Grab the current event data from the window object if it exists.
		eventData = $._data( window, 'events' ) || {};

		// Store the events that will retrigger doVerticalCenter().
		ourEvents = [ 'resize', 'orientationchange', 'verticalcenter' ];

		// Loop through each event and attach our handler if it isn't attached already.
		$( ourEvents ).each( function() {
			eventSet  = false;
			thisEvent = this;
			eventName = this + '.verticalcenter';

			// Check whether this event is already on the window.
			if ( eventData[ thisEvent ] ) {

				// Be careful not to disturb any unrelated listeners.
				$( eventData[ thisEvent ] ).each( function() {

					// Confirm that the event has our namespace.
					if ( this.namespace == 'verticalcenter' ) {

						// It does, so set our flag to true.
						eventSet = true;
					}
				});
			}

			// If our flag is still false we can safely attach the event.
			if ( ! eventSet ) {

				// Debounce it to be safe.
				$( window ).on( eventName, debounce( triggerVerticalCenter ) );
			}
		});

		// Trigger the first vertical centering.
		triggerVerticalCenter();

		// Make this function chainable.
		return this;
	};

	// Function to trigger the vertical centering.
	function triggerVerticalCenter() {

		// Loop through each object in our global and call doVerticalCenter.
		$.each( window.verticalCenterItems, function( selector, offset ) {

			$( selector ).doVerticalCenter( offset );
		});
	}

	// Function to do the vertical centering.
	$.fn.doVerticalCenter = function( offset ) {
		var offset = offset || 0;

		// Selector might match multiple items, so do all
		// centering calculations on one item at a time.
		$( this ).each( function() {
			var parentHeight = $( this ).parent().height();

			// Make sure the element is block level.
			if ( $( this ).css( 'display' ) === 'inline' ) {
				$( this ).css( 'display', 'inline-block' );
			}

			// Calculate and add the margin-top to center the element.
			$( this ).css(
				'margin-top',
				( ( ( parentHeight - $( this ).outerHeight() ) / 2 ) + parseInt( offset ) )
			).addClass( 'vc-complete' );
		});
	}

})( jQuery );
