<?php

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