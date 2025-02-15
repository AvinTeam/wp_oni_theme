<?php

(defined('ABSPATH')) || exit;

add_action('admin_enqueue_scripts', 'oni_admin_script');

function oni_admin_script()
{

    wp_register_style(
        'jalalidatepicker',
        ONI_VENDOR . 'jalalidatepicker/jalalidatepicker.min.css',
        [  ],
        '0.9.6'
    );
    wp_register_script(
        'jalalidatepicker',
        ONI_VENDOR . 'jalalidatepicker/jalalidatepicker.min.js',
        [  ],
        '0.9.6',
        true
    );

    wp_enqueue_style(
        'oni_admin',
        ONI_CSS . 'admin.css',
        [ 'jalalidatepicker' ],
        ONI_VERSION
    );

    wp_enqueue_media();

    wp_enqueue_script(
        'oni_admin',
        ONI_JS . 'admin.js',
        [ 'jquery', 'jalalidatepicker' ],
        ONI_VERSION,
        true
    );

    wp_localize_script(
        'oni_admin',
        'oni_js',
        [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ajax-nonce'),
         ]
    );

}

add_action('wp_enqueue_scripts', 'oni_style');

function oni_style()
{
    wp_register_style(
        'bootstrap',
        ONI_VENDOR . 'bootstrap/bootstrap.min.css',
        [  ],
        '5.3.3'
    );
    wp_register_style(
        'bootstrap.rtl',
        ONI_VENDOR . 'bootstrap/bootstrap.rtl.min.css',
        [ 'bootstrap' ],
        '5.3.3'
    );
    wp_register_style(
        'bootstrap.icons',
        ONI_VENDOR . 'bootstrap/bootstrap-icons.min.css',
        [ 'bootstrap' ],
        '1.11.3'
    );
    wp_register_script(
        'bootstrap',
        ONI_VENDOR . 'bootstrap/bootstrap.min.js',
        [  ],
        '5.3.3',
        true
    );

    wp_register_style(
        'select2',
        ONI_VENDOR . 'select2/select2.min.css',
        [ 'bootstrap' ],
        '4.1.0-rc.0'
    );
    wp_register_script(
        'select2',
        ONI_VENDOR . 'select2/select2.min.js',
        [  ],
        '4.1.0-rc.0',
        true
    );

    wp_register_style(
        'jalalidatepicker',
        ONI_VENDOR . 'jalalidatepicker/jalalidatepicker.min.css',
        [  ],
        '0.9.6'
    );
    wp_register_script(
        'jalalidatepicker',
        ONI_VENDOR . 'jalalidatepicker/jalalidatepicker.min.js',
        [  ],
        '0.9.6',
        true
    );

    wp_enqueue_style(
        'oni_style',
        ONI_CSS . 'public.css',
        [ 'bootstrap.rtl', 'bootstrap.icons', 'select2', 'jalalidatepicker' ],
        ONI_VERSION
    );

    wp_enqueue_script(
        'oni_js',
        ONI_JS . 'public.js',
        [ 'jquery', 'bootstrap', 'select2', 'jalalidatepicker' ],
        ONI_VERSION,
        true
    );

    wp_localize_script(
        'oni_js',
        'oni_js',
        [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ajax-nonce' . oni_cookie()),
            'option'  => oni_start_working(),
            'answers' => (is_user_logged_in()) ? oni_exam() : [  ],

         ]
    );

}
