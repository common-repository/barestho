<?php

if ( ! defined( 'ABSPATH' ) ) exit; 

add_action('admin_menu', 'barestho_add_admin_menu');
add_action('admin_init', 'barestho_settings_init');

function barestho_add_admin_menu() {
    add_menu_page('Barestho', 'Barestho', 'manage_options', 'barestho', 'barestho_options_page');
}

function barestho_settings_init() {
    register_setting('pluginPage', 'barestho_settings');

    add_settings_section(
        'barestho_settings_section',
        '',
        '',
        'pluginPage'
    );

    add_settings_field(
        'barestho_restaurant_id',
        esc_html__('Restaurant ID', 'barestho'),
        'barestho_restaurant_id_render',
        'pluginPage',
        'barestho_settings_section'
    );

    add_settings_field(
        'barestho_theme_color',
        esc_html__('Restaurant Color Code', 'barestho'),
        'barestho_theme_color_render',
        'pluginPage',
        'barestho_settings_section'
    );

    add_settings_field(
        'barestho_logo_choice',
        esc_html__('Choix du logo Barestho', 'barestho'),
        'barestho_logo_choice_render',
        'pluginPage',
        'barestho_settings_section'
    );    

    add_settings_section(
        'barestho_display_section',
        '',
        '',
        'pluginPage'
    );

    add_settings_field(
        'barestho_reservation_button',
        esc_html__('Mode toggle', 'barestho'),
        'barestho_reservation_button_render',
        'pluginPage',
        'barestho_display_section'
    );

    add_settings_field(
        'barestho_popup_widget',
        esc_html__('Mode popup', 'barestho'),
        'barestho_popup_widget_render',
        'pluginPage',
        'barestho_display_section'
    );

    add_settings_field(
        'barestho_custom_widget',
        esc_html__('Mode in-page', 'barestho'),
        'barestho_custom_widget_render',
        'pluginPage',
        'barestho_display_section'
    );
}


function barestho_restaurant_id_render() {
    $barestho_options = get_option('barestho_settings');
    if (!is_array($barestho_options)) {
        $barestho_options = [];
    }
    ?>
<input type='text' name='barestho_settings[barestho_restaurant_id]'
    value='<?php echo isset($barestho_options['barestho_restaurant_id']) ? esc_attr($barestho_options['barestho_restaurant_id']) : ''; ?>'
    placeholder="<?php echo esc_attr__('ID restaurant', 'barestho'); ?>">
<?php
}

function barestho_theme_color_render() {
    $barestho_options = get_option('barestho_settings');
    $theme_color = !empty($barestho_options['barestho_theme_color']) ? $barestho_options['barestho_theme_color'] : 'DC0044'; // Valeur par défaut
    ?>
    <input type="text" id="barestho_theme_color" name="barestho_settings[barestho_theme_color]"
           value="<?php echo esc_attr($theme_color); ?>" class="regular-text" data-default-color="DC0044" />
    <?php
}

function barestho_reservation_button_render() {
    $barestho_options = get_option('barestho_settings');
    $checked = isset($barestho_options['barestho_reservation_button']) && $barestho_options['barestho_reservation_button'] === '1' ? 'checked' : '';

    $label_text = $checked ? __('Disable mode', 'barestho') : __('Enable mode', 'barestho');
    ?>
    <div class="toggle-button">
        <input type="checkbox" id="barestho_reservation_button" name="barestho_settings[barestho_reservation_button]" value="1" <?php echo esc_attr($checked); ?>>
        <label for="barestho_reservation_button" id="barestho_reservation_button_label">
            <?php echo esc_html($label_text); ?>
        </label>
    </div>
    <?php
}

function barestho_custom_widget_render() {
    $barestho_options = get_option('barestho_settings');
    $checked = isset($barestho_options['barestho_custom_widget']) && $barestho_options['barestho_custom_widget'] === '1' ? 'checked' : '';

    $label_text = $checked ? __('Disable mode', 'barestho') : __('Enable mode', 'barestho');
    ?>
    <div class="toggle-button">
        <input type="checkbox" id="barestho_custom_widget" name="barestho_settings[barestho_custom_widget]" value="1" <?php echo esc_attr($checked); ?>>
        <label for="barestho_custom_widget" id="barestho_custom_widget_label">
            <?php echo esc_html($label_text); ?>
        </label>
    </div>
    <?php
}

function barestho_popup_widget_render() {
    $barestho_options = get_option('barestho_settings');
    $checked = isset($barestho_options['barestho_popup_widget']) && $barestho_options['barestho_popup_widget'] === '1' ? 'checked' : '';

    $label_text = $checked ? __('Disable mode', 'barestho') : __('Enable mode', 'barestho');
    ?>
<div class="toggle-button">
    <input type="checkbox" id="barestho_popup_widget" name="barestho_settings[barestho_popup_widget]" value="1"
        <?php echo esc_attr($checked); ?>>
    <label for="barestho_popup_widget" id="barestho_popup_widget_label">
        <?php echo esc_html($label_text); ?>
    </label>
</div>
<?php
}

function barestho_logo_choice_render() {
    $barestho_options = get_option('barestho_settings');
    $logo_choice = isset($barestho_options['barestho_logo_choice']) ? $barestho_options['barestho_logo_choice'] : '1';
    $theme_color = !empty($barestho_options['barestho_theme_color']) ? $barestho_options['barestho_theme_color'] : 'DC0044'; // Valeur par défaut
    ?>

<div style="display: flex; align-items: center;">
    <label style="display: flex; align-items: center; margin-right: 15px;">
        <input type="radio" name="barestho_settings[barestho_logo_choice]" value="1"
            <?php checked($logo_choice, '1'); ?>>
        <span class="barestho-color-span"
            style="display: inline-block; border-radius: 10px; background-color: <?php echo esc_attr($theme_color); ?>; padding: 10px; margin-left: 5px;">
            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/1.png'); ?>" width="20"
                style="display: block; margin: 0 auto;">
        </span>
    </label>
    <label style="display: flex; align-items: center; margin-right: 15px;">
        <input type="radio" name="barestho_settings[barestho_logo_choice]" value="2"
            <?php checked($logo_choice, '2'); ?>>
        <span class="barestho-color-span"
            style="display: inline-block; border-radius: 10px; padding: 10px; margin-left:5px;">
            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/2.png'); ?>" width="20"
                style="display: block; margin: 0 auto;">
        </span>
    </label>
    <label style="display: flex; align-items: center; margin-right: 15px;">
        <input type="radio" name="barestho_settings[barestho_logo_choice]" value="3"
            <?php checked($logo_choice, '3'); ?>>
        <span class="barestho-color-span"
            style="display: inline-block; border-radius: 10px; padding: 10px; margin-left:5px;">
            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/3.png'); ?>" width="20"
                style="display: block; margin: 0 auto;">
        </span>
    </label>
</div>

<?php
}

function barestho_options_page() {
    ?>
<div class="barestho-container">
    <div class="container-logo">
        <h2>
            <img class="barestho-logo-img"
                src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/barestho.png'); ?>">
        </h2>
        <p>
            <a href="https://youtu.be/ITknOS3g7Eg" target="_blank">
                <?php esc_html_e('How to configure the plugin and display the booking widget on your site?', 'barestho'); ?>
            </a>
        </p>
    </div>

    <form action='<?php echo esc_url( admin_url( 'options.php' ) ); ?>' method='post'>
        <?php settings_fields( 'pluginPage' ); ?>

        <div class="container-preview">
            <table class="barestho-table2">
                <tr>
                    <th colspan="3"><?php echo esc_html( __( 'Restaurant Settings', 'barestho' ) ); ?></th>
                </tr>
                <tr>
                    <td><?php echo esc_html( __( 'Your Restaurant ID', 'barestho' ) ); ?></td>
                    <td><?php barestho_restaurant_id_render(); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html( __( 'Restaurant Color Code', 'barestho' ) ); ?></td>
                    <td><?php barestho_theme_color_render(); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html( __( 'Barestho Logo Choice', 'barestho' ) ); ?></td>
                    <td><?php barestho_logo_choice_render(); ?></td>
                </tr>
            </table>
        </div>

        <div class="previuw-button">
            <?php submit_button( esc_html( __( 'Preview Changes', 'barestho' ) ) ); ?>
        </div>

        <div class="preview">
            <?php 
    $barestho_allowed_tags = array(
        'div' => array(
            'style' => array(),
        ),
        'iframe' => array(
            'id' => array(),
            'class' => array(),
            'src' => array(),
            'frameborder' => array(),
            'style' => array(),
        ),
    );

    echo wp_kses(barestho_preview(), $barestho_allowed_tags); 
    ?>
        </div>

        <table class="barestho-table">
            <tr>
                <th colspan="3" class="barestho-display-header">
                    <?php echo esc_html( __( 'Widget Display', 'barestho' ) ); ?></th>
            </tr>
            <tr>
                <td><?php echo esc_html( __( 'Toggle mode', 'barestho' ) ); ?></td>
                <td>
                    <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'assets/basdroite2.png' ); ?>"
                        style="width: 300px; padding-top:10px; padding:10px">
                    <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'assets/basdroite.png' ); ?>"
                        style="width: 300px; padding-top:10px; padding:10px">
                </td>
                <td><?php barestho_reservation_button_render(); ?></td>
            </tr>

            <tr>
                <td><?php echo esc_html( __( 'Popup mode', 'barestho' ) ); ?></td>
                <td class="popup-widget-content">
                    <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'assets/popup.png' ); ?>"
                        class="popup-image">
                    <div class="popup-text">
                        <ol>
                            <li><?php echo esc_html( __( 'Create or modify a button.', 'barestho' ) ); ?></li>
                            <li><?php echo esc_html( __( "Add the barestho.openPopup('id of your restaurant') function to the onclick of your button. Make sure you enter the id of your restaurant, for example ('barestho').", 'barestho' ) ); ?>
                            </li>
                            <li><?php echo esc_html( __( 'Save and check that the button works correctly.', 'barestho' ) ); ?>
                            </li>
                        </ol>
                        <p><?php echo esc_html( __( 'Example:', 'barestho' ) ); ?></p>
                        <code>&lt;button onclick="barestho.openPopup('<?php echo esc_js('barestho'); ?>')"&gt;<?php echo esc_html( __( 'My Button', 'barestho' ) ); ?>&lt;/button&gt;</code>
                    </div>
                </td>
                <td><?php barestho_popup_widget_render(); ?></td>
            </tr>

            <tr>
                <td><?php echo esc_html( __( 'In-page mode', 'barestho' ) ); ?></td>
                <td class="popup-widget-content">
                    <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'assets/iframe.png' ); ?>"
                        style="width: 300px; padding-top:10px; padding:10px">
                    <div class="popup-text">
                        <ol>
                            <li><?php echo esc_html( __( 'Create a "Custom HTML" tag.', 'barestho' ) ); ?></li>
                            <li><?php echo esc_html( __( 'Add <code>[barestho_inpage]</code> inside the tag.', 'barestho' ) ); ?>
                            </li>
                            <li><?php echo esc_html( __( 'Check that the widget appears.', 'barestho' ) ); ?>
                            </li>
                        </ol>
                    </div>
                </td>
                <td><?php barestho_custom_widget_render(); ?></td>
            </tr>
        </table>
        <div class="previuw-button">
            <?php submit_button( esc_html( __( 'Save Changes', 'barestho' ) ) ); ?>
        </div>
    </form>

</div>
<?php
}