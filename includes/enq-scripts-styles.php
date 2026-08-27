<?php
// Файл с подключение стилей и скриптов


// Подключение стилей и скриптов самого сайта
function my_site_scripts() {
    // SWIPER
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
    );

    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    );

    // Custom
    wp_enqueue_style(
        'fonts-css',
        THEME_URL . '/assets/css/style-fonts.css',
        [],
        filemtime(THEME_PATH . '/assets/css/style-fonts.css')
    );

    wp_enqueue_style(
        'main-css',
        THEME_URL . '/assets/css/style.css',
        [],
        filemtime(THEME_PATH . '/assets/css/style.css')
    );

    wp_enqueue_script(
        'main-js',
        THEME_URL . '/assets/js/app.js',
        [],
        filemtime(THEME_PATH . '/assets/js/app.js'),
        true
    );
}

add_action('wp_enqueue_scripts', 'my_site_scripts');

// Подключение стилей и скриптов адмнки
function my_admin_scripts() {
    wp_enqueue_style(
        'admin-css',
        THEME_URL . '/assets/css/style-admin.css',
        [],
        filemtime(THEME_PATH . '/assets/css/style-admin.css')
    );
}

add_action('admin_enqueue_scripts', 'my_admin_scripts');