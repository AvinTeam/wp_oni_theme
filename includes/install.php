<?php

(defined('ABSPATH')) || exit;
function oni_row_install()
{

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $wpdb_collate = $wpdb->collate;

    $table_question_row = $wpdb->prefix . 'oni_question';
    $sql_question       = "CREATE TABLE IF NOT EXISTS `$table_question_row` (
                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                            `chapter` varchar(255) COLLATE $wpdb_collate NOT NULL,
                            `chapter_number` int NOT NULL,
                            `verse` int NOT NULL,
                            `q_type` varchar(20) CHARACTER SET utf8mb4 COLLATE $wpdb_collate NOT NULL,
                            `question` text CHARACTER SET utf8mb4 COLLATE $wpdb_collate NOT NULL,
                            `option1` text COLLATE $wpdb_collate NOT NULL,
                            `option2` text COLLATE $wpdb_collate NOT NULL,
                            `option3` text COLLATE $wpdb_collate NOT NULL,
                            `option4` text COLLATE $wpdb_collate NOT NULL,
                            `answer` int NOT NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1674 DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_question);

    $table_match_row = $wpdb->prefix . 'oni_match';
    $sql_match       = "CREATE TABLE IF NOT EXISTS `$table_match_row` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `eid` varchar(100) COLLATE $wpdb_collate NOT NULL,
                        `iduser` bigint NOT NULL,
                        `count_questions` int NOT NULL,
                        `true_questions` text COLLATE $wpdb_collate NOT NULL,
                        `count_true` int NOT NULL DEFAULT '0',
                        `score` bigint unsigned NOT NULL,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_match);

    $table_cron_row = $wpdb->prefix . 'oni_cron';
    $sql_cron       = "CREATE TABLE IF NOT EXISTS `$table_cron_row`(
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `match_id` bigint unsigned NOT NULL,
                        `send_array` text COLLATE $wpdb_collate NOT NULL,
                        `tracking` int NOT NULL DEFAULT '0',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_cron);

    $table_cron_row = $wpdb->prefix . 'oni_cron_error';
    $sql_cron_error = "CREATE TABLE IF NOT EXISTS `$table_cron_row` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `match_id` bigint unsigned NOT NULL,
                        `send_array` text NOT NULL,
                        `error_code` int NOT NULL,
                        `cron_error` longtext NOT NULL,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_cron_error);

}

add_action('after_switch_theme', 'oni_row_install');
