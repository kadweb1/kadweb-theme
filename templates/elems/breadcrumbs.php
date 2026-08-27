<?php
// Генерация 3-х уровневых хлебных крошек

if (is_home()) {
    return;
}

$items = [
    [
        'title' => 'Главная',
        'url'   => home_url(),
    ]
];

if (is_page()) {
    $parents = array_reverse(get_ancestors(get_the_ID()));

    foreach ($parents as $parent_id) {
        $items[] = [
            'title' => get_the_title($parent_id),
            'url'   => get_permalink($parent_id),
        ];
    }
}

if (is_single()) {
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object($post_type);

    if ($post_type_obj && $post_type_obj->has_archive) {
        $items[] = [
            'title' => $post_type_obj->labels->name,
            'url'   => get_post_type_archive_link($post_type),
        ];
    }
}

if (is_tax()) {
    $term = get_queried_object();
    $taxonomy = get_taxonomy($term->taxonomy);

    if (!empty($taxonomy->object_type)) {
        $post_type = $taxonomy->object_type[0];
        $post_type_obj = get_post_type_object($post_type);

        if ($post_type_obj && $post_type_obj->has_archive) {
            $items[] = [
                'title' => $post_type_obj->labels->name,
                'url'   => get_post_type_archive_link($post_type),
            ];
        }
    }

    $items[] = [
        'title' => single_term_title('', false),
        'url'   => get_term_link($term),
    ];
}

if (is_post_type_archive()) {
    $post_type = get_queried_object();

    $items[] = [
        'title' => $post_type->labels->name,
        'url'   => get_post_type_archive_link($post_type->name),
    ];
} elseif (is_singular()) {
    $items[] = [
        'title' => get_the_title(),
        'url'   => get_permalink(),
    ];
}
?>

<nav class="breadcrumbs">
    <ul class="breadcrumbs__list">
        <?php foreach ($items as $index => $item) : ?>
            <li class="breadcrumbs__item">
                <?php if ($index === array_key_last($items)) : ?>
                    <span><?= esc_html($item['title']); ?></span>
                <?php else : ?>
                    <a href="<?= esc_url($item['url']); ?>">
                        <?= esc_html($item['title']); ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>