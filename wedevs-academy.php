<?php 

/*
 * Plugin Name:       Wedevs Academy
 * Description:       Simple plugin for wedevs academy
 * Plugin URI:        https://example.com/
 * Version:           1.0
 * Author:            Shamim Ahmed
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ){
    exit;
}

require_once __DIR__ . "/vendor/autoload.php";

/**
 * main plugin class
 */
final class Wedevs_Academy{

    /**
     * plugin version
     */
    const VERSION = 1.0;

    /**
     * constructor
     */
    protected function __construct(){
        $this->define_constants();
        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * initialize singletion instance
     * 
     * @return wedevs_academy
     */
    public static function init(){
        static $instance = false;
        if(! $instance){
            $instance = new self();
        }
        return $instance;
    }

    /**
     * constant define
     * 
     * @return void
     */

    public function define_constants(){
        define('WD_ACADEMY_VERSION', self::VERSION );
        define('WD_ACADEMY_FILE', __FILE__ );
        define('WD_ACADEMY_PATH', __DIR__ );
        define('WD_ACADEMY_URL', plugins_url('', WD_ACADEMY_FILE ) );
        define('WD_ACADEMY_ASSETS', WD_ACADEMY_URL .'/assets' );

       
    }

    public function init_plugin(){

        new Wedevs\Academy\Assets();

         if(defined('DOING_AJAX') && DOING_AJAX){
                new Wedevs\Academy\Ajax();
        }

        if(is_admin()){
            new Wedevs\Academy\Admin();
        }else{
            new Wedevs\Academy\Frontend();
        }
    }

    /**
     * Run on plugin activation.
     *
     * @return void
     */
    public function activate(){
        $installer = new Wedevs\Academy\Installer();
        $installer->run();
       
    }
}

/**
 * initialize main plugin calss
 * 
 * @return Wedevs_Academy
 */
function wedevs_academy(){
    return Wedevs_Academy::init();
}
wedevs_academy();








?>