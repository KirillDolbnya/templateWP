<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="header__container">
            <div class="header__logo">
                <?php the_custom_logo(); ?>
            </div>
            <nav class="header__nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'header-menu',
                    'menu_class'     => 'header__nav-items',
                    'container'      => false,
                    'fallback_cb'    => false
                ]);
                ?>
            </nav>
            <div class="header__btn-wrapper">
                <a class="header__cart-btn primary" href="/cart">Корзина <span class="header__cart-count js-cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span></a>
            </div>
        </div>
    </div>
</header>
