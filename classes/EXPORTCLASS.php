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

        if (!empty($date[ 'datestart' ]) && !empty($date[ 'datestart' ])) {
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
                SUM(m.count_true) AS total_true
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

        if (!empty($date[ 'datestart' ]) && !empty($date[ 'datestart' ])) {
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

}
