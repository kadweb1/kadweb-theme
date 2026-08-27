<?php
// Файл для действий с типами записей


// Регстрация типов записей
function register_my_post_types() {
    register_post_type('post-type-name', [
		'label' => null,
		'labels' => [
			'name' => 'Тип записей',
			'singular_name' => 'Статья типа записей',
			'add_new' => 'Добавить статью типа записей',
			'add_new_item' => 'Добавление статьи типа записей',
			'edit_item' => 'Редактирование статьи типа записей',
			'new_item' => 'Новая услуга',
			'view_item' => 'Смотреть статью типа записей',
			'search_items' => 'Искать статью типа записей',
			'not_found' => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name' => 'Тип записей',
		],
		'public' => true,
		'show_in_menu' => true,
		'menu_icon' => 'dashicons-admin-post',
		'has_archive' => true,
		'query_var' => true,
		'show_in_rest' => true,
		'supports' => ['title', 'editor'],
		'rewrite' => ['slug' => 'post-type-slug'],
	]);
}

add_action('init', 'register_my_post_types');


// Регистрация таксономий
function register_my_taxonomies() {
    register_taxonomy('tax-name', ['post-type-name'], [
        'label' => 'Категории постов',
		'labels' => [
			'name' => 'Категории постов',
			'singular_name' => 'Категория постов',
			'search_items' => 'Найти категорию постов',
			'all_items' => 'Все категории постов',
			'view_item' => 'Смотреть категорию постов',
			'parent_item' => 'Родительская категория постов',
			'edit_item' => 'Редактировать категорию постов',
			'update_item' => 'Обновить категорию постов',
			'add_new_item' => 'Добавить категорию постов',
			'new_item_name' => 'Новая категория постов',
			'menu_name' => 'Категории постов',
			'back_to_items' => '← К категориям постов',
		],
		'description' => 'Категории постов',
		'public' => true,
		'show_ui' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => ['slug' => 'tax-slug'],
    ]);
}

add_action('init', 'register_my_taxonomies');


// Получение всех постов типа записи
function get_post_type_items($post_type, $tax_query = [], $per_page = -1, $paged = 1, $parent = 0) {
	if (!$post_type) return;

	$query_args = [
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => $per_page,
		'post_parent' => $parent,
		'paged' => $paged,
	];

	if (!empty($tax_query)) {
		$query_args['tax_query'] = $tax_query;
	}

	$posts = new WP_Query($query_args);

	return $posts;
}