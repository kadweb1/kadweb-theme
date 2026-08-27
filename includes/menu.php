<?php
// Файл действий с меню


// Регистрация областей меню
function register_menu() {
    register_nav_menus([
        'menu-header' => 'Меню в шапке',
        'menu-burger' => 'Меню в бургере',
        'menu-footer' => 'Меню в подвале',
    ]);
}

add_action('after_setup_theme', 'register_menu');


// Получение страниц меню
function get_menu_items($menu_location) {
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations[$menu_location];
    
    if (!$menu_id) return;

    $menu_items = wp_get_nav_menu_items($menu_id);

    return $menu_items;
}