<?php

// Отключаем лишний вывод в head
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Включаем поддержку миниатюр и заголовков
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// Регистрация меню
function theme_register_nav_menu() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'theme-textdomain'),
        'footer'  => __('Footer Menu', 'theme-textdomain')
    ]);
}
add_action('after_setup_theme', 'theme_register_nav_menu');


function theme_enqueue_styles() {
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/dist/css/style.min.css');
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/dist/js/main.min.js', array(), '1.0.0', true );

    wp_localize_script('theme-scripts', 'ajaxData', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

// Отключение WP Emojis
function disable_wp_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'disable_wp_emojis');

// Отключение XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Удаление версии WP из HTML-кода
function remove_wp_version() {
    return '';
}
add_filter('the_generator', 'remove_wp_version');








/**
 * Валидирует ID товара и, при необходимости, количество.
 *
 * @param int      $productId ID товара.
 * @param int|null $quantity  Количество товара (необязательно).
 * @return array<string, int|string> Валидированные данные или сообщение об ошибке.
 */
function validateProductData(int $productId, ?int $quantity = null): array {
    if ($productId <= 0) {
        return ['error' => 'Некорректный ID товара.'];
    }

    if ($quantity !== null && $quantity <= 0) {
        return ['error' => 'Количество должно быть больше нуля.'];
    }

    return ['productId' => $productId, 'quantity' => $quantity ?? 1];
}

/**
 * Получает товар по его ID.
 *
 * @param int $productId ID товара.
 * @return WC_Product|null Объект товара или null, если товар не найден.
 */
function getProductById(int $productId): ?WC_Product {
    return wc_get_product($productId) ?: null;
}

/**
 * Получает элемент корзины по ID товара.
 *
 * @param int $productId ID товара.
 * @return array|null Данные элемента корзины или null, если товар не найден в корзине.
 */
function getCartItemByProductId(int $productId): ?array {
    $cart = WC()->cart;
    $cartItemKey = $cart->find_product_in_cart($cart->generate_cart_id($productId));

    return $cartItemKey ? $cart->get_cart_item($cartItemKey) : null;
}

/**
 * Получает информацию о корзине.
 *
 * @return array<string, int|float> Информация о корзине: общее количество товаров и общая сумма.
 */
function getCartInfo(): array {
    $cart = WC()->cart;

    return [
        'totalQuantity' => $cart->get_cart_contents_count(),
        'totalPrice'    => (float) $cart->get_cart_contents_total(),
    ];
}

add_action('wp_ajax_add_to_cart', 'productAddToCart');
add_action('wp_ajax_nopriv_add_to_cart', 'productAddToCart');

/**
 * AJAX-обработчик добавления товара в корзину.
 */
function productAddToCart(): void {
    $validated = validateProductData((int) ($_POST['product_id'] ?? 0), (int) ($_POST['quantity'] ?? 1));

    if (isset($validated['error'])) {
        wp_send_json_error(['message' => $validated['error']]);
    }

    $product = getProductById($validated['productId']);

    if (!$product) {
        wp_send_json_error(['message' => 'Товар не найден.']);
    }

    if ($product->managing_stock()) {
        $stockQuantity = $product->get_stock_quantity();
        if ($stockQuantity !== null && $validated['quantity'] > $stockQuantity) {
            wp_send_json_error(['message' => "Нельзя добавить больше, чем есть в наличии: {$stockQuantity}."]);
        }
    }

    $cartItemKey = WC()->cart->add_to_cart($validated['productId'], $validated['quantity']);

    if (!$cartItemKey) {
        wp_send_json_error(['message' => 'Ошибка добавления товара в корзину.']);
    }

    wp_send_json_success([
        'productId' => $validated['productId'],
        'cartInfo'  => getCartInfo(),
        'message'   => 'Товар успешно добавлен в корзину.',
    ]);
}

add_action('wp_ajax_update_product_quantity', 'productUpdateCart');
add_action('wp_ajax_nopriv_update_product_quantity', 'productUpdateCart');

/**
 * AJAX-обработчик обновления количества товара в корзине.
 */
function productUpdateCart(): void {
    $validated = validateProductData((int) ($_POST['product_id'] ?? 0), (int) ($_POST['quantity'] ?? 0));

    if (isset($validated['error'])) {
        wp_send_json_error(['message' => $validated['error']]);
    }

    $product = getProductById($validated['productId']);

    if (!$product) {
        wp_send_json_error(['message' => 'Товар не найден.']);
    }

    $cartItem = getCartItemByProductId($validated['productId']);

    if (!$cartItem) {
        wp_send_json_error(['message' => 'Товар не найден в корзине.']);
    }

    if ($product->managing_stock()) {
        $stockQuantity = $product->get_stock_quantity();
        if ($stockQuantity !== null && $validated['quantity'] > $stockQuantity) {
            wp_send_json_error(['message' => "Нельзя добавить больше, чем есть в наличии: {$stockQuantity}."]);
        }
    }

    if ($validated['quantity'] > 0) {
        WC()->cart->set_quantity($cartItem['key'], $validated['quantity']);
        $message = 'Количество товара обновлено.';
    } else {
        WC()->cart->remove_cart_item($cartItem['key']);
        $message = 'Товар удалён из корзины.';
    }

    wp_send_json_success([
        'productId' => $validated['productId'],
        'cartInfo'  => getCartInfo(),
        'message'   => $message,
    ]);
}

add_action('wp_ajax_remove_from_cart', 'productRemoveFromCart');
add_action('wp_ajax_nopriv_remove_from_cart', 'productRemoveFromCart');

/**
 * AJAX-обработчик удаления товара из корзины.
 */
function productRemoveFromCart(): void {
    $validated = validateProductData((int) ($_POST['product_id'] ?? 0));

    if (isset($validated['error'])) {
        wp_send_json_error(['message' => $validated['error']]);
    }

    $cartItem = getCartItemByProductId($validated['productId']);

    if (!$cartItem) {
        wp_send_json_error(['message' => 'Товар не найден в корзине.']);
    }

    WC()->cart->remove_cart_item($cartItem['key']);

    wp_send_json_success([
        'productId' => $validated['productId'],
        'cartInfo'  => getCartInfo(),
        'message'   => 'Товар успешно удалён из корзины.',
    ]);
}

add_action('wp_ajax_process_checkout', 'process_checkout');
add_action('wp_ajax_nopriv_process_checkout', 'process_checkout');
/**
 * AJAX-обработчик оформления заказа.
 */
function process_checkout() {
    parse_str($_POST['form_data'], $form_data);

    if (empty($form_data['billing_first_name']) || empty($form_data['billing_email']) || empty($form_data['billing_phone'])) {
        wp_send_json_error(['message' => 'Заполните все обязательные поля']);
        return;
    }

    if (WC()->cart->is_empty()) {
        wp_send_json_error(['message' => 'Ваша корзина пуста']);
        return;
    }

    $order = wc_create_order();
    if (is_wp_error($order)) {
        wp_send_json_error(['message' => 'Ошибка создания заказа']);
        return;
    }

    // Адрес плательщика
    $billing_address = [
        'first_name' => sanitize_text_field($form_data['billing_first_name']),
        'email'      => sanitize_email($form_data['billing_email']),
        'phone'      => sanitize_text_field($form_data['billing_phone']),
    ];
    $order->set_address($billing_address, 'billing');

    // Определение доставки и адреса
    $delivery_method = sanitize_text_field($form_data['delivery'] ?? 'Не указан');
    $payment_method = sanitize_text_field($form_data['payment'] ?? 'Не указан');
    $address = ($delivery_method === 'Курьер') ? sanitize_text_field($form_data['address'] ?? 'Адрес не указан') : '';

    if ($delivery_method === 'Курьер') {
        $shipping_rate = new WC_Shipping_Rate('courier', 'Курьер', WC()->cart->get_shipping_total(), [], 'custom_courier');
        $order->add_shipping($shipping_rate);

        $shipping_address = [
            'first_name' => $billing_address['first_name'],
            'address_1'  => $address,
        ];
        $order->set_address($shipping_address, 'shipping');
    } else {
        $shipping_rate = new WC_Shipping_Rate('self_pickup', 'Самовывоз', 0, [], 'custom_self_pickup');
        $order->add_shipping($shipping_rate);
    }

    // Добавление товаров из корзины
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = wc_get_product($cart_item['product_id']);
        if ($product) {
            $order->add_product($product, $cart_item['quantity']);
        }
    }

    // Применение методов оплаты и доставки
    $order->set_payment_method($payment_method);
    $order->set_payment_method_title($payment_method);
    $order->update_meta_data('_delivery_method', $delivery_method);
    $order->update_meta_data('_payment_method', $payment_method);
    $order->update_meta_data('_shipping_address', $address);

    $order->calculate_totals();
    $order->save();

    WC()->cart->empty_cart();

    wp_send_json_success([
        'order_id'    => $order->get_id(),
        'order_total' => wc_price($order->get_total()),
        'delivery'    => $delivery_method,
        'payment'     => $payment_method,
    ]);
}


