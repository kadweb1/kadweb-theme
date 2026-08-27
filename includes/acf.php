<?php
// Файл действий с плагином ACF


// Регистрация страниц с опциями
function my_options_pages() {
    if (!function_exists('acf_add_options_page')) return;

    acf_add_options_page([
        'menu_title' => __('Общее редактирование'),
        'page_title' => __('Общее редактирование'),
        'icon_url' => 'dashicons-admin-post',
        'update_button' => __('Обновить', 'acf'),
        'updated_message' => __('Общее обновлено', 'acf'),
    ]);
}

add_action('init', 'my_options_pages');


// Регистрация кастомной категории блоков в Gutenberg
function my_custom_block_categories($categories, $post) {
    $custom_category = [
        [
            'slug'  => 'custom-blocks',
            'title' => 'Кастомные блоки Gutenberg',
            'icon'  => null,
        ]
    ];

    return array_merge($custom_category, $categories);
}

add_filter('block_categories_all', 'my_custom_block_categories', 10, 2);


// Регистрация кастомных блоков в Gutenberg
function my_gutenberg_blocks() {
    if (!function_exists('acf_register_block_type')) return;

    acf_register_block_type([
        'name' => 'example-block',
        'title' => 'Кастомный блок',
        'description' => 'Пример кастомного блока в редактор Gutenberg',
        'category' => 'custom-blocks',
        'icon' => 'images-alt',
        'keywords' => ['ключ-1', 'ключ-2', 'ключ-3'],
        'render_template' => THEME_PATH . '/templates/variable-blocks/example-block.php',
    ]);
}

add_action('acf/init', 'my_gutenberg_blocks');


// Генерация контента из Flexible Content
function create_content($current_id = null, $is_blog = false) {
    if (!$current_id) {
        global $post; 
        $original_post = $post;

        $current_id = $is_blog ? get_option('page_for_posts') : get_the_ID();
    }

    if (have_rows('page_content', $current_id)) :
        while (have_rows('page_content', $current_id)) : the_row();
            $current_layout = get_row_layout();
            $current_template = locate_template("/templates/variable-blocks/{$current_layout}.php");

            if ($current_template) :
                include $current_template;
            endif;
        endwhile;
    endif;

    if (!$current_id) {
        $post = $original_post;
        setup_postdata($post);
    }
}