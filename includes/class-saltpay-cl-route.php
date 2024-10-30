<?php

class SaltPay_CL_Route extends WP_REST_Controller {

  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() {
    $version = '1';
    $namespace = 'saltpay-cl/v' . $version;
    $base = 'route';
    // /wp-json/saltpay-cl/v1/get-loans/?amount=50000
    register_rest_route( $namespace, '/get-loans', array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_item' ),
        'permission_callback' => array( $this, 'get_item_permissions_check' ),
        'args'                => array(
          'context' => array(
            'default' => 'view',
          ),
          'amount'=>[
              'required' => true,
          ]
        ),
      )
    ) );
  }

  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_item( $request ) {
    //get parameters from request
    $params = $request->get_params();
    $api = new SaltPay_CL_API();
    $api_response = $api->get_loans($params['amount']);
    $data = $this->prepare_item_for_response( $api_response, $request );

    //return a response or error based on some conditional
    if ( 1 == 1 ) {
      return new WP_REST_Response( $data, 200 );
    } else {
      return new WP_Error( 'code', __( 'message', 'text-domain' ) );
    }
  }

  /**
   * Check if a given request has access to get a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_item_permissions_check( $request ) {
   // $nonce = $request->get_param( 'nonce' );
    return $this->get_items_permissions_check( $request );
  }

  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    $current_user = wp_get_current_user();
    if (in_array('administrator', $current_user->roles)) {
      return true;
    }

    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],  $this->get_domain()) !== false) {
       return true;
    }else{
      return false;
    }
  }

  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $response, $request ) {
    //parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)
    return array(
      'success'=> 1,
      'domain'=> $this->get_domain(),
      //'request'=>$_SERVER['HTTP_REFERER'],
      'loans'=>$response
    );
  }

  /**
   * Get the query params for collections
   *
   * @return array
   */
  public function get_collection_params() {
    return array(
      'page'     => array(
        'description'       => 'Current page of the collection.',
        'type'              => 'integer',
        'default'           => 1,
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'description'       => 'Maximum number of items to be returned in result set.',
        'type'              => 'integer',
        'default'           => 10,
        'sanitize_callback' => 'absint',
      ),
      'search'   => array(
        'description'       => 'Limit results to those matching a string.',
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
    );
  }

  public function get_domain() {
    $protocols = array( 'http://', 'https://', 'http://www.', 'https://www.', 'www.' );
    return str_replace( $protocols, '', site_url() );
  }
}