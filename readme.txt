=== Learning More ===
Contributors: keighl
Donate link: http://www.keighl.com/plugins/learning-more/
Tags: widget, learn more, email, contact, ajax, form
Requires at least: 2.8
Tested up to: 2.9
Stable tag: trunk

Adds a simple contact widget to the sidebar. Let's a user quickly send you an email requesting more info. AJAX validation.

== Description ==

Adds a simple "Learn More" widget to the sidebar. Let's a user quickly send you an email requesting more info. AJAX validation.

= Use =

1. Add a 'Learning More' widget to one of your sidebars
1. Supply data for:
	* Title - The display title for the widget
	* Email - To which email should the message be sent. 
	* Success - The message supplied to users after form is submitted
1. Give it some style; include these CSS rules in your theme to customize the widget some more:

`
.learning_more_success { display:none; }

.learning_more_invalid { background-color:#ffcaca; }

.learning_more_valid { background-color:#d3ffca; }

#learning_more_name. #learning_more_email { }

#learning_more_submit { }
`

= Features =

1. Not complicated. 
1. Entirely AJAXed, so there are no page refreshes. 

= Support = 

For any issues you're having with 'Learning More', or if you'd like to suggest a feature, visit the [Plugin Homepage](http://keighl.com/plugins/learning-more/ "Plugin homepage").

== Installation ==

1. Upload the entire `/learning-more/` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What will the email look like? =

Very simple. It will give the user's name and email; that's it. It's essentially just for users to let you know they want some more info on your product/service/scam.

== Screenshots ==

1. Learning More widget
2. Live widget

== Changelog ==

= 0.2 =
* Minor change for better xhtml validation.

= 0.1 =
* Initial release.
