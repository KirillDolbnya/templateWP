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
                <a class="header__btn-profile" <?= is_user_logged_in() ? 'href="/my-account"' : 'data-path="login"'; ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11.5 14c4.14 0 7.5 1.57 7.5 3.5V20H4v-2.5c0-1.93 3.36-3.5 7.5-3.5m6.5 3.5c0-1.38-2.91-2.5-6.5-2.5S5 16.12 5 17.5V19h13zM11.5 5A3.5 3.5 0 0 1 15 8.5a3.5 3.5 0 0 1-3.5 3.5A3.5 3.5 0 0 1 8 8.5A3.5 3.5 0 0 1 11.5 5m0 1A2.5 2.5 0 0 0 9 8.5a2.5 2.5 0 0 0 2.5 2.5A2.5 2.5 0 0 0 14 8.5A2.5 2.5 0 0 0 11.5 6"/>
                    </svg>
                </a>
                <a class="header__btn-cart primary" href="/cart">Корзина <span class="header__cart-count js-cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span></a>
            </div>
        </div>
    </div>
</header>
