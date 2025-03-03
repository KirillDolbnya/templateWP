document.addEventListener('DOMContentLoaded', function () {

    const cartQty = document.querySelector('.js-cart-count');

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

                    cartQty.textContent = data.data.cartQuantity;

                    btn.outerHTML = controlBtnHtml(data.data.productId, data.data.productQty);

                    alert(data.data.message)
                })
                .catch(error => {
                    alert(error.message);
                });
        }
    });
});
// jQuery( document ).ready(function($) {
//     $('body').on('click', '.js-cart', function () {
//         const productID = $(this).data('product-id');
//         const button = $(this);
//
//         $.post(ajaxData.ajaxurl, {
//             action: 'add_to_cart',
//             product_id: productID,
//             quantity: 1
//         }).done(function (response) {
//             if (response.success) {
//                 updateProductButton(button, response.data.productId, 1);
//                 updateCartInfo(response.data.cartInfo);
//                 showAlert(response.data.message);
//             } else {
//                 showAlert(response.data.message);
//             }
//         }).fail(function () {
//             showAlert('Ошибка добавления товара. Попробуйте позже.');
//         });
//     });
//
//     $('body').on('click', '.quantity-control .increase-qty, .quantity-control .decrease-qty', function () {
//         const productID = $(this).closest('.quantity-control').data('product-id');
//         const qtyElement = $(this).siblings('.product-qty');
//         let newQty = parseInt(qtyElement.val(), 10);
//
//         newQty = $(this).hasClass('increase-qty') ? newQty + 1 : newQty - 1;
//
//         $.post(ajaxData.ajaxurl, {
//             action: 'update_product_quantity',
//             product_id: productID,
//             quantity: newQty
//         }).done(function (response) {
//             if (response.success) {
//                 if (newQty > 0) {
//                     qtyElement.val(newQty);
//                 } else {
//                     replaceWithAddButton(productID);
//                 }
//                 updateCartInfo(response.data.cartInfo);
//                 showAlert(response.data.message);
//             } else {
//                 showAlert(response.data.message);
//             }
//         }).fail(function () {
//             showAlert('Ошибка обновления количества. Попробуйте позже.');
//         });
//     });
//
//     $('body').on('click', '.js-cart-remove', function () {
//         const productID = $(this).data('product-id');
//
//         $.post(ajaxData.ajaxurl, {
//             action: 'remove_from_cart',
//             product_id: productID
//         }).done(function (response) {
//             if (response.success) {
//                 $(`.js-cart-remove[data-product-id="${productID}"]`).closest('.cart__item').remove();
//                 updateCartInfo(response.data.cartInfo);
//                 showAlert(response.data.message);
//
//                 if (response.data.cartInfo.totalQuantity === 0) {
//                     $('.cart__content').replaceWith('<p class="cart__empty">Ваша корзина пуста</p>');
//                 }
//             } else {
//                 showAlert(response.data.message);
//             }
//         }).fail(function () {
//             showAlert('Ошибка при удалении товара. Попробуйте позже.');
//         });
//     });
//
//     function updateProductButton(button, productId, quantity) {
//         button.replaceWith(`
//         <div class="quantity-control" data-product-id="${productId}">
//             <button class="decrease-qty">
//                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
//                     <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
//                 </svg>
//             </button>
//             <input class="product-qty" value="${quantity}" readonly>
//             <button class="increase-qty">
//                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
//                     <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H12.75V20.25C12.75 20.4489 12.671 20.6397 12.5303 20.7803C12.3897 20.921 12.1989 21 12 21C11.8011 21 11.6103 20.921 11.4697 20.7803C11.329 20.6397 11.25 20.4489 11.25 20.25V12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H11.25V3.75C11.25 3.55109 11.329 3.36032 11.4697 3.21967C11.6103 3.07902 11.8011 3 12 3C12.1989 3 12.3897 3.07902 12.5303 3.21967C12.671 3.36032 12.75 3.55109 12.75 3.75V11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
//                 </svg>
//             </button>
//         </div>
//     `);
//     }
//
//     function replaceWithAddButton(productId) {
//         $(`.quantity-control[data-product-id="${productId}"]`).replaceWith(`
//         <button class="js-cart productCard__btn catalog__item-btn primary" data-product-id="${productId}">
//             В корзину
//         </button>
//     `);
//     }
//
//     function updateCartInfo(cartInfo) {
//         updateCartCount(cartInfo.totalQuantity);
//         updateCartPrice(cartInfo.totalPrice);
//     }
//
//     function updateCartCount(count) {
//         $('.js-cart-count').text(count);
//     }
//
//     function updateCartPrice(price) {
//         $('.js-cart-price').text(price.toFixed(2));
//     }
//
//     function showAlert(message) {
//         // alert(message);
//         console.log(message);
//     }
// });