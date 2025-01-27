<?php

(defined('ABSPATH')) || exit;
function oni_row_install()
{

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $wpdb_collate = $wpdb->collate;

    $tabel_question_row = $wpdb->prefix . 'oni_question';
    $sql_question       = "CREATE TABLE IF NOT EXISTS `$tabel_question_row` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `title` text COLLATE $wpdb_collate NOT NULL,
                        `option1` text COLLATE $wpdb_collate NOT NULL,
                        `option2` text COLLATE $wpdb_collate NOT NULL,
                        `option3` text COLLATE $wpdb_collate NOT NULL,
                        `option4`text COLLATE $wpdb_collate NOT NULL,
                        `answer` int NOT NULL,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_question);

    $tabel_match_row = $wpdb->prefix . 'oni_match';
    $sql_question    = "CREATE TABLE IF NOT EXISTS `$tabel_match_row` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `eid` varchar(100) COLLATE $wpdb_collate NOT NULL,
                        `iduser` bigint NOT NULL,
                        `count_questions` int NOT NULL,
                        `count_true` int NOT NULL DEFAULT '0',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate";

    dbDelta($sql_question);

}

add_action('after_switch_theme', 'oni_row_install');