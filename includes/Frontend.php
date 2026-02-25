<?php 
    namespace Wedevs\Academy;
    
    class Frontend {
      
        public function __construct(){
            add_action('init', [$this, 'submit_frontend']);
            new Frontend\Shortcode();
            new Frontend\Enquiry();
        }

        public function submit_frontend($args = []){

            if(isset($_POST['submit_frontend_address'])){
                $name    = isset($_POST['name'])? sanitize_text_field($_POST['name'] ) : '';
                $address = isset($_POST['address'])? sanitize_textarea_field($_POST['address'] ) : '';
                $phone   = isset($_POST['phone'])? sanitize_text_field($_POST['phone'] ) : '';

                $args = [
                    'name'    => $name,
                    'address' => $address,
                    'phone'   => $phone,
                ];

                $insert_id = wd_ac_insert_adress($args);

                $redirect_to = site_url('/about-us/?inserted=true');
                wp_redirect($redirect_to);
                exit; // VERY IMPORTANT
                
            }
        }
    }



?>