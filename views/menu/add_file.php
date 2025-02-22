<?php
    (defined('ABSPATH')) || exit;
    global $title;

?>

<div id="wpbody-content">
    <div class="wrap oni_menu">
        <h1><?php echo esc_html($title) ?></h1>


        <hr class="wp-header-end">

        <form method="post" action="" novalidate="novalidate" enctype="multipart/form-data">
            <?php wp_nonce_field('oni_nonce' . get_current_user_id()); ?>
            <p>برای وارد کردن سوال دقت کنید که در فیلد های زیر باید اسم ستون مربوطه را وارد کنید</p>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="excel_file">فایل اکسل</label></th>
                        <td><input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required> </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="question">سوال</label></th>
                        <td><input name="frm[question]" type="text" id="question" class="regular-text" placeholder="ستون سوال در اکسل"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option1">گزینه اول</label></th>
                        <td><input name="frm[option1]" type="text" id="option1" class="regular-text" placeholder="ستون گزینه اول در اکسل"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option2">گزینه دوم</label></th>
                        <td><input name="frm[option2]" type="text" id="option2" class="regular-text" placeholder="ستون سوال گزینه دوم در اکسل"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option3">گزینه سوم</label></th>
                        <td><input name="frm[option3]" type="text" id="option3" class="regular-text" placeholder="ستون سوال گزینه سوم در اکسل"></td>
                    </tr>
                    </tr>
                    <tr>
                        <th scope="row"><label for="option4">گزینه چهارم</label></th>
                        <td><input name="frm[option4]" type="text" id="option4" class="regular-text" placeholder="ستون سوال گزینه چعارم در اکسل"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="answer">پاسخ درست</label></th>
                        <td><input name="frm[answer]" type="text" id="answer" class="regular-text" placeholder="ستون پاسخ درست در اکسل"></td>
                    </tr>
                    </tr>
                </tbody>
            </table>


            <p class="submit">
                <button type="submit" name="oni_act" value="oni__import" id="submit"
                    class="button button-primary">افزودن سوال</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>