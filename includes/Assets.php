<?php 
    namespace Wedevs\Academy;

    /**
     * Assets handle class
     */
    class Assets {
        function __construct() {
            add_action('wp_enqueue_scripts', [$this,'enqueue_assets']);
            add_action('admin_enqueue_scripts', [$this,'enqueue_assets']);
        }

        public function get_scripts(){
            return [
                'academy-script' =>[
                    'src'     =>  WD_ACADEMY_ASSETS .'/js/frontend.js',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/js/frontend.js'),
                    'deps'    => ['jquery']
                ],
                'academy-enquiry-script' =>[
                    'src'     =>  WD_ACADEMY_ASSETS .'/js/enquiry.js',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/js/enquiry.js'),
                    'deps'    => ['jquery']
                ],
                 // ajax delete//
                'academy-admin-script' =>[
                    'src'     =>  WD_ACADEMY_ASSETS .'/js/admin.js',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/js/admin.js'),
                    'deps'    => ['jquery', 'wp-util']
                ]
            ];
        }
        public function get_styles(){
            return [
                'academy-style' =>[
                    'src' =>  WD_ACADEMY_ASSETS .'/css/frontend.css',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/css/frontend.css')
                ],
                'academy-admin-style' =>[
                    'src' =>  WD_ACADEMY_ASSETS .'/css/admin.css',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/css/admin.css')
                ],
                'academy-enquiry-style' =>[
                    'src' =>  WD_ACADEMY_ASSETS .'/css/enquiry.css',
                    'version' => false, filemtime(WD_ACADEMY_PATH .'/assets/css/enquiry.css')
                ]
            ];
        }

        public function enqueue_assets(){

            $scripts = $this->get_scripts();
            $styles = $this->get_styles();

            foreach ($scripts as $hanlde => $script){
                $deps = isset($script['deps']) ? $script['deps'] : false;
                wp_register_script($hanlde, $script['src'], $deps, $script['version'], true);
            }

            foreach ($styles as $hanlde => $style){
                $deps = isset($style['deps']) ? $style['deps'] : false;

                wp_register_style($hanlde, $style['src'], $deps, $style['version']);
            }


            wp_localize_script('academy-enquiry-script', 'weDevsAcademy',[
                'ajaxurl' => admin_url('admin-ajax.php'),
                'error'   => __('Something went wrong', 'wedevs-academy')
            ]);

            // ajax delete//
            wp_localize_script('academy-admin-script', 'weDevsAcademy',[
                'nonce'   => wp_create_nonce( 'wd-ac-admin-nonce' ),
                'confirm' => __( 'Are you sure?', 'wedevs-academy' ),
                'error'   => __( 'Something went wrong', 'wedevs-academy' ),
            ]);

        }


    }




?>