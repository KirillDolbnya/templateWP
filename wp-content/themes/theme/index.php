<?php
/*
Template Name: Главная
*/

get_header();

$args = [
    'post_type' => 'product',
    'posts_per_page' => -1,
];

$products = new WP_Query($args);

?>
<div class="catalog">
    <div class="container">
        <div class="catalog__container">
            <h2 class="catalog__title">Каталог</h2>
            <?php
            if (!empty($products->posts)) {
                ?>
                <div class="catalog__items">
                    <?php
                    foreach ($products->posts as $product_post) {
                        $wc_product = wc_get_product($product_post->ID);

                        $cart = WC()->cart->get_cart();
                        $product_in_cart = false;
                        $cart_quantity = 0;

                        foreach ($cart as $cart_item) {
                            if ($cart_item['product_id'] == $wc_product->get_id()) {
                                $product_in_cart = true;
                                $cart_quantity = $cart_item['quantity'];
                                break;
                            }
                        }
                        ?>
                        <div class="productCard catalog__item">
                            <div class="productCard__img catalog__item-img">
                                <img src="<?= get_the_post_thumbnail_url($wc_product->get_id()) ?>" alt="">
                            </div>
                            <div class="productCard__content catalog__item-content">
                                <div class="productCard__name catalog__item-name">
                                    <p><?= $wc_product->get_name() ?></p>
                                    <p>Остаток: <?= $wc_product->get_stock_quantity() ?></p>
                                </div>
                                <?php if ($product_in_cart): ?>
                                    <div class="quantity-control" data-product-id="<?= $wc_product->get_id() ?>">
                                        <button class="decrease-qty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                                            </svg>
                                        </button>
                                        <input class="product-qty" value="<?= $cart_quantity; ?>" readonly>
                                        <button class="increase-qty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H12.75V20.25C12.75 20.4489 12.671 20.6397 12.5303 20.7803C12.3897 20.921 12.1989 21 12 21C11.8011 21 11.6103 20.921 11.4697 20.7803C11.329 20.6397 11.25 20.4489 11.25 20.25V12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H11.25V3.75C11.25 3.55109 11.329 3.36032 11.4697 3.21967C11.6103 3.07902 11.8011 3 12 3C12.1989 3 12.3897 3.07902 12.5303 3.21967C12.671 3.36032 12.75 3.55109 12.75 3.75V11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12Z" fill="#3300FF"/>
                                            </svg>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <button class="js-cart productCard__btn catalog__item-btn primary" <?= $wc_product->get_stock_quantity() === 0? 'disabled': '' ?> data-product-id="<?= $wc_product->get_id() ?>">В корзину</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
