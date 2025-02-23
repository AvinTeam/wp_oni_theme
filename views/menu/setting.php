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
                        <th scope="row"><label for="token_password">تعداد سوال های مسابقه</label></th>
                        <td><input name="token_password" type="text" id="token_password"
                                value="<?php echo $oni_option[ 'token_password' ] ?>"
                                class="regular-text ">
                        </td>
                    </tr>
                </tbody>
            </table>




            <h2>پنل پیامک</h2>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="set_timer">رمان اعتبار کد های ارسالی</label></th>
                        <td><input name="set_timer" type="text" id="set_timer"
                                value="<?php echo $oni_option[ 'set_timer' ] ?>" class="regular-text onlyNumbersInput"
                                inputmode="numeric" pattern="\d*"> دقیقه
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="set_code_count">تعداد کارکتر های پیامک</label></th>
                        <td><input name="set_code_count" type="text" id="set_code_count"
                                value="<?php echo $oni_option[ 'set_code_count' ] ?>"
                                class="regular-text onlyNumbersInput" inputmode="numeric" pattern="\d*">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sms_text_otp">متن پیامک کد تایید</label></th>
                        <td>
                            <textarea rows="4" name="sms_text_otp" type="number" id="sms_text_otp"
                                class="regular-text"><?php echo $oni_option[ 'sms_text_otp' ] ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">نوع پنل پیامک</th>
                        <td class="radio-td">
                            <fieldset>
                                <label><input type="radio" name="sms_type"
                                        <?php checked($oni_option[ 'sms_type' ], 'notificator')?> value="notificator">
                                    <span class="date-time-text">notificator</span></label>
                                <label><input type="radio" name="sms_type"
                                        <?php checked($oni_option[ 'sms_type' ], 'tsms')?> value="tsms"> <span
                                        class="date-time-text">tsms</span></label>
                                <label><input type="radio" name="sms_type"
                                        <?php checked($oni_option[ 'sms_type' ], 'ghasedaksms')?> value="ghasedaksms">
                                    <span class="date-time-text">ghasedaksms</span></label>
                            </fieldset>
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
        <h2>پاک کردن سوال ها</h2>



        <p class="submit">
            <button type="button" id="del_question" class="button button-primary button-error">پاک کردن</button>
        </p>
    </div>


    <div class="clear"></div>
    <div class="oni-loader "></div>
</div>