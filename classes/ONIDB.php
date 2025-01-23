<?php

class ONIDB
{

    private $wpdb;
    private $tablename;

    public function __construct($table)
    {
        global $wpdb;

        $this->wpdb      = $wpdb;
        $this->tablename = $wpdb->prefix . 'oni_' . $table;

    }

    public function insert(array $data, array $format): int | false
    {

        foreach ($data as $key => $value) {
            $data[ $key ] = $value;
        }

        $inserted = $this->wpdb->insert(
            $this->tablename,
            $data,
            $format

        );

        return ($inserted) ? $this->wpdb->insert_id : false;

    }

    public function update(array $data, array $where, array $format = null, array $where_format = null): int | false
    {

        $result = false;

        if ($data && $where) {

            $result = $this->wpdb->update(
                $this->tablename,
                $data,
                $where,
                $format,
                $where_format
            );
        }
        return $result;

    }

    public function delete(array $data, array $format): int | false
    {

        foreach ($data as $key => $value) {
            $data[ $key ] = $value;
        }

        $result = false;
        if (! empty($data)) {

            $result = $this->wpdb->delete(
                $this->tablename,
                $data,
                $format

            );

        }

        return $result;

    }

    public function get(array $data, array $format): object | array | false
    {

        $array = [  ];
        $where = '';
        $m     = 0;
        foreach ($data as $key => $value) {
            $where .= ' AND %i = ' . $format[ $m ];
            $array[  ] = $key;
            $array[  ] = $value;
            $m++;
        }

        $result = false;
        if (! empty($data)) {

            $result = $this->wpdb->get_row(
                $this->wpdb->prepare(
                    "SELECT * FROM `$this->tablename` WHERE 1=1 $where",
                    $array
                )
            );
        }
        return $result;
    }

    public function num(array $data, array $format): int | string
    {

        $where = "";

        $m = 0;
        foreach ($data as $key => $value) {

            switch ($format[ $m ]) {
                case '%s':
                    $value = "'$value'";
                    break;
                default:
                    $value = $value;
                    break;
            }

            $where .= " AND  `$key` = $value ";

            $m++;
        }

        $num = $this->wpdb->get_var("SELECT COUNT(*) FROM $this->tablename WHERE 1=1  $where ");

        return absint($num);

    }

    public function select(int $per_page, int $offset, string $status = '', string $date = ''): array | object | null
    {
        $sqlwhere = '';

        if (empty($status)) {
            $sqlwhere .= " AND status !='sms' ";
        } elseif (! empty($status)) {
            $sqlwhere .= " AND status ='$status' ";
        }

        if (! empty($date)) {
            $sqlwhere .= " AND created_at <= '$date' ";

        }

        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM `$this->tablename` WHERE 1=1  $sqlwhere ORDER BY `created_at` DESC LIMIT %d OFFSET %d",
                [ $per_page, $offset ]
            ), ARRAY_A
        );
        return $mpn_row;

    }

}
