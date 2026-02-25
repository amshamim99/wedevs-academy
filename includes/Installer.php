<?php 
    namespace Wedevs\Academy;

    class Installer{

        public function run(){
            $this->add_version();
            $this->create_tables();
        }
        public function add_version(){

            $instlled = get_option('wd_academy_installed');

            if ( ! $instlled ) {
                update_option('wd_academy_installed', time());
            }

            update_option('wd_academy_version', WD_ACADEMY_VERSION);
        }
        public function create_tables(){
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ac_addresses` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `address` VARCHAR(255) NULL , `phone` VARCHAR(11) NULL , `created_by` BIGINT(20) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) $charset_collate ";

            if(! function_exists('dbDelta')){
                require_once ABSPATH . "wp-admin/includes/upgrade.php";
            }

            dbDelta($schema);

        }
    }




?>