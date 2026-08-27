<?php
// Файл для обработки ajax запросов с фронта


// Отправка форм
function send_forms() {
    $name = get_checked_form_field('name');
    $phone = get_checked_form_field('phone');

    if (!$name || !$phone) {
        wp_send_json_error(['message' => 'Обязательные поля не заполнены или заполнены некорректно']);
    }

    $page_id = isset($_POST['page_id']) ? filter_var(trim($_POST['page_id']), FILTER_VALIDATE_INT) : null;

    // Отправка на Email

    $to = trim(get_field('forms_email', 'options'));
    $subject = 'Заявка с сайта Stratum OAK';
    $email_headers = 'Content-type: text/html; charset=utf-8';

    $email_message = "
        <b>ФИО:</b> {$name} <br>
        <b>Телефон:</b> {$phone} <br>
    ";

    if ($page_id) {
        $page_link = get_the_permalink($page_id);
        $email_message .= "<b>Форма отправлена со страницы:</b> {$page_link}";
    }

    $success_email = wp_mail($to, $subject, $email_message, $email_headers);

    if ($success_email) {
        wp_send_json_success();
    } else {
        wp_send_json_error(['message' => 'Ошибка при отправке формы']);
    }
}

add_action('wp_ajax_send_forms', 'send_forms');
add_action('wp_ajax_nopriv_send_forms', 'send_forms');


// Проверка поля на существование и пустоту
function get_checked_form_field($field_name) {
    if (isset($_POST[$field_name]) && !empty(trim($_POST[$field_name]))) {
        return sanitize_text_field(trim($_POST[$field_name]));
    }

    return null;
}
