jQuery( document ).ready(function($) {

    function updateHiddenFields() {
        const delivery = $('.cart__order-delivery--item input[type="radio"]:checked')
            .siblings('.cart__order-delivery--item_info')
            .find('p').text().trim();

        const payment = $('.cart__order-payment--item input[type="radio"]:checked')
            .siblings('.cart__order-payment--item_info')
            .find('p').text().trim();

        $('#selected-delivery').val(delivery);
        $('#selected-payment').val(payment);

        // Добавляем или убираем атрибут disabled у поля адреса
        if (delivery === 'Самовывоз') {
            $('input[name="address"]').attr('disabled', true).val(''); // Очищаем поле при самовывозе
        } else {
            $('input[name="address"]').removeAttr('disabled');
        }
    }

// Инициализация и обработка событий
    updateHiddenFields();

    function handleRadioChange(selector) {
        $(selector).on('change', 'input[type="radio"]', function () {
            $(selector + ' input[type="radio"]').prop('checked', false);
            $(this).prop('checked', true);
            updateHiddenFields();
        });
    }

    handleRadioChange('.cart__order-delivery--item');
    handleRadioChange('.cart__order-payment--item');

// Отправка формы через AJAX
    $("body").on('submit', '#checkout-form', function (e) {
        e.preventDefault();
        const form = $(this);
        const formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'process_checkout',
                form_data: formData,
            },
            beforeSend: () => $('#order-result').html('<p>Обработка заказа...</p>'),
            success: (response) => {
                if (response.success) {
                    alert(`Заказ №${response.data.order_id} успешно оформлен!`);
                    $('.cart__container').html('<p>Ваша корзина пуста</p>');
                    $('#order-result').empty();
                } else {
                    $('#order-result').html(`<p style="color: red;">Ошибка: ${response.data.message}</p>`);
                }
            },
            error: () => $('#order-result').html('<p style="color: red;">Ошибка сервера. Попробуйте снова.</p>'),
        });
    });
});