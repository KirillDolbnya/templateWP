<?php

// Проверяем входящие данные
function validate_cart_data($product_id, $quantity) {
    if ($product_id <= 0) {
        return ['error' => 'Некорректный ID товара.'];
    }
    if ($quantity <= 0) {
        return ['error' => 'Некорректное количество товара.'];
    }

    return ['product_id' => $product_id, 'quantity' => $quantity];
}

// Получаем товар по id
function get_wc_product($product_id) {
    return wc_get_product($product_id) ?: null;
}

// Получаем инфо корзины
function get_cart_summary() {
    return [
        'cartQuantity' => WC()->cart->get_cart_contents_count(),
        'cartTotal'    => WC()->cart->get_cart_contents_total(),
    ];
}

// Проверяем кол-во товара на складе и кол-во которое хотим добавить
function check_stock_availability($product, $product_id, $quantity) {
    if ($product->managing_stock()) {
        $stock_quantity = $product->get_stock_quantity();
        if ($stock_quantity !== null && ($quantity + get_cart_item_quantity($product_id)) > $stock_quantity) {
            return "Нельзя добавить больше, чем есть в наличии: {$stock_quantity}.";
        }
    }
    return null;
}

// Получаем кол-во товара в корзине
function get_cart_item_quantity($product_id) {
    $cart = WC()->cart;
    $cart_item_key = $cart->find_product_in_cart($cart->generate_cart_id($product_id));

    return $cart_item_key ? (int) $cart->get_cart_item($cart_item_key)['quantity'] : 0;
}

// Функция добавления товара в корзину
function ajax_add_to_cart() {
    // Валидация данных
    $validated = validate_cart_data((int) ($_POST['product_id'] ?? 0), (int) ($_POST['quantity'] ?? 1));
    if (isset($validated['error'])) {
        wp_send_json_error(['message' => $validated['error']]);
        return;
    }

    $product_id = $validated['product_id'];
    $quantity = $validated['quantity'];

    // Получаем товар
    $product = get_wc_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Товар не найден.']);
        return;
    }

    // Проверяем количество на складе
    $stock_error = check_stock_availability($product, $product_id, $quantity);
    if ($stock_error) {
        wp_send_json_error(['message' => $stock_error]);
        return;
    }

    // Добавляем товар в корзину
    if (WC()->cart->add_to_cart($product_id, $quantity) === false) {
        wp_send_json_error(['message' => 'Ошибка добавления товара в корзину.']);
        return;
    }

    // Получаем обновленные данные корзины
    $cart_summary = get_cart_summary();

    // Отправляем успешный ответ
    wp_send_json_success([
        'productId'    => $product_id,
        'productQty'   => get_cart_item_quantity($product_id),
        'cartQuantity' => $cart_summary['cartQuantity'],
        'cartTotal'    => $cart_summary['cartTotal'],
        'message'      => 'Товар успешно добавлен в корзину.',
    ]);
}

add_action('wp_ajax_add_to_cart', 'ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'ajax_add_to_cart');

///**
// * AJAX-обработчик обновления количества товара в корзине.
// */
//function productUpdateCart(): void {
//    $validated = validateProductData((int) ($_POST['product_id'] ?? 0), (int) ($_POST['quantity'] ?? 0));
//
//    if (isset($validated['error'])) {
//        wp_send_json_error(['message' => $validated['error']]);
//    }
//
//    $product = getProductById($validated['productId']);
//
//    if (!$product) {
//        wp_send_json_error(['message' => 'Товар не найден.']);
//    }
//
//    $cartItem = getCartItemByProductId($validated['productId']);
//
//    if (!$cartItem) {
//        wp_send_json_error(['message' => 'Товар не найден в корзине.']);
//    }
//
//    if ($product->managing_stock()) {
//        $stockQuantity = $product->get_stock_quantity();
//        if ($stockQuantity !== null && $validated['quantity'] > $stockQuantity) {
//            wp_send_json_error(['message' => "Нельзя добавить больше, чем есть в наличии: {$stockQuantity}."]);
//        }
//    }
//
//    if ($validated['quantity'] > 0) {
//        WC()->cart->set_quantity($cartItem['key'], $validated['quantity']);
//        $message = 'Количество товара обновлено.';
//    } else {
//        WC()->cart->remove_cart_item($cartItem['key']);
//        $message = 'Товар удалён из корзины.';
//    }
//
//    wp_send_json_success([
//        'productId' => $validated['productId'],
//        'cartInfo'  => getCartInfo(),
//        'message'   => $message,
//    ]);
//}
//
//add_action('wp_ajax_remove_from_cart', 'productRemoveFromCart');
//add_action('wp_ajax_nopriv_remove_from_cart', 'productRemoveFromCart');
//
///**
// * AJAX-обработчик удаления товара из корзины.
// */
//function productRemoveFromCart(): void {
//    $validated = validateProductData((int) ($_POST['product_id'] ?? 0));
//
//    if (isset($validated['error'])) {
//        wp_send_json_error(['message' => $validated['error']]);
//    }
//
//    $cartItem = getCartItemByProductId($validated['productId']);
//
//    if (!$cartItem) {
//        wp_send_json_error(['message' => 'Товар не найден в корзине.']);
//    }
//
//    WC()->cart->remove_cart_item($cartItem['key']);
//
//    wp_send_json_success([
//        'productId' => $validated['productId'],
//        'cartInfo'  => getCartInfo(),
//        'message'   => 'Товар успешно удалён из корзины.',
//    ]);
//}