<?php
wp_footer();
?>
<footer class="footer">
    <div class="container">
        <div class="footer__container">
            <div class="footer__top">
                <div class="footer__logo">
                    <img src="" alt="">
                </div>
                <div class="footer__nav">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer__nav-items',
                        'container'      => false,
                        'fallback_cb'    => false
                    ]);
                    ?>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="footer__bottom-left">
                    <a href="">Политика конфиденциальности</a>
                    <p>|</p>
                    <a href="">Договор оферты</a>
                </div>
                <a href="">2024</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
