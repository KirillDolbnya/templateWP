<?php
/*
Template Name: Мой аккаунт
*/

get_header();
?>

<div class="container">
    <?php echo apply_filters('the_content', '[woocommerce_my_account]'); ?>
</div>

<?php
get_footer();
