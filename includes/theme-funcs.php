<?php
// Файл для общих функций темы


// Заголовки Last-Modified и If-Modified-Since
function last_modified() {
    $modified_time = get_the_modified_time('U');
    $modified_gmt  = gmdate('D, d M Y H:i:s', $modified_time) . ' GMT';

    header("Last-Modified: $modified_gmt");

    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);

        if ($if_modified_since >= $modified_time) {

            header("HTTP/1.1 304 Not Modified");
            exit;
        }
    }
}

add_action('template_redirect', 'last_modified');

// Закрытие сайта на тех.обслуживание (раскомментировать только при необходимости)
// add_action('template_redirect', function() {
//     if ( ! current_user_can('manage_options') ) {
//         status_header(503);
//         nocache_headers();
//         wp_die(
//             'Сайт временно закрыт на техническое обслуживание. Пожалуйста, зайдите позже.',
//             'Техническое обслуживание',
//             array( 'response' => 503 )
//         );
//         exit;
//     }
// });

// Получение ссылки на страницу пагинации
function page_url($page) {
    return add_query_arg('cp', $page === 1 ? false : $page);
}


// Редиректы для пагинации
function pagination_redirects() {
    if (!is_post_type_archive()) {
        return;
    }

    if (is_paged()) {
        set_404();
        return;
    }

    if (isset($_GET['cp']) && (int)$_GET['cp'] === 1) {
        wp_safe_redirect(get_post_type_archive_link(get_post_type()), 301);
        exit;
    }
}

add_action('template_redirect', 'pagination_redirects');


// Вызов 404 ошибки
function set_404() {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
}
