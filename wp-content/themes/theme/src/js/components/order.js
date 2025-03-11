document.addEventListener('DOMContentLoaded', function (){

    const cartQty = document.querySelectorAll('.js-cart-count');
    const totalPrice = document.querySelectorAll('.js-cart-price');
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
       if (event.target.closest('#checkout-form')) {
           event.preventDefault();

           const form = event.target.closest('#checkout-form');

           // Получаем данные формы
           const formData = new FormData(form);
           formData.append('action', 'process_checkout');

           axios.post(ajaxData.ajaxurl, formData, {
               headers: { 'Content-Type': 'multipart/form-data' }
           })
               .then(response => {
                   const data = response.data;

                   if (!data.success) {
                       throw new Error(data.data.message);
                   }

                   alert(`Заказ #${data.data.order_id} успешно оформлен! Сумма: ${data.data.order_total}`);
                   cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
                   totalPrice.textContent = 0;
                   cartQty.forEach(item => {
                       item.textContent = 0;
                   });
               })
               .catch(error => {
                   alert(error.message);
               });
       }
    });

});