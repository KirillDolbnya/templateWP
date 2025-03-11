<?php

// Включаем поддержку миниатюр и заголовков
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )
);

// Подключение стилей и скриптов
function theme_enqueue_styles() {
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/dist/css/style.min.css');
    wp_enqueue_style('style', get_stylesheet_uri() );

    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/dist/js/main.min.js', array(), null, true);
    wp_localize_script('theme-scripts', 'ajaxData', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

// Регистрация логотипа
function theme_custom_logo_setup() {
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
}
add_action('after_setup_theme', 'theme_custom_logo_setup');

// Регистрация меню
function register_custom_menus() {
    register_nav_menu('header-menu', __('Header Menu'));

    register_nav_menu('footer-menu', __('Footer Menu'));

    register_nav_menu('burger-menu', __('Burger Menu'));
}
add_action('init', 'register_custom_menus');

// Разрешаем загрузку svg
function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

// Корзина
require get_template_directory() . '/inc/cart-functions.php';

// Оформление заказа
require get_template_directory() . '/inc/order-functions.php';

// Отправка на почту
require get_template_directory() . '/inc/form-functions.php';

// Авторизация
require get_template_directory() . '/inc/login-function.php';

// Аккаунт
require get_template_directory() . '/inc/account-functions.php';