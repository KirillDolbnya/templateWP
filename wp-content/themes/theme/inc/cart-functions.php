<?php

// Добавление товара в коризну
function ajax_add_to_cart() {
    // Получаем и валидируем данные
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id <= 0 || $quantity < 0) {
        wp_send_json_error(['message' => 'Некорректные данные товара.']);
    }

    // Получаем объект товара
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Товар не найден.']);
    }

    // Проверяем наличие на складе
    if (!$product->is_in_stock()) {
        wp_send_json_error(['message' => 'Товар отсутствует на складе.']);
    }

    if ($product->managing_stock() && $quantity > $product->get_stock_quantity()) {
        wp_send_json_error(['message' => 'Недостаточно товара на складе.']);
    }

    // Добавляем товар в корзину
    $cart = WC()->cart;
    if ($cart->add_to_cart($product_id, $quantity) === false) {
        wp_send_json_error(['message' => 'Ошибка добавления товара в корзину.']);
    }

    // Отправляем успешный ответ
    wp_send_json_success([
        'productId'         => $product_id,
        'productQty'        => $quantity,
        'cartTotalQty'      => $cart->get_cart_contents_count(),
        'cartTotalPrice'    => $cart->get_cart_contents_total(),
        'message'           => 'Товар успешно добавлен в корзину.',
    ]);
}

add_action('wp_ajax_add_to_cart', 'ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'ajax_add_to_cart');

// Обновление товара в корзине
function ajax_update_cart(): void {
    // Получаем и валидируем данные
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id <= 0 || $quantity < 0) {
        wp_send_json_error(['message' => 'Некорректные данные товара.']);
    }

    // Получаем объект товара
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Товар не найден.']);
    }

    // Проверяем наличие на складе
    if (!$product->is_in_stock()) {
        wp_send_json_error(['message' => 'Товар отсутствует на складе.']);
    }

    if ($product->managing_stock() && $quantity > $product->get_stock_quantity()) {
        wp_send_json_error(['message' => 'Недостаточно товара на складе.']);
    }

    // Получаем ключ товара в корзине
    $cart = WC()->cart;
    $cart_item_key = false;
    foreach ($cart->get_cart() as $key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $cart_item_key = $key;
            break;
        }
    }

    if (!$cart_item_key) {
        wp_send_json_error(['message' => 'Товар отсутствует в корзине.']);
    }

    if ($quantity > 0) {
        $cart->set_quantity($cart_item_key, $quantity);
        $cart_item_qty = $quantity;
        $message = 'Количество товара обновлено.';
    } else {
        $cart->remove_cart_item($cart_item_key);
        $cart_item_qty = 0;
        $message = 'Товар удалён из корзины.';
    }

    // Отправляем успешный ответ
    wp_send_json_success([
        'productId'         => $product_id,
        'productQty'        => $cart_item_qty,
        'cartTotalQty'      => $cart->get_cart_contents_count(),
        'cartTotalPrice'    => $cart->get_cart_contents_total(),
        'message'           => $message,
    ]);
}

add_action('wp_ajax_update_cart', 'ajax_update_cart');
add_action('wp_ajax_nopriv_update_cart', 'ajax_update_cart');

// Удаление товара из корзины
function ajax_remove_cart(): void {
    // Получаем и валидируем данные
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

    if($product_id <= 0){
        wp_send_json_error(['message' => 'Некорректные данные товара.']);
    }

    // Получаем ключ товара в корзине
    $cart = WC()->cart;
    $cart_item_key = false;
    foreach ($cart->get_cart() as $key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $cart_item_key = $key;
            break;
        }
    }

    if (!$cart_item_key) {
        wp_send_json_error(['message' => 'Товар отсутствует в корзине.']);
    }

    WC()->cart->remove_cart_item($cart_item_key);

    // Отправляем успешный ответ
    wp_send_json_success([
        'productId' => $product_id,
        'cartTotalQty'      => $cart->get_cart_contents_count(),
        'cartTotalPrice'    => $cart->get_cart_contents_total(),
        'message'   => 'Товар успешно удалён из корзины.',
    ]);
}

add_action('wp_ajax_remove_from_cart', 'ajax_remove_cart');
add_action('wp_ajax_nopriv_remove_from_cart', 'ajax_remove_cart');