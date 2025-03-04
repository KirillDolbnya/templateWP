document.addEventListener('DOMContentLoaded', function () {

    const cartQty = document.querySelectorAll('.js-cart-count');
    const totalPrice = document.querySelectorAll('.js-cart-price');
    const cartContent = document.querySelector('.cart__content');

    const addToCartBtn = (id) => {
        return `
        <button class="js-cart productCard__btn catalog__item-btn primary" data-product-id="${id}">
            В корзину
        </button>`;
    }

    const controlBtnHtml = (id, qty) => {
        return `
        <div class="quantity-control" data-product-id="${id}">
            <button class="decrease-qty">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                </svg>
            </button>
            <input class="product-qty" value="${qty}" readOnly>
            <button class="increase-qty">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H12.75V20.25C12.75 20.4489 12.671 20.6397 12.5303 20.7803C12.3897 20.921 12.1989 21 12 21C11.8011 21 11.6103 20.921 11.4697 20.7803C11.329 20.6397 11.25 20.4489 11.25 20.25V12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H11.25V3.75C11.25 3.55109 11.329 3.36032 11.4697 3.21967C11.6103 3.07902 11.8011 3 12 3C12.1989 3 12.3897 3.07902 12.5303 3.21967C12.671 3.36032 12.75 3.55109 12.75 3.75V11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                </svg>
            </button>
        </div>`
    }

    document.body.addEventListener('click', function (event) {
        if (event.target.classList.contains('js-cart')) {
            const btn = event.target.closest('.js-cart');
            const productID = event.target.dataset.productId;

            // fetch(ajaxData.ajaxurl, {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/x-www-form-urlencoded'
            //     },
            //     body: new URLSearchParams({
            //         action: 'add_to_cart',
            //         product_id: productID,
            //         quantity: 1
            //     })
            // })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (!data.success) {
            //             throw new Error(data.data.message);
            //         }
            //
            //         cartQty.forEach(item => {
            //             item.textContent = data.data.cartTotalQty;
            //         });
            //
            //         btn.outerHTML = controlBtnHtml(data.data.productId, data.data.productQty);
            //         alert(data.data.message);
            //     })
            //     .catch(error => {
            //         alert(error.message);
            //     });


            // вариант для axios
            axios.post(ajaxData.ajaxurl, new URLSearchParams({
                action: 'add_to_cart',
                product_id: productID,
                quantity: 1
            }))
                .then(response => {
                    const data = response.data;

                    if (!data.success) {
                        throw new Error(data.data?.message || "Ошибка при добавлении товара");
                    }

                    cartQty.forEach(item => {
                        item.textContent = data.data.cartTotalQty;
                    });

                    btn.outerHTML = controlBtnHtml(data.data.productId, data.data.productQty);

                    alert(data.data.message)
                })
                .catch(error => {
                    alert(error.message);
                });
        }
    });

    document.body.addEventListener('click', function (event) {
        if (event.target.closest('.increase-qty, .decrease-qty')) {
            const quantityControl = event.target.closest('.quantity-control');
            const productID = quantityControl.dataset.productId;
            const qtyElement = quantityControl.querySelector('.product-qty');
            let newQty = parseInt(qtyElement.value, 10);
            const cartItem = quantityControl.closest('.cart__item');

            if (event.target.closest('.increase-qty')) {
                newQty += 1;
            } else if (event.target.closest('.decrease-qty')) {
                newQty = Math.max(0, newQty - 1);
            }

            // fetch(ajaxData.ajaxurl, {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            //     body: new URLSearchParams({
            //         action: 'update_cart',
            //         product_id: productID,
            //         quantity: newQty
            //     })
            // })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (!data.success) {
            //             throw new Error(data.data.message);
            //         }
            //
            //         if (data.data.productQty > 0) {
            //             qtyElement.value = data.data.productQty;
            //         } else {
            //                 if (cartItem){
            //                     cartItem.remove()
            //                 }else{
            //                     quantityControl.outerHTML = addToCartBtn(data.data.productId);
            //                 }
            //         }
            //
            //         cartQty.textContent = data.data.cartTotalQty;
            //         alert(data.data.message);
            //     })
            //     .catch(error => {
            //         alert(error.message);
            //     });

            //вариант для axios
            axios.post(ajaxData.ajaxurl, new URLSearchParams({
                action: 'update_cart',
                product_id: productID,
                quantity: newQty
            }), {
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .then(response => {
                    const data = response.data;
                    if (!data.success) {
                        throw new Error(data.data.message);
                    }

                    if (data.data.productQty > 0) {
                        qtyElement.value = data.data.productQty;
                    } else {
                        if (cartItem){
                            cartItem.remove()

                            if (data.data.cartTotalQty === 0){
                                cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
                            }
                        }else{
                            quantityControl.outerHTML = addToCartBtn(data.data.productId);
                        }
                    }

                    cartQty.forEach(item => {
                        item.textContent = data.data.cartTotalQty;
                    });

                    totalPrice.forEach(item => {
                        item.textContent = data.data.cartTotalPrice;
                    });

                    alert(data.data.message);
                })
                .catch(error => {
                    alert(error.message);
                });
        }
    });

    document.body.addEventListener('click', function (event){
       if (event.target.closest('.js-cart-remove')) {
           const btn = event.target.closest('.js-cart-remove');
           const cartItem = btn.closest('.cart__item');
           const productID = btn.dataset.productId;

           // fetch(ajaxData.ajaxurl, {
           //     method: 'POST',
           //     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
           //     body: new URLSearchParams({
           //         action: 'remove_from_cart',
           //         product_id: productID,
           //     })
           // })
           //     .then(response => response.json())
           //     .then(data => {
           //         if (!data.success) {
           //             throw new Error(data.data.message);
           //         }
           //
           //         cartItem.remove();
           //
           //         if (data.data.cartTotalQty === 0) {
           //             cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
           //         }
           //
           //         cartQty.forEach(item => {
           //             item.textContent = data.data.cartTotalQty;
           //         });
           //
           //         totalPrice.forEach(item => {
           //             item.textContent = data.data.cartTotalPrice;
           //         });
           //
           //         alert(data.data.message);
           //     })
           //     .catch(error => {
           //         alert(error.message);
           //     });

           // вариант для axios
           axios.post(ajaxData.ajaxurl, new URLSearchParams({
               action: 'remove_from_cart',
               product_id: productID,
           }), {
               headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
           })
               .then(response => {
                   const data = response.data;
                   if (!data.success) {
                       throw new Error(data.data.message);
                   }

                   cartItem.remove();

                   if (data.data.cartTotalQty === 0) {
                       cartContent.innerHTML = `<p>Ваша корзина пуста</p>`;
                   }

                   cartQty.forEach(item => {
                      item.textContent = data.data.cartTotalQty;
                   });

                   totalPrice.forEach(item => {
                       item.textContent = data.data.cartTotalPrice;
                   });

                   alert(data.data.message);
               })
               .catch(error => {
                  alert(error.message)
               });

       }
    });

});