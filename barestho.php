<?php
/*
Plugin Name: Barestho
Description: Plugin pour afficher le widget de réservation.
Version: 1.0.1
Author: Barestho
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; 


function barestho_plugin_load_textdomain()
{
    load_plugin_textdomain('barestho', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'barestho_plugin_load_textdomain');


require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
require_once plugin_dir_path(__FILE__) . 'includes/barestho-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/barestho-display.php';



function barestho_enqueue_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style('barestho-style', $plugin_url . 'css/style-barestho.css', array(), '1.0.1');
    wp_enqueue_style('barestho-style');

    wp_register_script('barestho-script', $plugin_url . 'js/script-barestho.js', array('jquery'), '1.0.1', array('strategy'  => 'defer',), true);
    wp_enqueue_script('barestho-script');
}

function barestho_enqueue_admin_assets($hook) {
    $plugin_url = plugin_dir_url(__FILE__);

    if ($hook == 'toplevel_page_barestho') { 
        wp_register_style('barestho-admin-style', $plugin_url . 'css/barestho-admin-style.css', array(), '1.0.1');
        wp_enqueue_style('barestho-admin-style');

        wp_register_script('barestho-admin-script', $plugin_url . 'js/barestho-admin-script.js', array('jquery', 'wp-color-picker'), '1.0.1', true);
        wp_enqueue_script('barestho-admin-script');

        $barestho_options = get_option('barestho_settings');
        $theme_color = !empty($barestho_options['barestho_theme_color']) ? $barestho_options['barestho_theme_color'] : 'DC0044';
        $disable_text = esc_js(__('Disable mode', 'barestho'));
        $enable_text = esc_js(__('Enable mode', 'barestho'));

        wp_localize_script('barestho-admin-script', 'barestho', array(
            'themeColor' => esc_attr($theme_color),
            'disableText' => $disable_text,
            'enableText' => $enable_text,
        ));
    }
}
add_action('admin_enqueue_scripts', 'barestho_enqueue_admin_assets');

add_action('wp_enqueue_scripts', 'barestho_enqueue_assets');

add_action('wp_footer', 'barestho_toggle');

function barestho_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=barestho">' . esc_html__('Réglages', 'barestho') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'barestho_add_settings_link');


function barestho_enqueue_color_picker()
{
    wp_enqueue_style('wp-color-picker');

    wp_enqueue_script('barestho-color-picker', plugins_url('js/barestho-color-picker.js', __FILE__), array('wp-color-picker'), filemtime(plugin_dir_path(__FILE__) . 'js/barestho-color-picker.js'), true);}
add_action('admin_enqueue_scripts', 'barestho_enqueue_color_picker');


add_action('admin_notices', 'barestho_admin_notice');

function barestho_admin_notice() {
    if (isset($_GET['settings-updated']) && wp_unslash($_GET['settings-updated'])) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Settings saved.', 'barestho'); ?></p>
        </div>
        <?php
    }
}