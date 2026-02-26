<?php 
    namespace Wedevs\Academy\API;

    use WP_REST_Controller;
    use WP_REST_Server;
    use WP_Error;
    // use WP_REST_Response;

    class Addressbook extends WP_REST_Controller {

        public function __construct(){
            $this->namespace = 'academy/v1';
            $this->rest_base = 'contacts';
        }

        public function register_routes(){
            register_rest_route(
                $this->namespace,
                '/' . $this->rest_base,
                [
                    [
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => [ $this, 'get_items' ],
                        'permission_callback' => [ $this, 'get_items_permissions_check' ],
                        'args'                => $this->get_collection_params(),
                    ],
                    [
                        'methods'             => WP_REST_Server::CREATABLE,
                        'callback'            => [ $this, 'create_item' ],
                        'permission_callback' => [ $this, 'create_item_permissions_check' ],
                        'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                    ],
                    'schema' => [$this, 'get_item_schema']
                ]
            );
            register_rest_route(
                $this->namespace,
                '/' . $this->rest_base . '/(?P<id>[\d]+)',
                [
                    'args'   => [
                        'id' => [
                            'description' => __( 'Unique identifier for the object.' ),
                            'type'        => 'integer',
                        ],
                    ],
                    [
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => [ $this, 'get_item' ],
                        'permission_callback' => [ $this, 'get_item_permissions_check' ],
                        'args'                => [
                            'context' => $this->get_context_param( [ 'default' => 'view' ] ),
                        ],
                    ],
                    [
                        'methods'             => WP_REST_Server::EDITABLE,
                        'callback'            => [ $this, 'update_item' ],
                        'permission_callback' => [ $this, 'update_item_permissions_check' ],
                        'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                    ],
                    [
                        'methods'             => WP_REST_Server::DELETABLE,
                        'callback'            => [ $this, 'delete_item' ],
                        'permission_callback' => [ $this, 'delete_item_permissions_check' ],
                    ],
                    'schema' => [ $this, 'get_item_schema' ],
                ]
            );
        }

        public function get_items_permissions_check($request){
            if( current_user_can('manage_options')){
                return true;
            }
            return false;
        }

        public function get_items($request){
            $args = [];
            $params = $this->get_collection_params();

            foreach ($params as $key => $value) {
                if(isset($request[$key])){
                    $args[$key] = $request[$key];
                }
            }
            

            // change `per_page` to `number`
            $args['number'] = $args['per_page'];
            $args['offset'] = $args['number'] * ( $args['page'] - 1 );

            // unset others
            unset( $args['per_page'] );
            unset( $args['page'] );

            $data     = [];

            $contacts = wd_ac_get_addreesses($args);

            foreach ( $contacts as $contact ) {
                $response = $this->prepare_item_for_response( $contact, $request );
                $data[]   = $this->prepare_response_for_collection( $response );
            }

            $total     = wd_ac_address_count();
            $max_pages = ceil( $total / (int) $args['number'] );

            $response = rest_ensure_response( $data );

            $response->header( 'X-WP-Total', (int) $total );
            $response->header( 'X-WP-TotalPages', (int) $max_pages );

            return $response;
            
        }

        protected function get_contact($id){

            $contact = wd_ac_get_address($id);

            if ( ! $contact ) {
                return new WP_Error(
                    'rest_contact_invalid_id',
                    __( 'Invalid contact ID.' ),
                    [ 'status' => 404 ]
                );
            }
            return $contact;
        }


        public function get_item_permissions_check($request){
            if( ! current_user_can('manage_options')){
                return false;
            }

            $contact = $this->get_contact($request['id']);

            if(is_wp_error($contact)){
                return $contact;
            }

            return true;
        }

        public function get_item($request){

            $contact = $this->get_contact( $request['id'] );

            $response = $this->prepare_item_for_response( $contact, $request );
            $response = rest_ensure_response( $response );

            return $response;
        }

        public function create_item_permissions_check($request){
            return $this->get_items_permissions_check($request);
        }

        public function create_item($request){

            $contact = $this->prepare_item_for_database($request);

            if( is_wp_error($contact)){

                return $contact;
            }

            $contact_id = wd_ac_insert_adress($contact);

            if( is_wp_error($contact_id)){
                $contact_id->add_data( [ 'status' => 400 ] );
                return $contact_id;
            }
            $contact = $this->get_contact( $contact_id );
            $response = $this->prepare_item_for_response( $contact, $request );

            $response->set_status( 201 );
            $response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $contact_id ) ) );

            return rest_ensure_response( $response );
        }


        public function update_item_permissions_check($request){
            return $this->get_item_permissions_check($request);
        }

        public function update_item($request){
            $contact = $this->get_contact($request['id']);
            $prepared = $this->prepare_item_for_database( $request );

            $prepared = array_merge((array) $contact, $prepared);

            $updated = wd_ac_insert_adress($prepared);

            if(! $updated) {
                return new WP_Error(
                'rest_not_updated',
                __('Sorry, the address could not be updated'),
                [ 'status' => 400 ]);
            }

            $contact = $this->get_contact($request['id']);
            $response = $this->prepare_item_for_response( $contact, $request );
            return rest_ensure_response( $response );
        }


        public function delete_item_permissions_check($request){
            return $this->get_item_permissions_check($request);
        }


        public function delete_item($request){
            $contact = $this->get_contact( $request['id'] );
            $previous = $this->prepare_item_for_response( $contact, $request );

            $deleted = wd_ac_delete_address($request['id']);

            if ( ! $deleted ) {
                return new WP_Error(
                    'rest_not_deleted',
                    __( 'Sorry, the address could not be deleted.' ),
                    [ 'status' => 400 ]
                );
            }
            $data = [
                'deleted'  => true,
                'previous' => $previous->get_data(),
            ];

            $response = rest_ensure_response( $data );
            return $data;
        }

        protected function prepare_item_for_database($request){
            $prepared = [];

            if ( isset( $request['name'] ) ) {
                $prepared['name'] = $request['name'];
            }

            if ( isset( $request['address'] ) ) {
                $prepared['address'] = $request['address'];
            }

            if ( isset( $request['phone'] ) ) {
                $prepared['phone'] = $request['phone'];
            }

            return $prepared;
        }


        public function prepare_item_for_response($item, $request){
            $data   = [];
            $fields = $this->get_fields_for_response( $request );

            if ( in_array( 'id', $fields, true ) ) {
                $data['id'] = (int) $item->id;
            }

            if ( in_array( 'name', $fields, true ) ) {
                $data['name'] = $item->name;
            }

            if ( in_array( 'address', $fields, true ) ) {
                $data['address'] = $item->address;
            }

            if ( in_array( 'phone', $fields, true ) ) {
                $data['phone'] = $item->phone;
            }

            if ( in_array( 'date', $fields, true ) ) {
                $data['date'] = mysql_to_rfc3339( $item->created_at );
            }

            $context = ! empty( $request['context'] ) ? $request['context'] : 'view';
            $data    = $this->filter_response_by_context( $data, $context );

            $response = rest_ensure_response( $data );
            $response->add_links( $this->prepare_links( $item ) );

            return $response;
        }
        /**
         * Prepares links for the request.
         *
         * @param \WP_Post $post Post object.
         *
         * @return array Links for the given post.
         */
        protected function prepare_links( $item ) {
            $base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

            $links = [
                'self' => [
                    'href' => rest_url( trailingslashit( $base ) . $item->id ),
                ],
                'collection' => [
                    'href' => rest_url( $base ),
                ],
            ];

            return $links;
        }

        public function get_item_schema(){
            if ( $this->schema ) {
                return $this->add_additional_fields_schema( $this->schema );
            }

            $schema = [
                '$schema'    => 'http://json-schema.org/draft-04/schema#',
                'title'      => 'contact',
                'type'       => 'object',
                'properties' => [
                    'id' => [
                        'description' => __( 'Unique identifier for the object.' ),
                        'type'        => 'integer',
                        'context'     => [ 'view', 'edit' ],
                        'readonly'    => true,
                    ],
                    'name' => [
                        'description' => __( 'Name of the contact.' ),
                        'type'        => 'string',
                        'context'     => [ 'view', 'edit' ],
                        'required'    => true,
                        'arg_options' => [
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                    ],
                    'address' => [
                        'description' => __( 'Address of the contact.' ),
                        'type'        => 'string',
                        'context'     => [ 'view', 'edit' ],
                        'arg_options' => [
                            'sanitize_callback' => 'sanitize_textarea_field',
                        ],
                    ],
                    'phone' => [
                        'description' => __( 'Phone number of the contact.' ),
                        'type'        => 'string',
                        'required'    => true,
                        'context'     => [ 'view', 'edit' ],
                        'arg_options' => [
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                    ],
                    'date' => [
                        'description' => __( "The date the object was published, in the site's timezone." ),
                        'type'        => 'string',
                        'format'      => 'date-time',
                        'context'     => [ 'view' ],
                        'readonly'    => true,
                    ],
                ]
            ];

            $this->schema = $schema;
            return $this->add_additional_fields_schema( $this->schema );
        }

        /**
         * Retrieves the query params for collections.
         *
         * @return array
         */
        public function get_collection_params() {
            $params = parent::get_collection_params();
            unset( $params['search'] );
            return $params;
        }

        
    }

    




?>