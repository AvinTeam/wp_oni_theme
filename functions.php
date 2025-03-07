<?php

(defined('ABSPATH')) || exit;

date_default_timezone_set('Asia/Tehran');

define('ONI_VERSION', '2.2.14');

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
define('ONI_TOKEN', '1|L6niilLOBERWI0P6ftbfDLT7hfmry7iut7geWdD85e2f5836');

require_once ONI_PATH . 'vendor/autoload.php';

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
require_once ONI_INCLUDES . '/cron.php';

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

// if (isset($_GET[ 'test' ])) {

// print_r($_GET);
// exit;

// }

$my_config_db_users = [
    [
        'DB_USER'     => 'ayehonli_mytest',
        'DB_PASSWORD' => '7esjaHE7si',
     ],
    [
        'DB_USER'     => 'ayehonli_pmrr',
        'DB_PASSWORD' => 'uW7zTRj5',
     ],
    [
        'DB_USER'     => 'ayehonli_onitest',
        'DB_PASSWORD' => 'ptq0JTZ3sn',
     ],
    [
        'DB_USER'     => 'ayehonli_oni1test',
        'DB_PASSWORD' => 'WjFxJQvyE8',
     ],
    [
        'DB_USER'     => 'ayehonli_test2oni',
        'DB_PASSWORD' => 'WjFxJQvyE8',
     ],
    [
        'DB_USER'     => 'ayehonli_ayehonli_test3oni',
        'DB_PASSWORD' => 'WjFxJQvyE8',
     ],
    [
        'DB_USER'     => 'ayehonli_test4oni',
        'DB_PASSWORD' => 'QYmLIw7wjh',
     ],

    [
        'DB_USER'     => 'ayehonli_test5oni',
        'DB_PASSWORD' => '0ICOphC6F',
     ],
 ];

$randomKey = array_rand($my_config_db_users);

$db_users = $my_config_db_users[ $randomKey ];