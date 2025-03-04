document.addEventListener('DOMContentLoaded', function (){

    const cartContent = document.querySelector('.cart__content');

    const deliveryItems = document.querySelectorAll('.cart__order-delivery--item');
    const paymentItems = document.querySelectorAll('.cart__order-payment--item');

    deliveryItems.forEach(item => {
        item.addEventListener('click', function (){

            document.querySelectorAll('.cart__order-delivery--item input[type=radio]').forEach(input => {
                input.checked = false;
            });

            const input = this.querySelector('input[type=radio]');
            input.checked = true;
       });
    });

    paymentItems.forEach(item => {
        item.addEventListener('click', function (){

            document.querySelectorAll('.cart__order-payment--item input[type=radio]').forEach(input => {
                input.checked = false;
            });

            const input = this.querySelector('input[type=radio]');
            input.checked = true;
        });
    });

    document.body.addEventListener('submit', function (event){
        console.log(event);
        event.preventDefault();

       if (event.target.closest('#checkout-form')) {
           // const form = event.target.closest('#checkout-form');
           // const name = form.querySelector('input[name=billing_first_name]').value;
           // const phone = form.querySelector('input[name=billing_phone]').value;
           // const email = form.querySelector('input[name=billing_email]').value;
           // const address = form.querySelector('input[name=address]')?.value || '';
           // const deliveryInputs = form.querySelectorAll('.cart__order-delivery--item_input');
           // const paymentInputs = form.querySelectorAll('.cart__order-payment--item_input');
           // let delivery;
           // let payment;
           //
           // deliveryInputs.forEach(input => {
           //    if (input.checked){
           //        delivery = input.value;
           //    }
           // });
           //
           // paymentInputs.forEach(input => {
           //     if (input.checked){
           //         payment = input.value;
           //     }
           // });
           //
           // const formData = new URLSearchParams({
           //     action: 'process_checkout',
           //     name: name,
           //     phone: phone,
           //     email: email,
           //     address: address,
           //     delivery: delivery,
           //     payment: payment
           // });
           //
           // axios.post(ajaxData.ajaxurl, formData)
           //     .then(response => {
           //         const data = response.data;
           //
           //         if (!data.success) {
           //             throw new Error(data.data?.message || "Ошибка при оформлении заказа");
           //         }
           //
           //         cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
           //
           //         alert(data.data.message);
           //     })
           //     .catch(error => {
           //         alert(error.message);
           //     });

           const form = event.target.closest('#checkout-form');

           // Получаем данные формы
           const formData = new URLSearchParams(new FormData(form));
           formData.append('action', 'process_checkout'); // Добавляем action

           axios.post(ajaxData.ajaxurl, formData)
               .then(response => {
                   const data = response.data;

                   if (!data.success) {
                       throw new Error(data.data?.message || "Ошибка при оформлении заказа");
                   }

                   alert(`Заказ #${data.data.order_id} успешно оформлен! Сумма: ${data.data.order_total}`);
                   cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
               })
               .catch(error => {
                   alert(error.message);
               });
       }
    });

});

// Отправка формы через AJAX
//     $("body").on('submit', '#checkout-form', function (e) {
//         e.preventDefault();
//         const form = $(this);
//         const formData = form.serialize();
//
//         $.ajax({
//             type: 'POST',
//             url: ajaxData.ajaxurl,
//             data: {
//                 action: 'process_checkout',
//                 form_data: formData,
//             },
//             beforeSend: () => $('#order-result').html('<p>Обработка заказа...</p>'),
//             success: (response) => {
//                 if (response.success) {
//                     alert(`Заказ №${response.data.order_id} успешно оформлен!`);
//                     $('.cart__container').html('<p>Ваша корзина пуста</p>');
//                     $('#order-result').empty();
//                 } else {
//                     $('#order-result').html(`<p style="color: red;">Ошибка: ${response.data.message}</p>`);
//                 }
//             },
//             error: () => $('#order-result').html('<p style="color: red;">Ошибка сервера. Попробуйте снова.</p>'),
//         });
//     });