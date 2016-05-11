=== A Simple Multilanguage Plugin ===
Contributors: piupiiu
Tags: language, translate, multilanguage
Requires at least: 4.0
Tested up to: 4.5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Multilanguage support for pages and posts. Displays content based on selected language. Includes widget and shortcode for language selection.

== Description ==

A really simple to use plugin which provides support for additional languages for your pages and posts. Does not work with custom post types and some themes.

Adds title and content edit boxes for each additional language on edit pages for posts and pages. Translation indicator available for post list on admin panel (can be turned off).

Includes widget and shortcode to generate language selection.

When a language is selected, displays all pages, posts and menus based on selected language. Different actions for untranslated content available in the settings.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Languages screen to update the display name of your default language use 'Add language' to add more

== Frequently Asked Questions ==

= Does this plugin translate my blog? =

No. It provides a convenient environment to store translated content and switch languages.

= How do I switch languages? =

There is a widget and shortcode for displaying the language selection links. Alternatively you can add ?lang=_LANG_SHORT_ to the URL of any page of your site. Please note that you'll have to have at least one language added from the settings page before you can switch languages.

= I tried to switch languages and all my content and menu dissapreared. =

That may be because you haven't translated any content yet. The plugin does not display untranslated content by default. However, if you wish it do display content in the default language even when switched to another one, there is a setting for that.
If you have translated the content and it still doesn't show up, it may mean that the plugin does not work with your theme.

= Why can't I translate categories / tags / widgets / custom post types / etc ? =

This is a simple plugin for simple sites. It only supports translating titles and content of posts and pages. If you're running a more complicated site, you'll need a more sophisticated plugin. 

= The plugin doesn't work with my theme. =

I'm sorry to hear that. The plugin is tested with most of the themes from the WP team as well as some others and it should work with most. It uses pretty basic WP functions to work, but there are themes that display content differently and I can't account for them all. 

== Screenshots ==

1. Settings panel with 4 languages.
2. Language selection widget and menu in default language, menu in English with display untranslated menu items turned on and menu in English without displaying untranslated menu items.
3. Editing pages with additional languages. All language boxes are collapsable.
4. Translation indicators on page list.

== Changelog ==

== Upgrade Notice ==