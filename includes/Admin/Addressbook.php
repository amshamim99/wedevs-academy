<?php 

    namespace Wedevs\Academy\Admin;
    use Wedevs\Academy\Traits\Form_Error;

    class Addressbook{

        use Form_Error;
        
        public function plugin_page(){

            $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
            $id = isset( $_GET['id'] ) ? intval($_GET['id']) : 0;

            switch ($action) {
                case 'new':
                    $templete = __DIR__ . "/views/address-new.php";
                    break;
                case 'edit':
                    $address = wd_ac_get_address($id);
                    $templete = __DIR__ . "/views/address-edit.php";
                    break;
                case 'view':
                    $templete = __DIR__ . "/views/address-view.php";
                    break;
                
                default:
                    $templete = __DIR__ . "/views/address-list.php";
                    break;
            }

            if( file_exists($templete)){
                include $templete;
            }

        }

        public function form_handler(){
            if(! isset($_POST['submit_address'])){
                return ;
            }
            if ( ! wp_verify_nonce($_POST['_wpnonce'], 'new-address') ) {
                die('Are You Cheating?');
            }

            if(! current_user_can('manage_options')){
                die('Are You Cheating?');
            }
            $id      = isset( $_POST['id'] ) ? intval($_POST['id']) : 0;
            $name    = isset($_POST['name'])? sanitize_text_field($_POST['name'] ) : '';
            $address = isset($_POST['address'])? sanitize_textarea_field($_POST['address'] ) : '';
            $phone   = isset($_POST['phone'])? sanitize_text_field($_POST['phone'] ) : '';
            
            if(empty($name)){
                $this->errors['name'] = __('pleace provide a name', 'wedevs-academy');
            }
            if(empty($phone)){
                $this->errors['phone'] = __('pleace provide a phone', 'wedevs-academy');
            }
            if(! empty($this->errors)){
                return;
            }

            $args = [
                'name'    => $name,
                'address' => $address,
                'phone'   => $phone,
            ];

            if($id){
                $args['id'] = $id;
            }

            $insert_id = wd_ac_insert_adress($args);

            if( is_wp_error($insert_id)){
                wp_die($insert_id->get_error_message());
            }
            if($id){
                $redirect_to = admin_url('admin.php?page=wedevs-academy&action=edit&address-updated=ture&id=' . $id);
            }else{
                $redirect_to = admin_url('admin.php?page=wedevs-academy&inserted=true');
            }

            // $redirect_to = admin_url('admin.php?page=wedevs-academy&inserted=true');

            wp_redirect($redirect_to);

            
            var_dump($_POST);
            exit;
        }

        public function delete_addreess(){
            if ( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'wd-ac-delete-address') ) {
                die('Are You Cheating?');
            }

            if(! current_user_can('manage_options')){
                die('Are You Cheating?');
            }
            $id      = isset( $_REQUEST['id'] ) ? intval($_REQUEST['id']) : 0;

            if(wd_ac_delete_address($id)){
                $redirect_to = admin_url('admin.php?page=wedevs-academy&address-deleted=true');
            }else{
                $redirect_to = admin_url('admin.php?page=wedevs-academy&address-deleted=false');
            }
            wp_redirect($redirect_to);
            exit;
        }

       
    }




?>