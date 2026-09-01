<?php
// Генерация хлебных крошек.
defined('ABSPATH') || exit;


if (is_home()) {
	return;
}

$items = [
	[
		'title' => 'Главная',
		'url'   => home_url('/'),
	],
];

function breadcrumbs_add_term_ancestors(array &$items, WP_Term $term): void {
	$ancestors = get_ancestors($term->term_id, $term->taxonomy);
	$ancestors = array_reverse($ancestors);

	foreach ($ancestors as $ancestor_id) {
		$ancestor = get_term($ancestor_id, $term->taxonomy);

		if (!$ancestor instanceof WP_Term) {
			continue;
		}

		$items[] = [
			'title' => $ancestor->name,
			'url'   => get_term_link($ancestor),
		];
	}

	$items[] = [
		'title' => $term->name,
		'url'   => get_term_link($term),
	];
}


if (function_exists('is_shop') && is_shop()) {

	$items[] = [
		'title' => get_the_title(wc_get_page_id('shop')),
		'url'   => get_permalink(wc_get_page_id('shop')),
	];

} elseif (function_exists('is_product_category') && is_product_category()) {

	$term = get_queried_object();

	if ($term instanceof WP_Term) {

		$shop_id = wc_get_page_id('shop');

		if ($shop_id > 0) {
			$items[] = [
				'title' => get_the_title($shop_id),
				'url'   => get_permalink($shop_id),
			];
		}

		breadcrumbs_add_term_ancestors($items, $term);
	}

} elseif (function_exists('is_product_tag') && is_product_tag()) {

	$term = get_queried_object();

	if ($term instanceof WP_Term) {

		$shop_id = wc_get_page_id('shop');

		if ($shop_id > 0) {
			$items[] = [
				'title' => get_the_title($shop_id),
				'url'   => get_permalink($shop_id),
			];
		}

		$items[] = [
			'title' => $term->name,
			'url'   => get_term_link($term),
		];
	}

} elseif (function_exists('is_product') && is_product()) {

	$shop_id = wc_get_page_id('shop');

	if ($shop_id > 0) {
		$items[] = [
			'title' => get_the_title($shop_id),
			'url'   => get_permalink($shop_id),
		];
	}

	$product = wc_get_product(get_the_ID());

	if ($product) {

		$terms = get_the_terms(get_the_ID(), 'product_cat');

		if (!empty($terms) && !is_wp_error($terms)) {
			$deepest_term = null;
			$max_depth    = -1;

			foreach ($terms as $term) {
				$depth = count(get_ancestors($term->term_id, 'product_cat'));

				if ($depth > $max_depth) {
					$max_depth    = $depth;
					$deepest_term = $term;
				}
			}

			if ($deepest_term) {
				breadcrumbs_add_term_ancestors($items, $deepest_term);
			}
		}
	}

	$items[] = [
		'title' => get_the_title(),
		'url'   => get_permalink(),
	];

} elseif (is_page()) {

	$ancestors = array_reverse(
		get_ancestors(get_the_ID(), 'page')
	);

	foreach ($ancestors as $parent_id) {
		$items[] = [
			'title' => get_the_title($parent_id),
			'url'   => get_permalink($parent_id),
		];
	}

	$items[] = [
		'title' => get_the_title(),
		'url'   => get_permalink(),
	];

} elseif (is_tax()) {

	$term = get_queried_object();

	if ($term instanceof WP_Term) {

		$taxonomy = get_taxonomy($term->taxonomy);

		if ($taxonomy && !empty($taxonomy->object_type)) {

			$post_type = $taxonomy->object_type[0];
			$post_type_obj = get_post_type_object($post_type);

			if (
				$post_type_obj &&
				$post_type_obj->has_archive
			) {
				$items[] = [
					'title' => $post_type_obj->labels->name,
					'url'   => get_post_type_archive_link($post_type),
				];
			}
		}

		breadcrumbs_add_term_ancestors($items, $term);
	}

} elseif (is_post_type_archive()) {

	$post_type = get_queried_object();

	if ($post_type instanceof WP_Post_Type) {

		$items[] = [
			'title' => $post_type->labels->name,
			'url'   => get_post_type_archive_link($post_type->name),
		];
	}

} elseif (is_singular()) {

	$post_type = get_post_type();
	$post_type_obj = get_post_type_object($post_type);

	if (
		$post_type_obj &&
		$post_type_obj->has_archive
	) {
		$items[] = [
			'title' => $post_type_obj->labels->name,
			'url'   => get_post_type_archive_link($post_type),
		];
	}

	$items[] = [
		'title' => get_the_title(),
		'url'   => get_permalink(),
	];
}

?>

<nav class="breadcrumbs" aria-label="Хлебные крошки">
	<ul class="breadcrumbs__list">

		<?php foreach ($items as $index => $item) : ?>

			<li class="breadcrumbs__item">

				<?php if ($index === array_key_last($items)) : ?>

					<span aria-current="page">
						<?= esc_html($item['title']); ?>
					</span>

				<?php else : ?>

					<a href="<?= esc_url($item['url']); ?>">
						<?= esc_html($item['title']); ?>
					</a>

				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>
</nav>