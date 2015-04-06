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

		// Initialize vertical center using variables passed from PHP.
		$.each( verticalCenterElements, function() {

			$.each( this, function() {

				// Scope the vars.
				var $selector, offset;

				// Confirm that each selector is valid.
				try {

					// Test the selector.
					$selector = $( this.selector );

				} catch ( e ) {

					// If we have an error, the selector must not be valid,
					// so skip it and continue to the next selector.
					return true;
				}

				// Set the offset.
				offset = this.offset;

				// Start the party.
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

		// The timer.
		var timeout;

		return function debounced() {

			// Store the passed in function and args.
			var obj = this;
			var args = arguments;

			// This is the callback that the timer triggers when debouncing is complete.
			function delayed() {

				// We have successfully debounced, trigger the function.
				func.apply( obj, args );

				// And clear the timer.
				timeout = null;
			}

			// If the timer is active, clear it.
			if ( timeout ) {
				clearTimeout( timeout );
			}

			// Set the timer to 50ms and have it call delayed() when it completes.
			timeout = setTimeout( delayed, threshold || 50 );
		};
	};

	// Main plugin function.
	$.fn.initVerticalCenter = function( offset ) {

		// Scope our variables.
		var selector, eventData, ourEvents, eventSet, thisEvent, eventName;

		// Get the selector used to call this function.
		selector = this.selector;

		// Use the args that were passed in or use the defaults.
		offset = offset || 0;

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

			// Reset our flag to false.
			eventSet = false;

			// Store this event.
			thisEvent = this;

			// Add the namespace.
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

			// If our flag is still false then we can safely attach the event.
			if ( ! eventSet ) {

				// Namespace it and debounce it to be safe.
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

			doVerticalCenter( selector, offset );
		});
	}

	// Function to do the vertical centering.
	function doVerticalCenter( selector, offset ) {

		// Scope our variables.
		var $target, parentHeight;

		// Store the target.
		$target = $( selector );

		// Grab the wrapper's height.
		parentHeight = $target.parent().outerHeight();

		// Make sure the element is block level.	
		if ( $target.css( 'display' ) === 'inline' ) {
			$target.css( 'display', 'inline-block' );
		}

		// Calculate and add the margin-top to center the element.
		$target.css(
			'margin-top',
			( ( ( parentHeight - $target.outerHeight() ) / 2 ) + parseInt( offset ) )
		);
	}

})( jQuery );
