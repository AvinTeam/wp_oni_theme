<?php

(defined('ABSPATH')) || exit;
header('Content-Type: text/html; charset=utf-8');

define('ONI_VERSION', '1.2.1');

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

//     // print_r($_GET);
//     // exit;

// }
