<?php

namespace htrxuan\hdcon;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The hub tab. Every setting is written through the WordPress Settings API (`options.php`),
 * which performs its own `manage_options` capability check and nonce verification -- there is
 * no custom write path.
 */
class HDCON_Admin
{
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
        require_once HDCON_PLUGIN_DIR . 'includes/class-hdcon-hub.php';
        add_filter('hdwebmobile_hub_tabs', array($this, 'register_hub_tabs'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings()
    {
        register_setting('hdcon_group', HDCON_Numbering::OPTION_KEY, array(
            'type'              => 'array',
            'sanitize_callback' => array($this, 'sanitize'),
            'default'           => HDCON_Numbering::defaults(),
        ));
    }

    public function sanitize($input)
    {
        $d = HDCON_Numbering::defaults();
        return array(
            'prefix'  => isset($input['prefix']) ? sanitize_text_field($input['prefix']) : $d['prefix'],
            'suffix'  => isset($input['suffix']) ? sanitize_text_field($input['suffix']) : $d['suffix'],
            'padding' => isset($input['padding']) ? max(0, min(10, absint($input['padding']))) : $d['padding'],
            'offset'  => isset($input['offset']) ? max(-999999, min(999999, (int) $input['offset'])) : $d['offset'],
        );
    }

    public function register_hub_tabs($tabs)
    {
        $tabs['custom-order-numbers'] = array(
            'label'  => __('Custom Order Numbers', 'hdwebmobile-custom-order-numbers'),
            'order'  => 52,
            'render' => array($this, 'render_page'),
        );
        return $tabs;
    }

    public function render_page()
    {
        $o = HDCON_Numbering::get_options();
        $sample_id = 1042;
        ?>
        <p><?php esc_html_e('Show your own order-number format everywhere WooCommerce displays one -- admin, emails, and My Account. The real order is always looked up by its own id and key underneath; this only changes what number a customer sees.', 'hdwebmobile-custom-order-numbers'); ?></p>

        <form method="post" action="options.php">
            <?php settings_fields('hdcon_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="hdcon_prefix"><?php esc_html_e('Prefix', 'hdwebmobile-custom-order-numbers'); ?></label></th>
                    <td><input type="text" id="hdcon_prefix" class="regular-text" name="<?php echo esc_attr(HDCON_Numbering::OPTION_KEY); ?>[prefix]" value="<?php echo esc_attr($o['prefix']); ?>" placeholder="ORD-" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="hdcon_suffix"><?php esc_html_e('Suffix', 'hdwebmobile-custom-order-numbers'); ?></label></th>
                    <td><input type="text" id="hdcon_suffix" class="regular-text" name="<?php echo esc_attr(HDCON_Numbering::OPTION_KEY); ?>[suffix]" value="<?php echo esc_attr($o['suffix']); ?>" placeholder="-2026" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="hdcon_padding"><?php esc_html_e('Minimum digits (zero-padded)', 'hdwebmobile-custom-order-numbers'); ?></label></th>
                    <td><input type="number" id="hdcon_padding" min="0" max="10" step="1" class="small-text" name="<?php echo esc_attr(HDCON_Numbering::OPTION_KEY); ?>[padding]" value="<?php echo esc_attr($o['padding']); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="hdcon_offset"><?php esc_html_e('Starting-number offset', 'hdwebmobile-custom-order-numbers'); ?></label></th>
                    <td>
                        <input type="number" id="hdcon_offset" step="1" class="small-text" name="<?php echo esc_attr(HDCON_Numbering::OPTION_KEY); ?>[offset]" value="<?php echo esc_attr($o['offset']); ?>" />
                        <p class="description"><?php esc_html_e('Added to the real order id, e.g. an offset of 1000 makes your first order display as 1001 instead of 1.', 'hdwebmobile-custom-order-numbers'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Preview', 'hdwebmobile-custom-order-numbers'); ?></th>
                    <td>
                        <?php
                        /* translators: 1: an example real order id, 2: the resulting formatted number */
                        printf(esc_html__('Order #%1$d would display as: %2$s', 'hdwebmobile-custom-order-numbers'), (int) $sample_id, '<strong>' . esc_html(HDCON_Numbering::format($sample_id)) . '</strong>');
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Save Settings', 'hdwebmobile-custom-order-numbers')); ?>
        </form>
        <?php
    }
}
