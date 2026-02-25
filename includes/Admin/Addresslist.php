<?php 
    namespace Wedevs\Academy\Admin;
    if(! class_exists('WP_List_Table')){
        require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
    }


    class Addresslist extends \WP_List_Table{
        public function __construct(){
            parent::__construct([
                'singular' => 'contact',
                'plural' => 'contacts',
                'ajax' => false,
            ]);
        }

        protected function column_default($item, $column_name){
            switch ($column_name) {
                case 'value':
                    # code...
                    break;
                
                default:
                    return isset($item->$column_name)? $item->$column_name : '';
            }
        }
        public function column_name($item){

            $actions = [];
            // edit-btn
            $actions['edit'] = sprintf('<a href="%s" title="%s">%s</a>', admin_url('/admin.php?page=wedevs-academy&action=edit&id=' . $item->id), $item->id, __('Edit', 'wedevs-academy'),__('Edit', 'wedevs-academy'));
            // delete-btn
            $actions['delete'] = sprintf(
                    '<a href="%s" class="submitdelete" onclick="return confirm(\'Are your sure?\');" title="%s">%s</a>',wp_nonce_url(admin_url('admin-post.php?action=wd-ac-delete-address&id=' . $item->id),'wd-ac-delete-address'),
                    $item->id,__('Delete', 'wedevs-academy'),__('Delete', 'wedevs-academy')
            );
            /**ajax delete*/
            $actions['delete'] = sprintf('<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id,__('Delete', 'wedevs-academy'));

            return sprintf(
                // title-name-bold and link//
                '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=wedevs-academy&action=view&id'. $item->id), $item->name, $this->row_actions($actions));
        }
        /**
         * Checkbox column
         */
        protected function column_cb( $item ) {
            return sprintf(
                '<input type="checkbox" name="address_id[]" value="%d" />',
                $item->id
            );

        }
        // column title//
        public function get_columns(){
            return [
                'cb'         => '<input type = "checkbox">',
                'name'       => __('Name', 'wedevs-academy'),
                'address'    => __('Address', 'wedevs-academy'),
                'phone'      => __('Phone', 'wedevs-academy'),
                'created_at' => __('Date', 'wedevs-academy'),
                
            ];
        }
        /**
         * Sortable columns
         */
        protected function get_sortable_columns() {
            return [
                'name'       => [ 'name', true ],
                'created_at' => [ 'created_at', true ],
            ];

        }
        /**
         * bulck-action
         */
        public function get_bulk_actions() {
            return [
                'delete' => __('Delete', 'wedevs-academy'),
            ];
        }

        public function process_bulk_action() {

            if ( 'delete' === $this->current_action() ) {

                if ( empty($_REQUEST['address_id']) ) {
                    return;
                }

                $ids = array_map('intval', $_REQUEST['address_id']);

                foreach ( $ids as $id ) {
                    wd_ac_delete_address($id);
                }
            }
        }

        public function prepare_items(){
            $this->process_bulk_action();
            $columns = $this->get_columns();
            $hidden = [];
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = [ $columns, $hidden, $sortable ];

            // Pagination
            // $total_items = wd_ac_address_count();
            $per_page     = 20;
            $current_page = $this->get_pagenum();
            $offset = ( $current_page - 1 ) * $per_page;
            
            $args = [
                'number' => $per_page,
                'offset' => $offset,
            ];

            if(isset($_REQUEST['orderby']) && isset($_REQUEST['order'])){
                $args['orderby'] = $_REQUEST['orderby'];
                $args['order'] = $_REQUEST['order'];
            }

            $this->items = wd_ac_get_addreesses($args);
            $this->set_pagination_args( [
                'total_items' => wd_ac_address_count(),
                'per_page'    => $per_page,
                // 'total_pages' => ceil( $total_items / $per_page )
            ] );

        }
    }










?>

