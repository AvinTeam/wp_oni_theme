<?php get_header();

$is_has_page = (is_user_logged_in()) ? 'dashboard' : 'login';

require_once ONI_VIEWS . "home/$is_has_page.php";

get_footer();
