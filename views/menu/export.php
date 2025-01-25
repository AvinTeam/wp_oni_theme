<?php
    (defined('ABSPATH')) || exit;
    global $title;

?>

<div id="wpbody-content">
    <div class="wrap oni_menu">
        <h1><?php echo esc_html($title) ?></h1>


        <hr class="wp-header-end">

        <form method="post" action="" novalidate="novalidate" class="ag_form">
            <?php wp_nonce_field('oni_nonce' . get_current_user_id()); ?>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="count_questions">از تاریخ</label></th>
                        <td><input name="datestart" class="regular-text" type="text" data-jdp="" data-jdp-max-date="today" placeholder="از تاریخ" data-jdp-only-date="" require></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="count_questions">تا تاریخ</label></th>
                        <td><input name="dateend" class="regular-text" type="text" data-jdp="" data-jdp-max-date="today" placeholder="تا تاریخ" data-jdp-only-date="" require></td>
                    </tr>
                </tbody>
            </table>

            <p class="submit">
                <button type="submit" name="oni_act" value="oni__export" id="submit" class="button button-primary">خروجی  اکسل</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>










