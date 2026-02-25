<?php 
    namespace Wedevs\Academy\Frontend;

    class Enquiry {
            
        public function __construct(){
            add_shortcode('wedevs_academy', [$this, 'render_shortcode']);
        }

        public function render_shortcode($attr, $content = ''){
            wp_enqueue_script('academy-enquiry-script');
            wp_enqueue_style('academy-enquiry-style');
            
            ob_start();
            include __DIR__ . '/views/enquiry.php';
            return ob_get_clean();

        }
    }




?>