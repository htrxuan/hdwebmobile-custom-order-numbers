# HDWebmobile Custom Order Numbers

Give your WooCommerce orders a custom-formatted display number — prefix, suffix, zero-padding, and a starting offset.

- **WordPress.org:** https://wordpress.org/plugins/hdwebmobile-custom-order-numbers/
- **Requires:** WordPress 6.9+, WooCommerce, PHP 7.4+
- **License:** GPLv2 or later

## Description

Changes the order number shown everywhere — admin, emails, My Account — to your own format, without touching how orders are actually identified or accessed underneath.

## Why this plugin exists

"Custom Order Numbers for WooCommerce" (≤ 1.11.0) shipped CVE-2025-66071 (CWE-862 Missing Authorization) — order-numbering functionality restricted to admins could be manipulated by users who shouldn't have been able to.

Closed by construction:

* **One write path, WordPress's own** — settings saved via the Settings API (`manage_options` + nonce), no custom handler.
* **The number is a display label only** — derived purely from the order's real id via WooCommerce's own `woocommerce_order_number` filter; no separate counter to drift or collide. The real id/key remain what actually authorizes access.
* **No new search/lookup surface** — no "find order by custom number" feature to get an authorization check wrong on.

## Features

* Prefix, suffix, zero-padding, starting-number offset
* Applies everywhere: admin, emails, My Account
* Live format preview
* Never goes out of sync — it's derived, not stored

## Limitations

* One global format store-wide
* No "search by custom number" admin feature in this version
* A format change re-displays existing orders immediately; nothing stored is rewritten

## Installation

1. Upload to `/wp-content/plugins/hdwebmobile-custom-order-numbers`, or install through the WordPress plugins screen.
2. Activate. WooCommerce must already be installed and active.
3. Go to **WooCommerce > HDWebmobile > Custom Order Numbers**.

## License

GPLv2 or later — https://www.gnu.org/licenses/gpl-2.0.html
