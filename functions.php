<?php

use Dotenv\Dotenv;
use oniclass\ONIDB;

(defined('ABSPATH')) || exit;

date_default_timezone_set('Asia/Tehran');

//enable_maintenance_mode_oni();

define('ONI_VERSION', '2.3.5');

define('ONI_PATH', get_template_directory() . "/");
define('ONI_INCLUDES', ONI_PATH . 'includes/');
define('ONI_CLASS', ONI_PATH . 'classes/');
define('ONI_CORE', ONI_PATH . 'core/');
define('ONI_FUNCTION', ONI_PATH . 'functions/');
define('ONI_VIEWS', ONI_PATH . 'views/');

define('ONI_URL', get_template_directory_uri() . "/");
define('ONI_ASSETS', ONI_URL . 'assets/');
define('ONI_CSS', ONI_ASSETS . 'css/');
define('ONI_JS', ONI_ASSETS . 'js/');
define('ONI_IMAGE', ONI_ASSETS . 'image/');
define('ONI_VENDOR', ONI_ASSETS . 'vendor/');
define('ONI_QUESTION_SCORE', 1);
define('ONI_PER_PAGE', 10);
define('ONI_TOKEN', '1|L6niilLOBERWI0P6ftbfDLT7hfmry7iut7geWdD85e2f5836');

require_once ONI_PATH . 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once ONI_CORE . '/accesses.php';
require_once ONI_INCLUDES . '/theme_filter.php';
require_once ONI_INCLUDES . '/theme-function.php';
require_once ONI_INCLUDES . '/ajax.php';
require_once ONI_INCLUDES . '/styles.php';
require_once ONI_INCLUDES . '/init.php';
require_once ONI_INCLUDES . '/jdf.php';

require_once ONI_CLASS . '/ONIDB.php';
require_once ONI_CLASS . '/EXPORTCLASS.php';
require_once ONI_CLASS . '/Cipher.php';
require_once ONI_CLASS . '/Rabbitmq.php';
require_once ONI_INCLUDES . '/rabbitmq.php';
require_once ONI_INCLUDES . '/cron.php';
require_once ONI_INCLUDES . '/handle_download.php';

$oni_option = oni_start_working();

// require_once ONI_INCLUDES . '/postype.php';
// require_once ONI_INCLUDES . '/meta_boxs.php';
// require_once ONI_CLASS . '/Iran_Area.php';
// require_once ONI_INCLUDES . '/init_user_submit.php';

if (is_admin()) {
    require_once ONI_CLASS . '/List_Table.php';
    require_once ONI_INCLUDES . '/menu.php';
    require_once ONI_INCLUDES . '/install.php';
    require_once ONI_INCLUDES . '/edit_user_table.php';
//     require_once ONI_INCLUDES . '/edit_column_institute.php';
//
//     require_once ONI_INCLUDES . '/handle_download.php';

}

if (isset($_GET[ 'mydbreal' ])) {

    // exit;

}

function enable_maintenance_mode_oni()
{
    if (! current_user_can('administrator')) {
        // لاگ‌اوت کردن کاربران غیر ادمین
        wp_logout();

        // نمایش پیام زیبا و ریسپانسیو به کاربران
        $html = '
        <!DOCTYPE html>
        <html lang="fa" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>سایت در حال بروزرسانی است</title>
            <style>
                body {
                    font-family: IRANSansX;
                    background: linear-gradient(135deg, #393C97, #37BEC1);
                    color: #fff;
                    text-align: center;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .maintenance-container {
                    max-width: 90%;
                    width: 100%;
                    padding: 20px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 15px;
                    backdrop-filter: blur(10px);
                    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    font-size: 2rem;
                    margin-bottom: 20px;
                    color: #fff;

                }
                p {
                    font-size: 1rem;
                    line-height: 1.6;
                }
                .icon {
                    font-size: 3rem;
                    margin-bottom: 20px;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 0.9rem;
                    opacity: 0.8;
                }

                /* رسپانسیو برای دستگاه‌های کوچک */
                @media (max-width: 600px) {
                    h1 {
                        font-size: 1.5rem;
                    }
                    p {
                        font-size: 0.9rem;
                    }
                    .icon {
                        font-size: 2.5rem;
                    }
                    .footer {
                        font-size: 0.8rem;
                    }
                }

                /* رسپانسیو برای دستگاه‌های بسیار کوچک */
                @media (max-width: 400px) {
                    h1 {
                        font-size: 1.2rem;
                    }
                    p {
                        font-size: 0.8rem;
                    }
                    .icon {
                        font-size: 2rem;
                    }
                    .footer {
                        font-size: 0.7rem;
                    }
                }
            </style>
        </head>
        <body style="margin-top: 0;">
            <div class="maintenance-container">
                <div class="icon">🚧</div>
                <h1>سایت در حال بروزرسانی است</h1>
                <p>ما در حال انجام برخی به‌روزرسانی‌های ضروری هستیم. لطفاً بعداً مراجعه کنید.</p>
                <div class="footer">با تشکر از صبر و شکیبایی شما!</div>
            </div>
        </body>
        </html>
        ';

        // نمایش پیام و خاتمه اجرای اسکریپت
        wp_die($html, 'سایت در حال بروزرسانی است', [ 'response' => 503 ]);
    }
}
