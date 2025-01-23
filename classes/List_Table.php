<?php

(defined('ABSPATH')) || exit;

if (!class_exists('WP_List_Table')) {

    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

class List_Table extends WP_List_Table
{

    private $all_results;
    private $par_page;
    private $numsql;
    private $m;

    public function oni_res($rows)
    {
        $this->all_results = $rows[ 'all_results' ];
        $this->par_page = $rows[ 'par_page' ];
        $this->numsql = $rows[ 'nasrdb' ];
        $this->m = $rows[ 'offset' ];
    }

    public function get_columns()
    {
        return [
            'oni_row' => '#',
            'full_name' => 'نام و نام خانوادگی',
            'avatar' => 'تصویر',
            'mobile' => 'شماره موبایل',
            'ostan' => 'استان',
            'signature' => 'امضا',
            'description' => 'توضیحات',
            'created_at' => 'تاریخ ثبت',
            'status' => 'وضعیت',
            'oni_update' => '',

         ];
    }

    public function column_default($item, $column_name)
    {

        if (isset($item[ $column_name ])) {
            return wp_kses($item[ $column_name ], [
                'span' => [  ],
             ]);
        }
        return '-';
    }

    public function column_ostan($item)
    {

        $provinces = oni_remote('https://api.mrrashidpour.com/iran/provinces.json');

        return ($provinces[ 'code' ] == 0 && absint($item[ 'ostan' ])) ? get_name_by_id($provinces[ 'result' ], absint($item[ 'ostan' ])) : 'نامعلوم';
    }

    public function column_avatar($item)
    {
        return sprintf('<img src="%s" style="height:50px;">', oni_panel_image('avatar/' . $item[ 'avatar' ] . '.jpg'));
    }

    public function column_signature($item)
    {
        return sprintf('<img src="%s" style="border-radius: 10px;height: 50px;object-fit: cover;">', $item[ 'signature' ]);
    }

    public function column_status($item)
    {

        switch ($item[ 'status' ]) {
            case 'successful':
                $type = '<span class = "successful dashicons-before dashicons-yes-alt">تایید شده</span>';
                break;
            case 'waiting':
                $type = '<span class = "progress dashicons-before dashicons-warning">نا معلوم</span>';
                break;
            case 'failed':
                $type = '<span class="failed dashicons-before dashicons-dismiss">رد شده</span>';
                break;
            default:
                $type = '-';
                break;
        }

        return $type;
    }

    public function column_created_at($item)
    {
        return tarikh($item[ 'created_at' ]);
    }

    public function column_oni_row($item)
    {
        $this->m++;
        return $this->m;
    }
    public function column_oni_update($item)
    {

        $oni_update = '
            <button data-id="'.$item['ID'].'" data-type="successful" class="button button-primary oni_update_row">تایید امضا</button>
            <button data-id="'.$item['ID'].'" data-type="failed" class="button action oni_update_row">رد امضا</button>
            <button data-id="'.$item['ID'].'" data-type="delete" class="button button-primary button-error oni_update_row">حذف امضا</button>
        ';

        return $oni_update;
    }

    public function get_bulk_actions()
    {

        if (current_user_can('manage_options')) {
            $action[ 'delete' ] = esc_html__('delete', 'mraparat');
            $action[ 'delete' ] = esc_html__('delete', 'mraparat');
        }
        return $action;
    }

    public function no_items()
    {

        echo 'چیزی یافت نشد';

    }

    public function get_sortabele_colums()
    {

        // return [
        //     'amount' => [ 'amount', true ],
        //     'created_at' => [ 'created', true ],
        //  ];

    }

    public function prepare_items()
    {

        $this->process_bulk_action();

        $this->set_pagination_args([
            'total_items' => intval($this->numsql),
            'per_page' => $this->par_page,
         ]);
        $this->_column_headers = [
            $this->get_columns(),
            [  ],
            $this->get_sortabele_colums(),
            'full_name',
         ];
        $this->items = $this->all_results;

    }

    private function create_view($key, $label, $url, $count = 0)
    {
        $current_status = isset($_GET[ 'status' ]) ? $_GET[ 'status' ] : 'all';

        $view_tag = sprintf('<a href="%s" %s>%s</a>', $url, $current_status == $key ? 'class="current"' : '', $label);

        $view_tag .= sprintf('<span class="count">(%d)</span>', $count);

        return $view_tag;
    }

    protected function get_views()
    {

        $nasrdb = new NasrDB();

        return [
            'all' => $this->create_view('all', 'همه', admin_url('admin.php?page=nasr'), $nasrdb->num()),
            'successful' => $this->create_view('successful', 'تایید شده', admin_url('admin.php?page=nasr&status=successful'), $nasrdb->num('', 'successful')),
            'waiting' => $this->create_view('waiting', 'نامعلوم', admin_url('admin.php?page=nasr&status=waiting'), $nasrdb->num('', 'waiting')),
            'failed' => $this->create_view('failed', 'رد شده', admin_url('admin.php?page=nasr&status=failed'), $nasrdb->num('', 'failed')),
         ];
    }

    protected function extra_tablenav($which)
    {
        if ('top' === $which) {
            ?>
<!-- <div class="alignleft actions">
    <a href="<?php echo esc_url(oni_end_url('action','download_csv')); ?>"
        class="button button-primary">دانلود CSV</a>
    <a href="<?php echo esc_url(oni_end_url('action','download_exel')); ?>"
        class="button button-primary">دانلود exel</a>
</div> -->
<?php
}
    }

}