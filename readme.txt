=== Popup Creator ===
Contributors: anisur2805
Tags: popup, modal, notification bar, gutenberg, lead generation
Requires at least: 6.3
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build popups, slide-ins, notification bars and fullscreen offers in the block editor, with scroll triggers and countdown timers included.

== Description ==

Popup Creator makes the popup body the WordPress block editor canvas. You lay a popup out with the
blocks you already know — columns, buttons, images, forms — instead of filling in a fixed set of
title and subtitle fields. Behaviour, targeting and appearance live in a three-tab settings panel
next to the editor.

= Popup types =

* Modal
* Slide In
* Notification Bar
* Fullscreen

= Triggers =

* On page load
* After a delay
* On click of any CSS selector
* After scrolling 25%, 50% or 75% of the page
* After X seconds

= Display rules =

* Entire site
* Specific pages
* Specific posts
* Categories and tags
* Any public custom post type
* Include or exclude rules, stacked as many rows as you need

= Design =

* Built with the block editor — no separate page builder to learn
* Starter block patterns to open from a blank popup
* Nine-cell position grid, box or full-width layouts
* Per-device width controls for desktop, tablet and mobile
* Overlay colour, blur and z-index
* Opening and closing animations
* Full close-button styling: position, colour, size, padding, margin, radius, tooltip

= Marketing =

* Email signup form with a subscriber list in the admin
* Countdown timer with custom expiry text
* Call-to-action buttons via the core Buttons block
* Frequency control: every time, once per period (minutes to months), or once only
* Floating launcher button that re-opens a dismissed popup
* Social proof block showing how many people subscribed in a recent time window

= Visitor experience =

* Per-device visibility — hide on mobile, tablet or desktop
* Close button, ESC key close, overlay-click close
* Optional page-scroll lock while a popup is open
* Scheduling: run a popup only between two dates

= Analytics =

Views, opens, closes and conversions are counted per popup and shown in the admin.

= Privacy =

The Popup Social Proof block reports an aggregate count only. Subscriber names and email
addresses are never rendered on the front end.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install it through the Plugins screen.
2. Activate the plugin through the Plugins screen.
3. Go to Popups and add a new popup.
4. Lay the popup out in the editor, then set behaviour, appearance and targeting in the Popup Settings panel.
5. Publish. The popup appears on the pages your display rules match.

== Frequently Asked Questions ==

= Do I need a page builder? =

No. The popup body is the standard block editor, so any block you can use in a post works in a popup.

= How do I open a popup from a link or button? =

Set the trigger mode to Click and enter a CSS selector, for example `.my-button`. Every element
matching that selector opens the popup.

= Can I show different popups on different pages? =

Yes. Each popup has its own display rules, and you can stack include and exclude rows covering the
whole site, individual pages and posts, categories, tags or custom post types.

= Where do email signups go? =

Into a Subscribers screen in the admin, recorded against the popup that collected them. You can
search the list and delete entries in bulk.

= What happens to my data when I delete the plugin? =

Deleting the plugin removes its popups, settings, subscriber records and database tables.
Deactivating it leaves everything in place.

== Changelog ==

= 1.0.0 =
* Initial release.
