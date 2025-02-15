<?php
namespace oniclass;

class ONIDB
{

    protected $wpdb;
    protected $tablename;

    public function __construct($table)
    {
        global $wpdb;

        $this->wpdb      = $wpdb;
        $this->tablename = $wpdb->prefix . 'oni_' . $table;

    }

    public function insert(array $data): int | false
    {

        $format = [  ];
        foreach ($data as $key => $value) {
            $data[ $key ] = $value;
            $format[  ]   = $this->set_type($value);

        }

        $inserted = $this->wpdb->insert(
            $this->tablename,
            $data,
            $format

        );

        return ($inserted) ? $this->wpdb->insert_id : false;

    }

    public function update(array $data, array $where): int | false
    {

        if (! $data && ! $where) {return false;}

        $format = $where_format = [  ];

        foreach ($data as $value) {
            $format[  ] = $this->set_type($value);
        }

        foreach ($where as $value) {
            $where_format[  ] = $this->set_type($value);

        }

        $result = $this->wpdb->update(
            $this->tablename,
            $data,
            $where,
            $format,
            $where_format
        );

        return $result;

    }

    public function delete(array $data): int | false
    {
        $format = [  ];
        foreach ($data as $key => $value) {
            $data[ $key ] = $value;
            $format[  ]   = $this->set_type($value);
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

    public function get(array $data, string $output = OBJECT): object | array | false
    {

        if (empty($data)) {return false;}

        $where = '1=1';

        foreach ($data as $key => $value) {
            $where .= $this->wpdb->prepare(' AND %i = ' . $this->set_type($value), $key, $value);
        }

        $result = $this->wpdb->get_row(
            "SELECT * FROM `$this->tablename` WHERE $where",
            $output
        );

        return $result;
    }

    public function num(array $data = [  ], string $where = ''): int | string
    {
        $sqlwhere = "";

        if (! empty($where)) {

            $sqlwhere = " AND  $where ";

        }

        if (! empty($data)) {

            foreach ($data as $key => $value) {

                switch ($this->set_type($value)) {
                    case '%s':
                        $value = "'$value'";
                        break;
                    default:
                        $value = $value;
                        break;
                }

                $sqlwhere .= " AND  `$key` = $value ";
            }
        }

        $num = $this->wpdb->get_var("SELECT COUNT(*) FROM $this->tablename WHERE 1=1  $sqlwhere ");

        return absint($num);

    }

    public function select(array $args = [  ]): array | object | null
    {
        $where = '1=1 ';

        if (isset($args[ 'data' ])) {
            foreach ($args[ 'data' ] as $key => $value) {

                if (is_array($value)) {
                    $where .= ' AND (';
                    foreach ($value as $i => $row) {
                        if ($i == 1) {$where .= ' OR ';}
                        $where .= $this->wpdb->prepare(' %i = ' . $this->set_type($row), $key, $row);
                    }
                    $where .= ')';
                } else {
                    $where .= $this->wpdb->prepare(' AND %i = ' . $this->set_type($value), $key, $value);

                }

            }
        }

        if (isset($args[ 's' ])) {
            $where .= $this->wpdb->prepare(" AND %i LIKE %s", $args[ 's' ][ 0 ], '%' . $args[ 's' ][ 1 ] . '%');
        }

        if (isset($args[ 'where' ])) {
            $where .= " AND  {$args[ 'where' ]} ";
        }

        if (isset($args[ 'order_by' ])) {
            $where .= $this->wpdb->prepare(" ORDER BY %i " . $args[ 'order_by' ][ 1 ], $args[ 'order_by' ][ 0 ]);
        }
        if (isset($args[ 'per_page' ])) {
            $where .= $this->wpdb->prepare(" LIMIT %d ", absint($args[ 'per_page' ]));
        }

        if (isset($args[ 'offset' ])) {
            $where .= $this->wpdb->prepare(" OFFSET %d ", absint($args[ 'offset' ]));
        }

        $star = (isset($args[ 'star' ])) ? $args[ 'star' ] : "*";

        $mpn_row = $this->wpdb->get_results(
            "SELECT $star FROM `$this->tablename` WHERE  $where "
        );
        return $mpn_row;

    }

    public function sum(string $object, array $data, string $where): int | string
    {

        $sqlwhere = "";

        if (! empty($where)) {

            $sqlwhere = " AND  $where ";

        }

        if (! empty($data)) {

            foreach ($data as $key => $value) {

                switch ($this->set_type($value)) {
                    case '%s':
                        $value = "'$value'";
                        break;
                    default:
                        $value = $value;
                        break;
                }

                $sqlwhere .= " AND  `$key` = $value ";
            }
        }

        $num = $this->wpdb->get_var("SELECT SUM($object) FROM `$this->tablename` WHERE 1=1 $sqlwhere ");

        return absint($num);

    }

    protected function set_type($item)
    {
        switch (gettype($item)) {
            case 'integer':
                return '%d';
                break;

            case 'string':
                return '%s';
                break;

            default:
                return '%d';
                break;
        }

    }

    public function empty()
    {

        $empty = $this->wpdb->get_results("TRUNCATE `$this->tablename`  ");

        return $empty;

    }

}
