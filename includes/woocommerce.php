<?php
// Действия с WooCommerce


// Добавление поддержи WooCommerce в тему
add_action('after_setup_theme', function () {
	add_theme_support('woocommerce');
});