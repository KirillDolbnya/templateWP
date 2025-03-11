<?php

add_filter( 'woocommerce_account_menu_items', function( $items ) {
    unset( $items['dashboard'] );      // Удалить "Консоль"
    unset( $items['downloads'] );      // Удалить "Загрузки"
    unset( $items['edit-address'] );   // Удалить "Адреса"
    unset( $items['payment-methods'] );// Удалить "Способы оплаты"
    unset( $items['edit-account'] );   // Удалить "Детали аккаунта"
    return $items;
}, 99 );

function custom_enqueue_woocommerce_styles() {
    if (is_page_template('my-account.php')) {
        wp_enqueue_style('woocommerce-general');
        wp_enqueue_style('woocommerce-layout');
        wp_enqueue_style('woocommerce-smallscreen', '', array(), '', 'only screen and (max-width: 768px)');
    }
}
add_action('wp_enqueue_scripts', 'custom_enqueue_woocommerce_styles');

add_action('wp_logout', function() {
    wp_redirect(home_url());
    exit;
});

add_action('template_redirect', function() {
    if (is_page('my-account') && !is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }
});