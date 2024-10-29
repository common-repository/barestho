<?php

if ( ! defined( 'ABSPATH' ) ) exit; 

const barestho_plugin_root_dir = __DIR__ . "/..";
const barestho_env_file_path = barestho_plugin_root_dir . "/env.php";

function barestho_loadenv() {
    if (file_exists(barestho_env_file_path)) {
        return require barestho_env_file_path;
    } else {
        throw new Exception("Configuration file not found.");
    }
}

$barestho_config = barestho_loadenv();

// Définir les variables d'environnement pour l'utilisation dans le plugin
$barestho_port = $barestho_config['PORT'];
$barestho_protocol = $barestho_config['PROTOCOL'];
$barestho_domain_reservation = $barestho_config['DOMAIN_RESERVATION'];

// Fonction réutilisable pour générer l'URL du widget
function barestho_generate_widget_url($view, $restaurant_id, $theme_color, $logo_choice, $barestho_protocol, $barestho_domain_reservation, $barestho_port) {
    $logo_params = [
        '1' => '&barestho-logo=%23252332&barestho-alternate=false&text-color=%23FFFFFF',
        '2' => '&barestho-logo=%23DC0044&barestho-alternate=false&text-color=%23FFFFFF',
        '3' => '&barestho-logo=%23DC0044&barestho-alternate=true&text-color=%23252332',
    ];

    return $barestho_protocol . '://' . esc_attr($restaurant_id) . '.' . $barestho_domain_reservation . ':' . $barestho_port . '/?view=' . $view . '&primary-color=' . rawurlencode($theme_color) . '&secondary-color=' . rawurlencode($theme_color) . $logo_params[$logo_choice];
}

function barestho_getoptions() {
    $barestho_options = get_option('barestho_settings');
    if (!is_array($barestho_options)) {
        $barestho_options = [];
    }
    return [
        'restaurant_id' => isset($barestho_options['barestho_restaurant_id']) ? $barestho_options['barestho_restaurant_id'] : '',
        'theme_color' => !empty($barestho_options['barestho_theme_color']) ? $barestho_options['barestho_theme_color'] : '',
        'logo_choice' => isset($barestho_options['barestho_logo_choice']) ? $barestho_options['barestho_logo_choice'] : '1',
        'custom_widget' => !empty($barestho_options['barestho_custom_widget']),
        'reservation_button' => !empty($barestho_options['barestho_reservation_button']),
        'popup_widget' => !empty($barestho_options['barestho_popup_widget']),
    ];
}

function barestho_generate_iframe($view, $barestho_protocol, $barestho_domain_reservation, $barestho_port, $barestho_options) {
    $widget_url = barestho_generate_widget_url(
        $view,
        esc_attr($barestho_options['restaurant_id']),
        esc_attr($barestho_options['theme_color']),
        esc_attr($barestho_options['logo_choice']),
        esc_attr($barestho_protocol),
        esc_attr($barestho_domain_reservation),
        esc_attr($barestho_port)
    );

    return '<iframe id="' . esc_attr($barestho_options['restaurant_id']) . '" class="barestho-widget" src="' . esc_url($widget_url) . '"></iframe>';
}

function barestho_toggle() {
    global $barestho_port, $barestho_protocol, $barestho_domain_reservation;

    $barestho_options = barestho_getoptions();

    // Vérifier et échapper les options avant de les utiliser
    if (empty($barestho_options['restaurant_id']) || !$barestho_options['reservation_button']) {
        return;
    }

    $barestho_allowed_tags = array(
        'iframe' => array(
            'id' => array(),
            'class' => array(),
            'src' => array(),
            'frameborder' => array(),
            'style' => array(),
            'width' => array(),
            'height' => array(),
            'allow' => array(),
            'allowfullscreen' => array(),
        ),
        'div' => array(
            'style' => array(),
            'class' => array(),
        ),
    );
    
    echo wp_kses(
        barestho_generate_iframe(
            'toggle',
            esc_attr($barestho_protocol),
            esc_attr($barestho_domain_reservation),
            esc_attr($barestho_port),
            $barestho_options
        ), 
        $barestho_allowed_tags
    );
}

function barestho_inpage() {
    global $barestho_port, $barestho_protocol, $barestho_domain_reservation;

    $barestho_options = barestho_getoptions();
    if (empty($barestho_options['restaurant_id']) || !$barestho_options['custom_widget']) {
        return;
    }

    return barestho_generate_iframe('in-page', $barestho_protocol, $barestho_domain_reservation, $barestho_port, $barestho_options);
}
add_shortcode('barestho_inpage', 'barestho_inpage');

function barestho_preview() {
    global $barestho_port, $barestho_protocol, $barestho_domain_reservation;

    $barestho_options = barestho_getoptions();
    if (empty($barestho_options['restaurant_id'])) {
        return;
    }

    $widget_url = barestho_generate_widget_url('in-page', $barestho_options['restaurant_id'], $barestho_options['theme_color'], $barestho_options['logo_choice'], $barestho_protocol, $barestho_domain_reservation, $barestho_port);

    return '
        <div style="position: relative; width: 360px; height: 550px; border-radius: 20px; background-color: white;">
            <iframe id="' . esc_attr($barestho_options['restaurant_id']) . '" class="barestho-widget" src="' . esc_url($widget_url) . '" frameborder="0" style="width: 100%; height: 100%; pointer-events: none; border-radius: 20px;"></iframe>
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0); z-index: 10;"></div>
        </div>';
}

function barestho_popup() {
    global $barestho_port, $barestho_protocol, $barestho_domain_reservation;

    $barestho_options = barestho_getoptions();

    if (empty($barestho_options['restaurant_id']) || !$barestho_options['popup_widget']) {
        return;
    }

    $barestho_escaped_protocol = esc_attr($barestho_protocol);
    $barestho_escaped_domain = esc_attr($barestho_domain_reservation);
    $barestho_escaped_port = esc_attr($barestho_port);

    $barestho_allowed_tags = array(
        'iframe' => array(
            'id' => array(),
            'class' => array(),
            'src' => array(),
            'frameborder' => array(),
            'style' => array(),
            'width' => array(),
            'height' => array(),
            'allow' => array(),
            'allowfullscreen' => array(),
        ),
        'div' => array(
            'style' => array(),
            'class' => array(),
        ),
    );
    
    echo wp_kses(
        barestho_generate_iframe(
            'popup',
            esc_attr($barestho_escaped_protocol),
            esc_attr($barestho_escaped_domain),
            esc_attr($barestho_escaped_port),
            $barestho_options
        ), 
        $barestho_allowed_tags
    );
}
add_action('wp_footer', 'barestho_popup');

?>