<?php

namespace htrxuan\hdcon;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Formats a customer-facing order number from the order's own real, immutable id -- and
 * nothing else. There is no separate counter, sequence table, or anything that could drift
 * out of sync with, collide with, or be raced against the real id.
 *
 * CVE-2025-66071 (CWE-862 Missing Authorization) in "Custom Order Numbers for WooCommerce"
 * (<= 1.11.0): order-numbering functionality that should have been restricted to
 * administrators could be manipulated or accessed by users who shouldn't have been able to.
 *
 * This plugin closes that by construction on two fronts:
 *   - The ONLY write path for the format settings (prefix, suffix, digit padding, starting
 *     offset) is the WordPress Settings API (`options.php`), which itself enforces
 *     `manage_options` and a verified nonce before anything is saved. There is no second,
 *     custom write path for this plugin to get wrong.
 *   - The formatted number is PURELY a display label, applied only through WooCommerce's own
 *     `woocommerce_order_number` filter (confirmed directly in WC_Order::get_order_number()).
 *     It is never used to look an order up, authorize access to one, or substitute for the
 *     order's real id or order key anywhere. This plugin adds no search-by-custom-number
 *     endpoint at all -- exactly the kind of extra surface that would need its own
 *     authorization check to get right, and is simply not present here to get wrong.
 */
final class HDCON_Numbering
{
    const OPTION_KEY = 'hdcon_settings';

    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_filter('woocommerce_order_number', array($this, 'filter_order_number'), 10, 2);
    }

    public static function defaults()
    {
        return array(
            'prefix'   => '',
            'suffix'   => '',
            'padding'  => 0,
            'offset'   => 0,
        );
    }

    public static function get_options()
    {
        $opts = get_option(self::OPTION_KEY, array());
        return wp_parse_args(is_array($opts) ? $opts : array(), self::defaults());
    }

    /**
     * @param string    $number The real order id, as a string (WooCommerce's own default).
     * @param \WC_Order $order
     * @return string
     */
    public function filter_order_number($number, $order)
    {
        if (!$order instanceof \WC_Order) {
            return $number;
        }
        return self::format($order->get_id());
    }

    /**
     * @param int $order_id The order's own real, database id -- the only input.
     * @return string
     */
    public static function format($order_id)
    {
        $opts   = self::get_options();
        $number = absint($order_id) + (int) $opts['offset'];
        $number = max(0, $number);

        $digits = (string) $number;
        if ((int) $opts['padding'] > 0) {
            $digits = str_pad($digits, (int) $opts['padding'], '0', STR_PAD_LEFT);
        }

        return $opts['prefix'] . $digits . $opts['suffix'];
    }
}
