<?php
// Файл для изменений интерфейса админки


// Отключение Gutenberg для страниц (кроме документов)
function disable_editor_for_pages($use_block_editor, $post_type) {
    if ($post_type === 'page') {
        $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : null);

        if ($post_id) {
            $template = get_page_template_slug($post_id);
            if ($template !== 'page-document.php') {
                return false;
            }
        }
    }
    return $use_block_editor;
}

add_filter('use_block_editor_for_post_type', 'disable_editor_for_pages', 10, 2);


// Отключение Clssic Editor для страниц (кроме документов)
function remove_classic_editor_support_for_pages() {
    $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : null);

    if ($post_id) {
        $template = get_page_template_slug($post_id);

        if ($template !== 'page-document.php') {
            remove_post_type_support('page', 'editor');
        }
    }
}

add_action('load-post.php', 'remove_classic_editor_support_for_pages');
add_action('load-post-new.php', 'remove_classic_editor_support_for_pages');


// Подстановка плейсхолдеров к заголовкам типов записей
function my_custom_placeholders($text, $post) {
    switch ($post->post_type) {
        case 'posts' :
            $text = 'Введите название поста';
            break;
    }

    return $text;
}

add_filter('enter_title_here', 'my_custom_placeholders', 10, 2);