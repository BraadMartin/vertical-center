# Vertical Center #
**Contributors:**      Braad  
**Donate link:**       http://braadmartin.com  
**Tags:**              vertical, center, responsive, jQuery, JavaScript  
**Requires at least:** 3.8  
**Tested up to:**      4.3  
**Stable tag:**        1.1.0  
**License:**           GPLv2 or later  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html  

Easily vertically center any element relative to its container. Fully responsive.

## Description ##

Vertical Center lets you easily vertically center elements.

### Features ###

* Center an unlimited number of elements independently
* Specify simple CSS/jQuery selectors to target elements
* Easy to use admin interface
* Items are vertical centered immediately after the page has loaded
* Adds a class to each centered element after it has been centered (useful for CSS transition effects)
* Fully responsive (automatically updates on resize and orientationchange events)
* Works on mobile devices
* Works across all modern browsers (including IE8)
* Trigger custom 'verticalcenter' event to force a recalculation
* Debounced resize events for added smoothness

Check out the [Screenshots tab](https://wordpress.org/plugins/vertical-center/screenshots/) for a gif of the plugin in action.

### Instructions ###

1. Navigate to **Settings > Vertical Center** in the WordPress admin.
2. Enter a *selector* and an *offset* for the element(s) you want to center.
3. Add/remove additional elements by clicking the "+ Add More" and "Remove" buttons.

### Advanced ###

Want to trigger the vertical centering manually? No problem. You can skip entering a selector on the settings page and call the jQuery script yourself using either `.initVerticalCenter()` or `.doVerticalCenter()`. The `.initVerticalCenter()` method sets up the event listeners to recalculate if the window is resized, while the `.doVerticalCenter()` method directly centers without attaching any events:


	// Attach events and center.
	jQuery( '.selector' ).initVerticalCenter();
	
	// Center without attaching events.
	jQuery( '.selector' ).doVerticalCenter();


Both functions take one optional argument, the offset value (as a number of pixels):


	jQuery( '.selector' ).initVerticalCenter( offset );
	
	jQuery( '.selector' ).doVerticalCenter( offset );


To offset the calculation by 20 pixels:


	jQuery( '.selector' ).initVerticalCenter( 20 );


The functions are chainable. My personal favorite way to use this plugin is to inline `style="opacity: 0;"` on the elements that I am centering (to guarantee they'll be transparent when the DOM loads) and then fade them in with something like this:


	jQuery( '.selector' ).initVerticalCenter().delay( 200 ).fadeTo( 'slow', 1 );


Or to achieve the same effect with only CSS you can use the `vc-complete` class that gets added to each target element after the initial centering (added in version 1.0.3), which might look like this:

	#target {
		opacity: 0;
		transition: opacity 0.5s;
	}

	#target.vc-complete {
		opacity: 1;
	}

This plugin also adds an event 'verticalcenter' to the window, allowing you to easily trigger the vertical centering manually. This is useful if you have added items to the page after it loads via AJAX. You can trigger the event like this:


	jQuery( window ).trigger( 'verticalcenter' );


## Frequently Asked Questions ##

### Is this plugin fully responsive? ###

Yes! When the script runs it creates event listeners for the window resize and orientationchange events and recalculates the vertical centering after those events trigger.

### Does the plugin support multiple items that get vertically centered independently? ###

Yes! From the settings page you can enter as many selectors as you'd like, giving you the ability to vertically center an unlimited number of items.

### What if I am dynamically adding the element I want to center to the page after it loads? ###

The jQuery script uses the selector to always grab the items fresh from the DOM in its current state, so as long as the selector matches the newly added element it will get included in the calculation. You can trigger the vertical centering manually at any time (such as after new content has been added via AJAX) by triggering the 'verticalcenter' event on the window like this:


	jQuery( window ).trigger( 'verticalcenter' );


## Screenshots ##

### 1. The easy-to-use admin interface. ###
![The easy-to-use admin interface.](http://ps.w.org/vertical-center/assets/screenshot-1.png)

### 2. Vertical centering in action. ###
![Vertical centering in action.](http://ps.w.org/vertical-center/assets/screenshot-2.png)


## Installation ##

### Manual Installation ###

1. Upload the entire `/vertical-center` directory to the `/wp-content/plugins/` directory.
1. Activate Vertical Center through the 'Plugins' menu in WordPress.

### Better Installation ###

1. Go to Plugins > Add New in your WordPress admin and search for Vertical Center.
1. Click Install.

## Changelog ##

### 1.1.0 ###
* Code cleanup and refactoring
* The plugin class now loads on 'init'
* All strings are now translatable
* Added direct JS method `.doVerticalCenter()`
* Bugfix: Pass correct plugin version when enqueueing the admin css

### 1.0.3 ###
* A class is now added to each target element after it has been initially centered

### 1.0.2 ###
* Improve centering calculation when parent elements have padding

### 1.0.1 ###
* Added support for using selectors that match multiple items
* Improved compatibility with other plugins that use JS to affect layout
* Bugfix: Better scoping of the admin JS to prevent conflicts with other plugins

### 1.0.0 ###
* First release

## Upgrade Notice ##

### 1.1.0 ###
* Code cleanup and refactoring
* The plugin class now loads on 'init'
* All strings are now translatable
* Added direct JS method `.doVerticalCenter()`
* Bugfix: Pass correct plugin version when enqueueing the admin css

### 1.0.3 ###
* A class is now added to each target element after it has been initially centered

### 1.0.2 ###
* Improve centering calculation when parent elements have padding

### 1.0.1 ###
* Added support for using selectors that match multiple items
* Improved compatibility with other plugins that use JS to affect layout
* Bugfix: Better scoping of the admin JS to prevent conflicts with other plugins

### 1.0.0 ###
* First Release