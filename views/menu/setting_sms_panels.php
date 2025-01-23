<?php
    (defined('ABSPATH')) || exit;
    global $title;

?>

<div id="wpbody-content">
    <div class="wrap oni_menu">
        <h1><?php echo esc_html($title) ?></h1>

        <hr class="wp-header-end">

        <?php if ($error = get_transient('error_mat')) {?>
        <div class="notice notice-error settings-error is-dismissible">
            <p><?php echo esc_html($error); ?></p>
        </div>
        <?php set_transient('error_mat', '');}?>

        <?php if ($success = get_transient('success_mat')) {?>
        <div class="notice notice-success settings-error is-dismissible">
            <p><?php echo esc_html($success); ?></p>
        </div>
        <?php set_transient('success_mat', '');}?>

        <form method="post" action="" novalidate="novalidate" class="ag_form">
            <?php wp_nonce_field('oni_nonce' . get_current_user_id()); ?>
            <h2 class="title">نوتیویکیتور ( <a href="https://notificator.ir">لینک</a> )</h2>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="notificator_token">توکن نوتیو</label></th>
                        <td>
                            <input name="notificator_token" type="text" id="notificator_token"
                                value="<?php echo $oni_option[ 'notificator_token' ]?>" class="regular-text">
                        </td>
                    </tr>
                </tbody>
            </table>

            <h2 class="title">تی اس ام اس ( <a href="https://www.tsms.ir/">لینک</a> )</h2>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="tsms_username">نام کاربری</label></th>
                        <td>
                            <input name="tsms[username]" type="text" id="tsms_username"
                                value="<?php echo $oni_option[ 'tsms' ][ 'username' ]?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="tsms_password">رمز عبور پنل پیامک</label></th>
                        <td>
                            <input name="tsms[password]" type="text" id="tsms_password"
                                value="<?php echo $oni_option[ 'tsms' ][ 'password' ]?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="tsms_number">شماره ارسال پیامک</label></th>
                        <td>
                            <input name="tsms[number]" type="text" id="tsms_number"
                                value="<?php echo $oni_option[ 'tsms' ][ 'number' ]?>" class="regular-text">
                        </td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">قاصدک ( <a href="https://panel.ghasedaksms.com/">لینک</a> )</h2>
            <table class="form-table" role="presentation">
                <tbody>

                    <tr>
                        <th scope="row"><label for="ghasedaksms_ApiKey">ApiKey</label></th>
                        <td>
                            <input name="ghasedaksms[ApiKey]" type="text" id="ghasedaksms_ApiKey"
                                value="<?php echo $oni_option[ 'ghasedaksms' ][ 'ApiKey' ]?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ghasedaksms_number">شماره ارسال پیامک</label></th>
                        <td>
                            <input name="ghasedaksms[number]" type="text" id="ghasedaksms_number"
                                value="<?php echo $oni_option[ 'ghasedaksms' ][ 'number' ]?>" class="regular-text">
                        </td>
                    </tr>
                </tbody>
            </table>







            <p class="submit">
                <button type="submit" name="oni_act" value="oni__submit" id="submit"
                    class="button button-primary">ذخیرهٔ
                    تغییرات</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>