=== HDWebmobile Custom Order Numbers ===
Contributors: htrxuan
Donate link: https://paypal.me/htrxuan/20
Tags: woocommerce, order number, custom order number, order prefix, invoice number
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Give your orders a custom-formatted display number -- a prefix, a suffix, zero-padding, and a starting offset.

== Description ==

HDWebmobile Custom Order Numbers changes the order number WooCommerce shows everywhere -- the admin order list and screen, order emails, and My Account -- to your own format: a prefix like "ORD-", a suffix, a minimum digit count with zero-padding, and a starting-number offset so your first order doesn't have to display as "#1".

= Why this plugin exists =
"Custom Order Numbers for WooCommerce" (versions up to and including 1.11.0) shipped CVE-2025-66071 (CWE-862 Missing Authorization): order-numbering functionality that should have been restricted to administrators could be manipulated or accessed by users who shouldn't have been able to.

This plugin is built so that class of bug has nowhere to happen:

* **One write path, and it's WordPress's own.** The format settings are saved entirely through the WordPress Settings API, which enforces the `manage_options` capability and a verified nonce before anything is written. This plugin adds no custom save handler of its own to get wrong.
* **The custom number is a display label, nothing more.** It is generated purely from the order's own real, immutable id (via WooCommerce's own `woocommerce_order_number` filter) -- there is no separate counter or sequence that could drift, collide under concurrent orders, or be raced. The real order id and order key remain exactly what WooCommerce uses to look up and authorize access to an order; the custom number never substitutes for either.
* **No new search or lookup surface.** This plugin does not add a "find an order by its custom number" feature -- exactly the kind of extra endpoint that would need its own authorization check to get right. Order search and access continue to go entirely through WooCommerce's own, already-capability-gated admin screens.

= Key Features =
* Prefix, suffix, zero-padding, and a starting-number offset
* Applies everywhere WooCommerce shows an order number: admin, emails, My Account
* A live preview of the format while you configure it
* Zero risk of a custom number ever going out of sync with the real order -- it's derived, not stored

= Limitations (please read before installing) =
* One global format for the whole store -- no per-year or per-status numbering schemes
* No "search orders by custom number" admin feature in this version
* Changing the format changes how existing orders display immediately; it does not rewrite anything stored

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/hdwebmobile-custom-order-numbers` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress. WooCommerce must already be installed and active.
3. Go to **WooCommerce > HDWebmobile > Custom Order Numbers** to set your format.

== How to Use ==

= 1. Set your format =
On the Custom Order Numbers tab, enter a prefix, suffix, minimum digit count, and starting offset. The preview updates to show exactly how a sample order would display.

= 2. It applies everywhere automatically =
No further setup -- the new format appears immediately in the admin order list, the Edit Order screen, order emails, and the customer's My Account order history.

== Screenshots ==

1. The Custom Order Numbers settings tab with its live preview.
2. A custom-formatted order number in the admin order list.
3. The same order number in a customer's order email.

== Changelog ==

= 1.0.0 =
* Initial release: prefix/suffix/padding/offset order-number formatting via WooCommerce's own woocommerce_order_number filter, settings-API-only configuration.
