<?php (defined('ABSPATH')) || exit;
global $title; ?>
<div class="wrap mph_big_style oni_menu">


    <h1 class="wp-heading-inline"> <?php echo esc_html($title) ?></h1>

    <?php

        $nasrListTable->atlas_res($row);
        $nasrListTable->prepare_items();

        echo '<form method="post" action="" class="nasr-table" >';
        $nasrListTable->views();

        $nasrListTable->display();

        wp_nonce_field('mph_nonce' . get_current_user_id());

    ?>

    </form>

</div>
<div class="nasr-loader"></div>

<div class="clear"></div>