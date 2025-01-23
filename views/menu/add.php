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
                        <th scope="row"><label for="set_timer">سوال</label></th>
                        <td><?php
                                $editor_array = [
                                    'media_buttons' => false,
                                    'textarea_name' => 'question',
                                    'textarea_rows' => 7,
                                    'tinymce'       => [
                                        'wpautop'                 => true,
                                        'force_p_newlines'        => true,
                                        'br_in_pre'               => true,
                                        'valid_elements'          => '*[*]',
                                        'extended_valid_elements' => 'p[*],br[*],span[*]',
                                        'remove_linebreaks'       => false,
                                     ],
                                 ];

                            wp_editor('', 'form_text', $editor_array);?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option1">گزینه اول</label></th>
                        <td><input name="option1" type="text" id="option1" value="" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option2">گزینه دوم</label></th>
                        <td><input name="option2" type="text" id="option2" value="" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option3">گزینه سوم</label></th>
                        <td><input name="option3" type="text" id="option3" value="" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option4">گزینه چهارم</label></th>
                        <td><input name="option4" type="text" id="option4" value="" class="regular-text">
                        </td>
                    </tr>


                    <tr>
                        <th scope="row">پاسخ درست</th>
                        <td class="radio-td">
                            <fieldset>
                                <label><input type="radio" name="answer" value="1"><span
                                        class="date-time-text">1</span></label>
                                <label><input type="radio" name="answer" value="2"><span
                                        class="date-time-text">2</span></label>
                                <label><input type="radio" name="answer" value="3"><span
                                        class="date-time-text">3</span></label>
                                <label><input type="radio" name="answer" value="4"><span
                                        class="date-time-text">4</span></label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>


            <p class="submit">
                <button type="submit" name="oni_act" value="oni__submit_question" id="submit"
                    class="button button-primary">ذخیرهٔ
                    تغییرات</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>