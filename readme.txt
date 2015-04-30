=== Vertical Center ===
Contributors:      Braad
Donate link:       http://braadmartin.com
Tags:              vertical, center, responsive, jQuery, JavaScript
Requires at least: 3.8
Tested up to:      4.2.1
Stable tag:        1.0.1
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Easily vertically center any element relative to its container in an intelligent, fully responsive way.

== Description ==

Vertical Center lets you easily vertically center elements.

= Features =

* Center an unlimited number of elements independently
* Specify simple CSS/jQuery selectors to target elements
* Easy to use admin interface
* Items are vertical centered immediately after the page has loaded
* Fully responsive (automatically updates on resize and orientationchange events)
* Works on mobile devices
* Works across all modern browsers (including IE8)
* Trigger custom 'verticalcenter' event to force a recalculation
* Debounced resize events for added smoothness

Check out the [Screenshots tab](https://wordpress.org/plugins/vertical-center/screenshots/) for a gif of the plugin in action.

= Instructions =

1. Navigate to **Settings > Vertical Center** in the WordPress admin.
2. Enter a *selector* and an *offset* for the element(s) you want to center.
3. Add/remove additional elements by clicking the "+ Add More" and "Remove" buttons.

= Advanced =

Want to trigger the vertical centering manually? No problem. You can skip entering a selector on the settings page and call the jQuery script yourself:

`
jQuery( '.selector' ).initVerticalCenter();
`

The function initVerticalCenter takes one optional argument, the offset value (as a number of pixels):

`
jQuery( '.selector' ).initVerticalCenter( offset );
`

To offset the calculation by 20 pixels:

`
jQuery( '.selector' ).initVerticalCenter( 20 );
`

The `initVerticalCenter()` function is chainable. My personal favorite way to use this plugin is to inline `style="opacity: 0;"` on the elements that I am centering (to make them transparent when the DOM loads) and then fade them in with something like this:

`
jQuery( '.selector' ).initVerticalCenter().delay( 200 ).fadeTo( 'slow', 1 );
`

This plugin also adds an event 'verticalcenter' to the window, allowing you to easily trigger the vertical centering manually. This is useful if you have added items to the page after it loads via AJAX. You can trigger the event like this:

`
jQuery( window ).trigger( 'verticalcenter' );
`

== Frequently Asked Questions ==

= Is this plugin fully responsive? =

Yes! When the script runs it creates event listeners for the window resize and orientationchange events and recalculates the vertical centering after those events trigger.

= Does the plugin support multiple items that get vertically centered independently? =

Yes! From the settings page you can enter as many selectors as you'd like, giving you the ability to vertically center an unlimited number of items.

= What if I am dynamically adding the element I want to center to the page after it loads? =

The jQuery script uses the selector to always grab the items fresh from the DOM in its current state, so as long as the selector matches the newly added element it will get included in the calculation. You can trigger the vertical centering manually at any time (such as after new content has been added via AJAX) by triggering the 'verticalcenter' event on the window like this:

`
jQuery( window ).trigger( 'verticalcenter' );
`

== Screenshots ==

1. The easy-to-use admin interface.
2. Vertical centering in action.

== Installation ==

= Manual Installation =

1. Upload the entire `/vertical-center` directory to the `/wp-content/plugins/` directory.
1. Activate Vertical Center through the 'Plugins' menu in WordPress.

= Better Installation =

1. Go to Plugins > Add New in your WordPress admin and search for Vertical Center.
1. Click Install.

== Changelog ==

= 1.0.0 =
* First release

== Upgrade Notice ==

= 1.0.0 =
* First Release