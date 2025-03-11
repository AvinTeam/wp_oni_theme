<?php
namespace oniclass;

class oni_export extends ONIDB
{

    public function __construct($table)
    {
        ONIDB::__construct($table);

    }

    public function getuser($date)
    {
        $where = "";

        if (! empty($date[ 'datestart' ]) && ! empty($date[ 'datestart' ])) {
            $datestart = tarikh($date[ 'datestart' ]);
            $dateend   = tarikh($date[ 'dateend' ]);

            $where = "WHERE
                        DATE(created_at) >= '$datestart'
                        AND
                        DATE(created_at) <= '$dateend'";

        }

        $usertd = $this->wpdb->users;

        $result = $this->wpdb->get_results(
            "SELECT
                DATE(m.created_at) AS unique_date,
                u.user_login AS username,
                COUNT(*) AS total_rows,
                SUM(m.score) AS total_true
            FROM
                `$this->tablename` m
            INNER JOIN
                `$usertd`  u
            ON
                m.iduser = u.ID

                $where

            GROUP BY
                DATE(m.created_at), u.user_login
            ORDER BY
                unique_date ASC, u.user_login ASC
            ");

        return $result;

    }

    public function getall($date)
    {

        $where = "";

        if (! empty($date[ 'datestart' ]) && ! empty($date[ 'datestart' ])) {
            $datestart = tarikh($date[ 'datestart' ]);
            $dateend   = tarikh($date[ 'dateend' ]);

            $where = "WHERE
                        DATE(created_at) >= '$datestart'
                        AND
                        DATE(created_at) <= '$dateend'";

        }

        $result = $this->wpdb->get_results(
            "SELECT
                DATE(created_at) AS unique_date,
                COUNT(DISTINCT iduser) AS unique_users,
                COUNT(*) AS total_rows

            FROM
                `$this->tablename`

             $where
            GROUP BY
                DATE(created_at)
            ORDER BY
                unique_date ASC;");

        return $result;

    }

    public function get_by_user(string $date = '', string $order = '')
    {

        $userid = get_current_user_id();
        $where  = "";

        if (! empty($date)) {
            $dateend = tarikh($date);
            $where   = "AND DATE(created_at) = '$dateend'";
        }
        if (empty($order)) {
            $order = 'unique_date DESC';
        }

        $result = $this->wpdb->get_results(
            "SELECT
                DATE(created_at) AS unique_date,
                SUM(count_questions) AS total_count_questions,
                SUM(count_true) AS total_count_true,
                SUM(score) AS total_score,
                COUNT(*) AS total_match

            FROM `$this->tablename`
            WHERE
                iduser = $userid

                $where
            GROUP BY DATE(created_at)
            ORDER BY $order;");

        return $result;

    }

    public function get_today()
    {
        $this_data    = date('Y-m-d');
        $this_user_id = get_current_user_id();

        $result = $this->wpdb->get_row(
            "SELECT
                DATE(created_at) AS unique_date,
                COUNT(DISTINCT iduser) AS unique_users,
                COUNT(*) AS total_rows,
                SUM(score) AS total_score
            FROM
                `$this->tablename`

              WHERE `iduser` = $this_user_id AND DATE(`created_at`) = '$this_data'
            GROUP BY
                DATE(created_at)
            ORDER BY
                unique_date ASC;");

        if (! $result) {

            $result = (object) [
                'total_rows'  => 0,
                'total_score' => 0,
             ];

        }

        return $result;

    }

    public function get_all_info_match(int $user_id = 0)
    {
        $this_user_id = ($user_id) ? $user_id : get_current_user_id();

        $result = $this->wpdb->get_row(
            "SELECT
                SUM(count_questions) AS total_count_questions,
                SUM(count_true) AS total_count_true,
                SUM(score) AS total_score,
                COUNT(*) AS total_match
            FROM
                `$this->tablename`

              WHERE `iduser` = $this_user_id");

        if (! $result) {

            $result = (object) [
                'total_count_questions' => 0,
                'total_count_true'      => 0,
                'total_score'           => 0,
                'total_match'           => 0,
             ];

        }

        return $result;

    }

    public function get_exam()
    {

        $where = (isset($_COOKIE[ 'setcookie_oni_shown_ids' ])) ? "WHERE id NOT IN (" . $_COOKIE[ 'setcookie_oni_shown_ids' ] . ")" : ' ';

        $result = $this->wpdb->get_results(
            "SELECT * FROM `$this->tablename`
                  $where
                  ORDER BY RAND()
                  LIMIT 5");

        $exam      = [  ];
        $answers   = [  ];
        $shown_ids = [  ];

        foreach ($result as $ayeh) {

            $exam[  ]                   = $ayeh;
            $answers[ 'Q' . $ayeh->id ] = $ayeh->answer;
            $shown_ids[  ]              = $ayeh->id;

        }
        if (isset($_COOKIE[ 'setcookie_oni_shown_ids' ])) {

            $cookie_to_array = explode(',', $_COOKIE[ 'setcookie_oni_shown_ids' ]);

            $shown_ids = array_unique(array_merge($cookie_to_array, $shown_ids));

            if (count($shown_ids) > 20) {
                $shown_ids = [  ];
            }

        }

        setcookie("setcookie_oni_shown_ids", implode(',', $shown_ids), time() + 3600, "/");

        $array = [
            'exam'    => $exam,
            'answers' => $answers,
         ];

        return (object) $array;

    }

}