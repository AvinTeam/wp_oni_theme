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
                        <th scope="row"><label for="question">سوال</label></th>
                        <td>
                            <textarea class="w-100" rows="10"
                                name="question"><?php echo $ayeh[ 'question' ] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option1">گزینه اول</label></th>
                        <td><input name="option1" type="text" id="option1" value="<?php echo $ayeh[ 'option1' ] ?>"
                                class="w-100">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option2">گزینه دوم</label></th>
                        <td><input name="option2" type="text" id="option2" value="<?php echo $ayeh[ 'option2' ] ?>"
                                class="w-100">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option3">گزینه سوم</label></th>
                        <td><input name="option3" type="text" id="option3" value="<?php echo $ayeh[ 'option3' ] ?>"
                                class="w-100">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option4">گزینه چهارم</label></th>
                        <td><input name="option4" type="text" id="option4" value="<?php echo $ayeh[ 'option4' ] ?>"
                                class="w-100">
                        </td>
                    </tr>


                    <tr>
                        <th scope="row">پاسخ درست</th>
                        <td class="radio-td">
                            <fieldset>
                                <input type="radio" name="answer" value="0" checked class="d-none">

                                <label><input type="radio" name="answer"                                                                         <?php checked(1, $ayeh[ 'answer' ])?>
                                        value="1"><span class="date-time-text">1</span></label>
                                <label><input type="radio" name="answer"                                                                         <?php checked(2, $ayeh[ 'answer' ])?>
                                        value="2"><span class="date-time-text">2</span></label>
                                <label><input type="radio" name="answer"                                                                         <?php checked(3, $ayeh[ 'answer' ])?>
                                        value="3"><span class="date-time-text">3</span></label>
                                <label><input type="radio" name="answer"                                                                         <?php checked(4, $ayeh[ 'answer' ])?>
                                        value="4"><span class="date-time-text">4</span></label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>


            <p class="submit">
                <button type="submit" name="oni_act" value="oni__submit_question" id="submit"
                    class="button button-primary"><?php echo(isset($_GET[ 'update_item' ])) ? 'ویرایش سوال' : 'افزودن سوال'; ?>
                </button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>