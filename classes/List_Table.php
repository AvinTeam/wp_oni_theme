<?php
namespace oniclass;

use oniclass\ONIDB;
use WP_List_Table;

    (defined('ABSPATH')) || exit;

    if (! class_exists('WP_List_Table')) {

        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

    }

    class List_Table extends WP_List_Table
    {

        private $all_results;
        private $par_page;
        private $numsql;
        private $m;

        public function get_columns()
        {
            return [
                'cb'         => '<input type="checkbox" >',
                'oni_row'    => '#',
                'question'      => 'سوال',
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
        public function column_cb($item)
        {

            return '<input type="checkbox" name="oni_row[]" value = "' . $item->id . '" >';
        }

        public function column_question($item)
        {

            $oni_update = '
            <a href="' . admin_url('admin.php?page=oni&update_item=' . $item->id) . '">' . $item->question . '</a>
        ';

            return $oni_update;
        }

        public function column_oni_update($item)
        {

            $oni_update = '
            <button type="button" data-id="' . $item->id . '" data-type="delete" class="button button-primary button-error oni_update_row">حذف سوال</button>
        ';

            return $oni_update;
        }

        public function no_items()
        {

            echo 'چیزی یافت نشد';

        }

        public function get_bulk_actions()
        {

            if (current_user_can('manage_options')) {
                $action[ 'delete' ] = 'حذف سوال';
            }
            return $action;
        }

        public function column_oni_row($item)
        {
            $this->m++;
            return $this->m;
        }

        public function process_table_data()
        {

            $onidb = new ONIDB('question');

            $par_page = 20;

            $offset = (isset($_GET[ 'paged' ])) ? ($par_page * absint($_GET[ 'paged' ])) - 1 : 0;

            $args = [
                'per_page' => $par_page,
                'offset'   => $offset,
                'order_by' => [ 'id', 'DESC' ],

             ];

            if (isset($_GET[ 's' ]) && ! empty($_GET[ 's' ])) {
                $args[ 's' ] = [ 'title', $_GET[ 's' ] ];
            }

            $all_results = $onidb->select($args);

            $this->all_results = $all_results;
            $this->par_page    = $par_page;
            $this->numsql      = count($all_results);
            $this->m           = $offset;

        }

        public function prepare_items()
        {

            $this->process_table_data();
            $this->process_bulk_action();

            $this->set_pagination_args([
                'total_items' => intval($this->numsql),
                'per_page'    => $this->par_page,
             ]);
            $this->_column_headers = [
                $this->get_columns(),
                [  ],
                $this->get_sortabele_colums(),
                'question',
             ];
            $this->items = $this->all_results;

        }

        public function get_hidden_columns()
        {
            return get_hidden_columns(get_current_screen());
        }

        protected function extra_tablenav($which)
        {
            if ('top' === $which) {
            ?>
<!-- <div class="alignleft actions">
    <a href="<?php echo esc_url(add_query_arg('action', 'download_csv', get_current_relative_url())); ?>"
        class="button button-primary">دانلود CSV</a>
    <a href="<?php echo esc_url(add_query_arg('action', 'download_exel', get_current_relative_url())); ?>"
        class="button button-primary">دانلود exel</a>
</div> -->
<?php
    }
        }

}