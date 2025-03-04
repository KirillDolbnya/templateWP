<?php
/*
Template Name: Корзина
*/

get_header();

$cart = WC()->cart->get_cart();

//WC()->cart->empty_cart();
?>

<div class="cart">
    <div class="container">
        <div class="cart__container">
            <?php
            if (!empty($cart)){
            ?>
           <h1>Корзина</h1>
            <div class="cart__content">
                <div class="cart__items">
                    <?php
                    foreach ($cart as $cart_item_key => $cart_item):
                        $product = wc_get_product($cart_item['product_id']);
                        $product_price = $product->get_price();
                        $product_regular_price = $product->get_regular_price();
                        $product_sale_price = $product->get_sale_price();
                        $product_name = $product->get_name();
                        $product_image = get_the_post_thumbnail_url($cart_item['product_id'], 'thumbnail');
                        $quantity = $cart_item['quantity'];
                        $total_price = $product_price * $quantity;
                        ?>
                    <div class="cart__item">
                        <div class="cart__item-img">
                            <img src="<?= $product_image ?>" alt="">
                        </div>
                        <div class="cart__item-info">
                            <div class="cart__item-top">
                                <p class="cart__item-name"><?= $product_name ?></p>
                                <div class="cart__item-prices">
                                    <p><?= $product_sale_price ?> ₽</p>
                                    <p><?= $product_regular_price ?> ₽</p>
                                </div>
                                <div class="cart__item-control productCard__quantity-control quantity-control" data-product-id="<?= $product->get_id() ?>">
                                    <button class="productCard__decrease-qty decrease-qty">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                                        </svg>
                                    </button>
                                    <input class="product-qty" type="text" value="<?= $quantity ?>" readonly>
                                    <button class="productCard__increase-qty increase-qty">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H12.75V20.25C12.75 20.4489 12.671 20.6397 12.5303 20.7803C12.3897 20.921 12.1989 21 12 21C11.8011 21 11.6103 20.921 11.4697 20.7803C11.329 20.6397 11.25 20.4489 11.25 20.25V12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H11.25V3.75C11.25 3.55109 11.329 3.36032 11.4697 3.21967C11.6103 3.07902 11.8011 3 12 3C12.1989 3 12.3897 3.07902 12.5303 3.21967C12.671 3.36032 12.75 3.55109 12.75 3.75V11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button class="cart__item-remove js-cart-remove" data-product-id="<?= $product->get_id() ?>" >
                                Удалить
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M13.5 3H2.5C2.36739 3 2.24021 3.05268 2.14645 3.14645C2.05268 3.24021 2 3.36739 2 3.5C2 3.63261 2.05268 3.75979 2.14645 3.85355C2.24021 3.94732 2.36739 4 2.5 4H3V13C3 13.2652 3.10536 13.5196 3.29289 13.7071C3.48043 13.8946 3.73478 14 4 14H12C12.2652 14 12.5196 13.8946 12.7071 13.7071C12.8946 13.5196 13 13.2652 13 13V4H13.5C13.6326 4 13.7598 3.94732 13.8536 3.85355C13.9473 3.75979 14 3.63261 14 3.5C14 3.36739 13.9473 3.24021 13.8536 3.14645C13.7598 3.05268 13.6326 3 13.5 3ZM12 13H4V4H12V13ZM5 1.5C5 1.36739 5.05268 1.24021 5.14645 1.14645C5.24021 1.05268 5.36739 1 5.5 1H10.5C10.6326 1 10.7598 1.05268 10.8536 1.14645C10.9473 1.24021 11 1.36739 11 1.5C11 1.63261 10.9473 1.75979 10.8536 1.85355C10.7598 1.94732 10.6326 2 10.5 2H5.5C5.36739 2 5.24021 1.94732 5.14645 1.85355C5.05268 1.75979 5 1.63261 5 1.5Z" fill="#0D0D0D"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <form id="checkout-form" class="cart__order">
                    <div class="cart__order-info">
                        <p>Ваша корзина</p>
                        <div class="cart__order-info--prices">
                            <div class="cart__order-info--price">
                                <p>Товары (<span class="js-cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span>)</p>
                                <p class="total-price js-cart-price"><?=  WC()->cart->get_cart_contents_total() ?> ₽</p>
                            </div>
                            <div class="cart__order-info--price">
                                <p>Скидка</p>
                                <p class="price-sale">-0 ₽</p>
                            </div>
                        </div>
                        <div class="cart__order-info--total-price">
                            <p>Итого</p>
                            <p class="js-cart-price">0 ₽</p>
                        </div>
                    </div>
                    <p>Заполните форму</p>
                    <div class="cart__order-inputs">
                        <p>Ваши данные</p>
                        <div class="cart__order-input">
                            <input name="billing_first_name" type="text" placeholder="Ваше имя">
                            <input name="billing_phone" type="text" placeholder="Телефон">
                            <input name="billing_email" type="text" placeholder="Почта">
                        </div>
                    </div>
                    <div class="cart__order-delivery">
                        <input type="hidden" name="delivery" id="selected-delivery">
                        <p>Информация о доставке</p>
                        <div class="cart__order-delivery--items">
                            <label class="cart__order-delivery--item">
                                <input type="radio" class="cart__order-delivery--item_input" value="Курьер">
                                <div class="cart__order-delivery--item_info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M23.1956 10.9688L21.8831 7.6875C21.7718 7.40988 21.5796 7.17209 21.3316 7.00494C21.0835 6.83778 20.791 6.74897 20.4919 6.75H17.25V6C17.25 5.80109 17.171 5.61032 17.0303 5.46967C16.8897 5.32902 16.6989 5.25 16.5 5.25H2.25C1.85218 5.25 1.47064 5.40804 1.18934 5.68934C0.908035 5.97064 0.75 6.35218 0.75 6.75V17.25C0.75 17.6478 0.908035 18.0294 1.18934 18.3107C1.47064 18.592 1.85218 18.75 2.25 18.75H3.84375C4.00898 19.3953 4.38428 19.9673 4.91048 20.3757C5.43669 20.7842 6.08387 21.0059 6.75 21.0059C7.41613 21.0059 8.06331 20.7842 8.58952 20.3757C9.11573 19.9673 9.49103 19.3953 9.65625 18.75H14.3438C14.509 19.3953 14.8843 19.9673 15.4105 20.3757C15.9367 20.7842 16.5839 21.0059 17.25 21.0059C17.9161 21.0059 18.5633 20.7842 19.0895 20.3757C19.6157 19.9673 19.991 19.3953 20.1563 18.75H21.75C22.1478 18.75 22.5294 18.592 22.8107 18.3107C23.092 18.0294 23.25 17.6478 23.25 17.25V11.25C23.2503 11.1536 23.2318 11.0581 23.1956 10.9688ZM17.25 8.25H20.4919L21.3919 10.5H17.25V8.25ZM2.25 6.75H15.75V12.75H2.25V6.75ZM6.75 19.5C6.45333 19.5 6.16332 19.412 5.91665 19.2472C5.66997 19.0824 5.47771 18.8481 5.36418 18.574C5.25065 18.2999 5.22094 17.9983 5.27882 17.7074C5.3367 17.4164 5.47956 17.1491 5.68934 16.9393C5.89912 16.7296 6.16639 16.5867 6.45737 16.5288C6.74834 16.4709 7.04994 16.5006 7.32403 16.6142C7.59812 16.7277 7.83238 16.92 7.99721 17.1666C8.16203 17.4133 8.25 17.7033 8.25 18C8.25 18.3978 8.09197 18.7794 7.81066 19.0607C7.52936 19.342 7.14783 19.5 6.75 19.5ZM14.3438 17.25H9.65625C9.49103 16.6047 9.11573 16.0327 8.58952 15.6243C8.06331 15.2158 7.41613 14.9941 6.75 14.9941C6.08387 14.9941 5.43669 15.2158 4.91048 15.6243C4.38428 16.0327 4.00898 16.6047 3.84375 17.25H2.25V14.25H15.75V15.4041C15.4051 15.6034 15.1032 15.8692 14.8619 16.1861C14.6205 16.5029 14.4444 16.8646 14.3438 17.25ZM17.25 19.5C16.9533 19.5 16.6633 19.412 16.4166 19.2472C16.17 19.0824 15.9777 18.8481 15.8642 18.574C15.7507 18.2999 15.7209 17.9983 15.7788 17.7074C15.8367 17.4164 15.9796 17.1491 16.1893 16.9393C16.3991 16.7296 16.6664 16.5867 16.9574 16.5288C17.2483 16.4709 17.5499 16.5006 17.824 16.6142C18.0981 16.7277 18.3324 16.92 18.4972 17.1666C18.662 17.4133 18.75 17.7033 18.75 18C18.75 18.3978 18.592 18.7794 18.3107 19.0607C18.0294 19.342 17.6478 19.5 17.25 19.5ZM21.75 17.25H20.1563C19.989 16.6063 19.613 16.0362 19.0871 15.629C18.5612 15.2218 17.9151 15.0006 17.25 15V12H21.75V17.25Z" fill="#0D0D0D"/>
                                    </svg>
                                    <p>Курьер</p>
                                </div>
                                <div class="circle">
                                </div>
                            </label>
                            <label class="cart__order-delivery--item">
                                <input type="radio" class="cart__order-delivery--item_input" value="Самовывоз">
                                <div class="cart__order-delivery--item_info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M21.75 9C21.7504 8.93027 21.7409 8.86083 21.7219 8.79375L20.3766 4.0875C20.2861 3.77523 20.0971 3.50059 19.8378 3.30459C19.5784 3.10858 19.2626 3.00174 18.9375 3H5.0625C4.73741 3.00174 4.4216 3.10858 4.16223 3.30459C3.90287 3.50059 3.71386 3.77523 3.62344 4.0875L2.27906 8.79375C2.2597 8.86079 2.24991 8.93022 2.25 9V10.5C2.25 11.0822 2.38554 11.6563 2.6459 12.1771C2.90625 12.6978 3.28427 13.1507 3.75 13.5V19.5C3.75 19.8978 3.90804 20.2794 4.18934 20.5607C4.47064 20.842 4.85218 21 5.25 21H18.75C19.1478 21 19.5294 20.842 19.8107 20.5607C20.092 20.2794 20.25 19.8978 20.25 19.5V13.5C20.7157 13.1507 21.0937 12.6978 21.3541 12.1771C21.6145 11.6563 21.75 11.0822 21.75 10.5V9ZM5.0625 4.5H18.9375L20.0081 8.25H3.99469L5.0625 4.5ZM9.75 9.75H14.25V10.5C14.25 11.0967 14.0129 11.669 13.591 12.091C13.169 12.5129 12.5967 12.75 12 12.75C11.4033 12.75 10.831 12.5129 10.409 12.091C9.98705 11.669 9.75 11.0967 9.75 10.5V9.75ZM8.25 9.75V10.5C8.25 11.0967 8.01295 11.669 7.59099 12.091C7.16903 12.5129 6.59674 12.75 6 12.75C5.40326 12.75 4.83097 12.5129 4.40901 12.091C3.98705 11.669 3.75 11.0967 3.75 10.5V9.75H8.25ZM18.75 19.5H5.25V14.175C5.4969 14.2248 5.74813 14.2499 6 14.25C6.58217 14.25 7.15634 14.1145 7.67705 13.8541C8.19776 13.5937 8.6507 13.2157 9 12.75C9.3493 13.2157 9.80224 13.5937 10.3229 13.8541C10.8437 14.1145 11.4178 14.25 12 14.25C12.5822 14.25 13.1563 14.1145 13.6771 13.8541C14.1978 13.5937 14.6507 13.2157 15 12.75C15.3493 13.2157 15.8022 13.5937 16.3229 13.8541C16.8437 14.1145 17.4178 14.25 18 14.25C18.2519 14.2499 18.5031 14.2248 18.75 14.175V19.5ZM18 12.75C17.4033 12.75 16.831 12.5129 16.409 12.091C15.9871 11.669 15.75 11.0967 15.75 10.5V9.75H20.25V10.5C20.25 11.0967 20.0129 11.669 19.591 12.091C19.169 12.5129 18.5967 12.75 18 12.75Z" fill="#0D0D0D"/>
                                    </svg>
                                    <p>Самовывоз</p>
                                </div>
                                <div class="circle">
                                </div>
                            </label>
                        </div>
                    </div>
                    <input type="text" name="address" placeholder="Адрес">
                    <div class="cart__order-payment">
                        <input type="hidden" name="payment" id="selected-payment">
                        <p>Способ оплаты</p>
                        <div class="cart__order-payment--items">
                            <label class="cart__order-payment--item">
                                <input type="radio" class="cart__order-payment--item_input" value="Картой при получении">
                                <div class="cart__order-payment--item_info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M21 4.5H3C2.60218 4.5 2.22064 4.65804 1.93934 4.93934C1.65804 5.22064 1.5 5.60218 1.5 6V18C1.5 18.3978 1.65804 18.7794 1.93934 19.0607C2.22064 19.342 2.60218 19.5 3 19.5H21C21.3978 19.5 21.7794 19.342 22.0607 19.0607C22.342 18.7794 22.5 18.3978 22.5 18V6C22.5 5.60218 22.342 5.22064 22.0607 4.93934C21.7794 4.65804 21.3978 4.5 21 4.5ZM21 6V8.25H3V6H21ZM21 18H3V9.75H21V18ZM19.5 15.75C19.5 15.9489 19.421 16.1397 19.2803 16.2803C19.1397 16.421 18.9489 16.5 18.75 16.5H15.75C15.5511 16.5 15.3603 16.421 15.2197 16.2803C15.079 16.1397 15 15.9489 15 15.75C15 15.5511 15.079 15.3603 15.2197 15.2197C15.3603 15.079 15.5511 15 15.75 15H18.75C18.9489 15 19.1397 15.079 19.2803 15.2197C19.421 15.3603 19.5 15.5511 19.5 15.75ZM13.5 15.75C13.5 15.9489 13.421 16.1397 13.2803 16.2803C13.1397 16.421 12.9489 16.5 12.75 16.5H11.25C11.0511 16.5 10.8603 16.421 10.7197 16.2803C10.579 16.1397 10.5 15.9489 10.5 15.75C10.5 15.5511 10.579 15.3603 10.7197 15.2197C10.8603 15.079 11.0511 15 11.25 15H12.75C12.9489 15 13.1397 15.079 13.2803 15.2197C13.421 15.3603 13.5 15.5511 13.5 15.75Z" fill="#0D0D0D"/>
                                    </svg>
                                    <p>Картой при получении</p>
                                </div>
                                <div class="circle">
                                </div>
                            </label>
                            <label class="cart__order-payment--item">
                                <input type="radio" class="cart__order-payment--item_input" value="Наличными при получении">
                                <div class="cart__order-payment--item_info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 8.25C11.2583 8.25 10.5333 8.46993 9.91661 8.88199C9.29993 9.29404 8.81928 9.87971 8.53545 10.5649C8.25162 11.2502 8.17736 12.0042 8.32205 12.7316C8.46675 13.459 8.8239 14.1272 9.34835 14.6517C9.8728 15.1761 10.541 15.5333 11.2684 15.6779C11.9958 15.8226 12.7498 15.7484 13.4351 15.4645C14.1203 15.1807 14.706 14.7001 15.118 14.0834C15.5301 13.4667 15.75 12.7417 15.75 12C15.75 11.0054 15.3549 10.0516 14.6517 9.34835C13.9484 8.64509 12.9946 8.25 12 8.25ZM12 14.25C11.555 14.25 11.12 14.118 10.75 13.8708C10.38 13.6236 10.0916 13.2722 9.92127 12.861C9.75097 12.4499 9.70642 11.9975 9.79323 11.561C9.88005 11.1246 10.0943 10.7237 10.409 10.409C10.7237 10.0943 11.1246 9.88005 11.561 9.79323C11.9975 9.70642 12.4499 9.75097 12.861 9.92127C13.2722 10.0916 13.6236 10.38 13.8708 10.75C14.118 11.12 14.25 11.555 14.25 12C14.25 12.5967 14.0129 13.169 13.591 13.591C13.169 14.0129 12.5967 14.25 12 14.25ZM22.5 5.25H1.5C1.30109 5.25 1.11032 5.32902 0.96967 5.46967C0.829018 5.61032 0.75 5.80109 0.75 6V18C0.75 18.1989 0.829018 18.3897 0.96967 18.5303C1.11032 18.671 1.30109 18.75 1.5 18.75H22.5C22.6989 18.75 22.8897 18.671 23.0303 18.5303C23.171 18.3897 23.25 18.1989 23.25 18V6C23.25 5.80109 23.171 5.61032 23.0303 5.46967C22.8897 5.32902 22.6989 5.25 22.5 5.25ZM18.1547 17.25H5.84531C5.5935 16.3984 5.13263 15.6233 4.50467 14.9953C3.87671 14.3674 3.10162 13.9065 2.25 13.6547V10.3453C3.10162 10.0935 3.87671 9.63263 4.50467 9.00467C5.13263 8.37671 5.5935 7.60162 5.84531 6.75H18.1547C18.4065 7.60162 18.8674 8.37671 19.4953 9.00467C20.1233 9.63263 20.8984 10.0935 21.75 10.3453V13.6547C20.8984 13.9065 20.1233 14.3674 19.4953 14.9953C18.8674 15.6233 18.4065 16.3984 18.1547 17.25ZM21.75 8.75344C20.8504 8.36662 20.1334 7.64959 19.7466 6.75H21.75V8.75344ZM4.25344 6.75C3.86662 7.64959 3.14959 8.36662 2.25 8.75344V6.75H4.25344ZM2.25 15.2466C3.14959 15.6334 3.86662 16.3504 4.25344 17.25H2.25V15.2466ZM19.7466 17.25C20.1334 16.3504 20.8504 15.6334 21.75 15.2466V17.25H19.7466Z" fill="#0D0D0D"/>
                                    </svg>
                                    <p>Наличными при получении</p>
                                </div>
                                <div class="circle">
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="cart__order-checkbox">
                        <input type="checkbox">
                        <div>

                        </div>
                        <p>Соглашаюсь с Политикой конфиденциальности и условиями обработки персональных данных</p>
                    </div>
                    <button type="submit" class="cart__order-submit">Оформить заказ</button>
                </form>
            </div>
            <?php
            }else{
            ?>
            <p>Ваша Корзина Пуста</p>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
