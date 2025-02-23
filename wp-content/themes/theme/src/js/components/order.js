jQuery( document ).ready(function($) {

    function updateSelection(groupSelector) {
        $(groupSelector).each(function () {
            let input = $(this).find("input[type='radio']");
            let circle = $(this).find(".circle");

            if (input.is(':checked')) {
                circle.css("background", "#000"); // активный цвет
            } else {
                circle.css("background", "#F2F2F2"); // стандартный цвет
            }
        });
    }

    $('.cart__order-delivery--item input[type="radio"]').on('change', function () {
        $('.cart__order-delivery--item input[type="radio"]').prop('checked', false);
        $(this).prop('checked', true);
        updateSelection(".cart__order-delivery--item");
    });

    $('.cart__order-payment--item input[type="radio"]').on('change', function () {
        $('.cart__order-payment--item input[type="radio"]').prop('checked', false);
        $(this).prop('checked', true);
        updateSelection(".cart__order-payment--item");
    });

    $('.cart__order-delivery--item input[type="radio"]').first().prop('checked', true);
    $('.cart__order-payment--item input[type="radio"]').first().prop('checked', true);
    updateSelection(".cart__order-delivery--item");
    updateSelection(".cart__order-payment--item");

    $("body").on('submit', '#checkout-form', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let selectedDelivery = $('.cart__order-delivery--item input[type="radio"]:checked').next().find('p').text();
        let selectedPayment = $('.cart__order-payment--item input[type="radio"]:checked').next().find('p').text();

        if (!selectedDelivery || !selectedPayment) {
            alert("Пожалуйста, выберите способ доставки и оплаты!");
            return;
        }

        formData += `&delivery=${encodeURIComponent(selectedDelivery)}&payment=${encodeURIComponent(selectedPayment)}`;

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'process_checkout',
                form_data: formData
            },
            // beforeSend: function() {
            //     $('#order-result').html('<p>Обработка заказа...</p>');
            // },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    alert(`Заказ ${response.data.applied_coupons} успешно ${response.data.discount_total} оформлен! №\' + ${response.data.order_id}`)
                    $('.cart__container').html('<p>Ваша корзина пуста</p>');
                } else {
                    $('#order-result').html('<p style="color: red;">Ошибка: ' + response.data.message + '</p>');
                }
            },
            error: function () {
                $('#order-result').html('<p style="color: red;">Ошибка сервера</p>');
            }
        });
    });
});