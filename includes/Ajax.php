<?php 
    namespace Wedevs\Academy;

    class Ajax {

        public function __construct(){

            add_action('wp_ajax_wd_academy_enquiry', [$this, 'submit_enquiry']);
            add_action('wp_ajax_nopriv_wd_academy_enquiry', [$this, 'submit_enquiry']);
            // ajax-delete//
            add_action('wp_ajax_wd-academy-delete-contact', [$this, 'delete_contact']);

        }

        public function submit_enquiry(){

            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wd-ac-enquiry-form' ) ) {

                wp_send_json_error([
                    'message' => 'Nonce verification failed',
                ]);

            }

            wp_send_json_success([
                'message' => 'Enquiry has been sent successfully!',
            ]);

        }
        // ajax-delete//
        public function delete_contact(){
           
            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wd-ac-admin-nonce' ) ) {
                wp_send_json_error( [
                    'message' => __( 'Nonce verification failed!', 'wedevs-academy' )
                ] );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( [
                    'message' => __( 'No permission!', 'wedevs-academy' )
                ] );
            }

            $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
            wd_ac_delete_address( $id );

            wp_send_json_success();
        }

    }







?>