<?php

/**
 * Plugin Name: HDWebmobile Custom Order Numbers
 * Plugin URI: https://hdwebmobile.com/plugins/hdwebmobile-custom-order-numbers/
 * Description: Give orders a custom-formatted display number -- the real order ID always determines access, never the display number.
 * Version: 1.0.0
 * Author: htrxuan - Han Tran
 * Author URI: https://hdwebmobile.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hdwebmobile-custom-order-numbers
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 * Requires PHP: 7.4
 * Requires at least: 6.9
 */

namespace htrxuan\hdcon;

if (!defined('ABSPATH')) {
    exit;
}

define('HDCON_VERSION', '1.0.0');
define('HDCON_PLUGIN_FILE', __FILE__);
define('HDCON_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HDCON_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once HDCON_PLUGIN_DIR . 'includes/class-hdcon-activator.php';

register_activation_hook(__FILE__, array(HDCON_Activator::class, 'activate'));

add_action('before_woocommerce_init', function () {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', HDCON_PLUGIN_FILE, true);
    }
});

add_action('plugins_loaded', function () {
    require_once HDCON_PLUGIN_DIR . 'includes/class-hdcon-core.php';
    HDCON_Core::get_instance();
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $donate_link = '<a href="https://paypal.me/htrxuan/20" target="_blank" rel="noopener noreferrer">' . esc_html__('Donate', 'hdwebmobile-custom-order-numbers') . '</a>';
    array_unshift($links, $donate_link);
    return $links;
});
