<?php 
    namespace Wedevs\Academy\Traits;

    trait Form_Error{

        public $errors = [];

        function has_error($ky){
            return isset($this->errors[$ky])? true : false ;
        }

        public function get_error( $key ) {
            if ( isset( $this->errors[ $key ] ) ) {
                return $this->errors[ $key ];
            }
            return false;
        }
    }




?>