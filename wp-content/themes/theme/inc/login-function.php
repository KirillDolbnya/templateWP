<?php

function custom_woo_login_register() {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        wp_send_json_error(['message' => 'Заполните все поля']);
    }

    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Некорректный email']);
    }

    $user = get_user_by('email', $email);

    if ($user) {
        // Авторизация
        if (wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_set_auth_cookie($user->ID, true);
            wp_send_json_success(['message' => 'Авторизация успешна', 'redirect' => wc_get_page_permalink('myaccount')]);
        } else {
            wp_send_json_error(['message' => 'Неверный пароль']);
        }
    } else {
        // Регистрация
        $user_id = wp_create_user($email, $password, $email);
        if (is_wp_error($user_id)) {
            wp_send_json_error(['message' => 'Ошибка при регистрации']);
        }

        // Авторизуем нового пользователя
        wp_set_auth_cookie($user_id, true);
        wp_send_json_success(['message' => 'Регистрация успешна', 'redirect' => wc_get_page_permalink('myaccount')]);
    }
}

add_action('wp_ajax_custom_woo_login_register', 'custom_woo_login_register');
add_action('wp_ajax_nopriv_custom_woo_login_register', 'custom_woo_login_register');
