<?php
define('WP_CACHE', true);
define('WP_MEMORY_LIMIT', '2560M');

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
        'DB_USER'     => 'ayehav_db',
        'DB_PASSWORD' => 'ahD2waehsqyh8nUCM2qY',
     ],
     [
         'DB_USER'     => 'ayehav_mrrdb',
         'DB_PASSWORD' => 'QmvfjjMtwkuLBVqUbxSq',
      ],
    [
        'DB_USER'     => 'ayehav_mrrdb1',
        'DB_PASSWORD' => 'hEDGS54FfTyREqAatGKy',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb2',
        'DB_PASSWORD' => 'hEDGS54FfTyREqAatGKy',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb3',
        'DB_PASSWORD' => 'Rp3Lkxs2pCWEMDE7j47x',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb4',
        'DB_PASSWORD' => 'mX9WH2BFZ4tZ3ntjvFkk',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb5',
        'DB_PASSWORD' => 'pUMGSpezWNM5TgZA7xFY',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb6',
        'DB_PASSWORD' => 'F3YEgGAXZTcQQxbJNg5L',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb7',
        'DB_PASSWORD' => 'hjSsQbWEZRNQxrfKhLPG',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb8',
        'DB_PASSWORD' => '9MLcDsFSGyn37mjmNQNP',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb9',
        'DB_PASSWORD' => 'xvaXyCT4B8J3dEcvH5tn',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb10',
        'DB_PASSWORD' => 'cSw7yCcN66VkycykppbH',
     ],
    [
        'DB_USER'     => 'ayehav_mrrdb11',
        'DB_PASSWORD' => 'tPHHRDRGCzVxAKQKauNR',
     ],
 ];

//  mrrdb
$randomKey = array_rand($my_config_db_users);

$db_users = $my_config_db_users[ $randomKey ];

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ayehav_db');

/** Database username */
define('DB_USER', $db_users[ 'DB_USER' ]);

/** Database password */
define('DB_PASSWORD', $db_users[ 'DB_PASSWORD' ]);

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
define('AUTH_KEY', 'B^Z@G8-QbEGe0=zUN|O]0m,G0:r_]y__S>@eKUoE.FV,T*pXX-CH@Nh#{++b:-h@');
define('SECURE_AUTH_KEY', 'a-.yS}:z2|LaJ/WZrIa)9?I`g]i>rYE~Ix.tIs+d)_h?b@q+&@XyEA4=D,)c&rLd');
define('LOGGED_IN_KEY', ')S`v6R!:8Q|E}[tgB{*B6m!(MX^Lb0:fl0zE-ds_y&wA@l2NYqiB-3(|/[^t<h%i');
define('NONCE_KEY', 'Er}?GSGT@f5J?3cou=R[fceA-i_&23n=w_$*XQ|Y-AtL*dqr=<xu q! 2aYTy.Q ');
define('AUTH_SALT', '<1(M,=&E[L}(|nX}7bkML$6`l%]-g_.x!1rZD9y,X<|;&>JRmr[+K-HYq(Ue^!g ');
define('SECURE_AUTH_SALT', 'swm:19~fUub-rxCc1dI5:A*eQcKNj|,PxcU]m4GX,D{!+.<YO7D~@%uxPuUpF9c&');
define('LOGGED_IN_SALT', 'wP:9pEVGcf=($$fbmp+B.vH/BTrZpvf>d&pblPzt7jSTvK5$KizL_3mJkDb9oT+}');
define('NONCE_SALT', 'E!K@fSX<UF,C~@bVI2EpJkTW{f@Czic)2.k}3Ps>bi1eI-8NQ&b8jV#]!A,TT*C,');

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
