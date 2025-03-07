<?php
define('WP_CACHE', true); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ayehonli_wmrr');

/** Database username */
define('DB_USER', $db_users[ 'DB_USER' ]);
// define('DB_USER', 'ayehonli_mytest');

/** Database password */
define('DB_PASSWORD', $db_users[ 'DB_PASSWORD' ]);
// define('DB_PASSWORD', '7esjaHE7si');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'KsI]im0Ez?@(]a}^ir^`|!%_k`^1lxJ6kD>Xer1`+0C{P2KJ-;92pf3KO7)G:u}Q');
define('SECURE_AUTH_KEY', 'o(TU8sqs&ESiYwMp;CZ#F:|e#O|g+R/f;QMZfS]kExcqHk^|]^B3a[/7LW!ymx&[');
define('LOGGED_IN_KEY', '|9Yh<Uvw4 N3YU#R E|.9w+*|WJazeq6#ROR }0hPZ@<i%HR@8e2hd 5cR{he7Em');
define('NONCE_KEY', '96}|vMMX?x8EVDN5|QoJFkf%+>y;0gMPfL#ah>cBWD9n2vEK+*FC|^I;j hXJ=tY');
define('AUTH_SALT', 'W?-upp[KAMBR4T%t<v*YEsnhy~Y#d+:K,<wN>&H$-*;8yfF-n@G[x0xXFi]<Y+8G');
define('SECURE_AUTH_SALT', '&5}I[`YE3=sQ@A(TQ!!#< 3ZE3T.+%A^n.:+Uwp(iU|(,oO||l,o?p/V-}}8&7QY');
define('LOGGED_IN_SALT', '}gG&WH:{4]1,!-rb~m|I4T||,j,K#esrt+ D]6*EI[xc^ hwu6F{RvGi+n]_sOeV');
define('NONCE_SALT', 'Rs~rc%h!jNpm[*NVy4$|JH;4.2p.]yVfpT~4_s=%Pg`z!.V@x$aH_5@xp1[hyL:-');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */

define('DISABLE_WP_CRON', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';