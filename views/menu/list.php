<?php (defined('ABSPATH')) || exit;
global $title; ?>
<div class="wrap mph_big_style">


    <h1 class="wp-heading-inline"> <?php echo esc_html($title) ?> <a
            href="<?php echo esc_html(admin_url('admin.php?page=add_ayeh')) ?>" class="page-title-action">افزودن
            سوال</a></h1>

    <?php

        //$oniListTable->oni_res($row);
        $oniListTable->prepare_items();

        echo '<form method="post" action="" >';
        $oniListTable->views();
        $oniListTable->search_box('جست و جو سوال', 'oni_title');
        $oniListTable->display();

    ?>

    </form>




</div>
<div class="oni-loader "></div>

<div class="clear"></div>