<?php
// Пагинация

$classes = $args['classes'] ?? '';
$paged = $args['paged'] ?? null;
$total = $args['total'] ?? null;

if (!$paged || !$total) {
    return;
}
?>

<nav class="<?= $classes; ?> pag">
    <?php
        $pagination = paginate_links([
        'base'      => add_query_arg('cp', '%#%'),
        'format'    => '',
        'total'     => $total,
        'current'   => $paged,
        'end_size'  => 1,
        'prev_text' => '<i class="_icon-arrow-left"></i>',
        'next_text' => '<i class="_icon-arrow-right"></i>',
        'type'      => 'list',
    ]);

    $pagination = str_replace('?cp=1', '', $pagination);

    echo $pagination;
    ?>
</nav>