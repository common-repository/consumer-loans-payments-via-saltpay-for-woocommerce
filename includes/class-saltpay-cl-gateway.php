<?php

/**
 * Add WC_Gateway_SaltPay_CL WC gateway
 *
 *
 * @link       https://tactica.is
 * @since      1.0.0
 *
 * @package    SaltPay_CL
  * @extends     WC_Payment_Gateway
 * @subpackage SaltPay_CL/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * WC_Gateway_SaltPay_CL class
 *
 *
 * @since      1.0.0
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 * @author     Tactica <=author_email=>
 */
class WC_Gateway_SaltPay_CL extends WC_Payment_Gateway {

  /**
   * Whether or not logging is enabled
   *
   * @var bool
   */
  public static $log_enabled = false;

  /**
   * Gateway testmode
   *
   * @var string
   */
  private $testmode;

  /**
   * Enable payment logs
   *
   * @var string
   */
  private $debug;

  /**
   * SaltPay_CL_API instance
   *
   * @var SaltPay_CL_API
   */
  public $api = false;


  /**
   * Request timeout;
   *
   * @var integer
   */
  private $request_timeout;

  /**
   * Request advert timeout;
   *
   * @var integer
   */
  private $request_advert_timeout;

  /**
   * Transient expiration;
   *
   * @var integer
   */
  private $transient_expiration;

  /**
   * Min advert price
   *
   * @var integer
   */
  private $min_advert_price;

  /**
   * Logger instance
   *
   * @var WC_Logger
   */
  public static $log = false;
  
  public function __construct(){
		// Setup general properties.
		$this->setup_properties();

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Get settings.
		$this->enabled            = $this->get_option( 'enabled' );
		$this->description        = $this->get_option( 'description' );
		$this->title              = $this->get_option( 'title' );
		if(!$this->title) $this->title = __( 'Teya - Consumer loans', 'saltpay-cl' );
		$this->testmode           = $this->get_option( 'testmode' );

		$this->debug              = 'yes' === $this->get_option( 'debug', 'no' );
		self::$log_enabled        = $this->debug;
		if($this->enabled == 'yes')
			$this->api = new SaltPay_CL_API();

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

  }

  /**
  * Setup general properties for the gateway.
  */
  protected function setup_properties() {
		$this->id                 = 'saltpay_cl';
		$this->icon               = apply_filters( 'saltpay_cl_icon', SALTPAY_CL_URL . 'assets/sp-radgreidslur.png');
		$this->method_title       = __( 'Teya - Consumer loans', 'saltpay-cl' );
		$this->method_description = __( 'Allow the customer to choose from different types of Teya loans after which
	the customer will be redirected to the Teya consumer loan site for the loan process.', 'saltpay-cl' );
		$this->has_fields         = true;
		$this->request_timeout = 20; //Curl requests timeout
		$this->request_advert_timeout = 10; //Curl loan advert requests timeout
		$this->transient_expiration = 10; // Transient expiration, minutes
		$this->min_advert_price = 54000;
  }

  /**
   * Initialise Gateway Settings Form Fields.
   */
  public function init_form_fields() {
	$this->form_fields = array(
		'enabled'            => array(
			'title'       => __( 'Enable/Disable', 'saltpay-cl' ),
			'label'       => __( 'Enable Teya - Consumer loans', 'saltpay-cl' ),
			'type'        => 'checkbox',
			'description' => '',
			'default'     => 'no'
		),
		'title'              => array(
			'title'       => __( 'Title', 'saltpay-cl' ),
			'default'     => __( 'Teya - Consumer loans', 'saltpay-cl' ),
			'type'        => 'text'
		),
		'description'        => array(
			'title'       => __( 'Description', 'saltpay-cl' ),
			'type'        => 'textarea',
			'class'         =>"",
			'description' => __( 'This controls the description which the user sees during checkout.', 'saltpay-cl' ),
			'default'     => __( 'Pay with a consumer loan from Teya', 'saltpay-cl' ),
			'css'         => 'max-width:400px;',
		),
		'merchantid'         => array(
			'title'       => __( 'Merchant number', 'saltpay-cl' ),
			'type'        => 'text',
			'description' => __( 'Merchant number is supplied by Teya.', 'saltpay-cl' ),
			'default'     => ''
		),
		'username'         => array(
			'title'       => __( 'Username', 'saltpay-cl' ),
			'type'        => 'text',
			'description' => __( 'Username is supplied by Teya.', 'saltpay-cl' ),
			'default'     => ''
		),
		'password'         => array(
			'title'       => __( 'Password', 'saltpay-cl' ),
			'type'        => 'password',
			'description' => __( 'Password is supplied by Teya.', 'saltpay-cl' ),
			'default'     => ''
		),
		'testmode'           => array(
			'title'       => __( 'Test Mode', 'saltpay-cl' ),
			'label'       => __( 'Enable Test Mode', 'saltpay-cl' ),
			'type'        => 'checkbox',
			'description' => __( 'Place the payment gateway in development mode.', 'saltpay-cl' ),
			'default'     => 'no'
		),
		'loan_advertisement_title' => array(
			'type' 				=> 'title',
			'title'			=>	__( 'Loan advertisement settings', 'saltpay-cl' )
		),
		'min_advert_price' => array(
			'title'       => __( 'Minimum advert price', 'saltpay-cl' ),
			'label'       => __( 'Minimum price to show loan advert', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 54000,
			'description' => __( 'The field Amount must be between 1 and 2999000. Default value is 54000', 'saltpay-cl' ),
			'custom_attributes' => array('min'=>1, 'max'=>2999000)
		),
		'loan_advert_type_id' => array(
			'title'       => __( 'Loan advertisement type id', 'saltpay-cl' ),
			//'label'       => __( 'Loan advert type id', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 23,
			'description' => __( 'Default value is 23', 'saltpay-cl' )
		),
		'loan_advert_numberOfPayments' => array(
			'title'       => __( 'Loan advertisement number of payments', 'saltpay-cl' ),
			//'label'       => __( 'Loan advert type id', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 6,
			'description' => __( 'Default value is 6', 'saltpay-cl' )
		),
		'archive_products_advert' => array(
			'title'       => __( 'Enable/Disable advertisement in product list', 'saltpay-cl' ),
			'label'       => __( 'Enable advertisement at product overview', 'saltpay-cl' ),
			'type'        => 'checkbox',
			'description' => '',
			'default'     => 'yes'
		),
		'single_product_advert' => array(
			'title'       => __( 'Enable/Disable advertisement in single product overview', 'saltpay-cl' ),
			'label'       => __( 'Enable advertisement in single product overview', 'saltpay-cl' ),
			'type'        => 'checkbox',
			'description' => '',
			'default'     => 'yes'
		),
		'loan_advertisement_description' => array(
			'title'       => '',
			'type'        => 'title',
			'description' => sprintf(__('The %s shortcode is also available and can be used in the products', 'saltpay-cl' ), '<b>[loan_advert]</b>' )
		),
		'advanced_settings' => array(
			'type' 				=> 'title',
			'title'			=>	sprintf('%s <a class="show-more" href=""><span></span></a>', __( 'Advanced features', 'saltpay-cl' ) ),
			'class'				=> 'saltpay-cl-advanced-settings'
		),
		'request_timeout' => array(
			'title'       => __( 'Requests timeout', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 20,
			'custom_attributes' => ['min' => 20],
			'description' => __( 'Default value is 20', 'saltpay-cl' )
		),
		'request_advert_timeout' => array(
			'title'       => __( 'Loan advertisement requests timeout', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 10,
			'custom_attributes' => ['min' => 10],
			'description' => __( 'Default value is 10', 'saltpay-cl' )
		),
		'transient_expiration' => array(
			'title'       => __( 'Time until cache expires in minutes', 'saltpay-cl' ),
			'type'        => 'number',
			'default'     => 10,
			'custom_attributes' => ['min' => 10],
			'description' => __( 'Default value is 10 minutes', 'saltpay-cl' )
		),
		'debug' => array(
			'title'       => __( 'Debug', 'saltpay-cl' ),
			'label'       => __( 'Enable Debug Mode', 'saltpay-cl' ),
			'type'        => 'checkbox',
			'default'     => 'no',
			'desc_tip'    => true
		)
	);
  }

  /**
   * Processes and saves options.
   * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
   *
   * @return bool was anything saved?
   */
  public function process_admin_options() {
	$saved = parent::process_admin_options();

	// Maybe clear logs.
	if ( 'yes' !== $this->get_option( 'debug', 'no' ) ) {
	  if ( empty( self::$log ) ) {
		self::$log = wc_get_logger();
	  }
	  self::$log->clear( 'saltpay-cl' );
	}

	return $saved;
  }

  /**
   * Logging method.
   *
   * @param string $message Log message.
   * @param string $level Optional. Default 'info'. Possible values:
   *                      emergency|alert|critical|error|warning|notice|info|debug.
   */
  public static function log( $message, $level = 'info' ) {
	if ( self::$log_enabled ) {
	  if ( empty( self::$log ) ) {
		self::$log = wc_get_logger();
	  }
	  self::$log->log( $level, $message, array( 'source' => 'saltpay-cl' ) );
	}
  }

  public function admin_options() {
	if ( $this->is_valid_for_use() )
	  parent::admin_options();
	else
	  echo sprintf(
		'<div class="inline error"><p><strong>%s</strong>: %s</p></div>',
		__( 'Gateway Disabled', 'saltpay-cl' ),
		__( 'Current Store currency is not valid for Teya - Consumer loans gateway. Must be in ISK', 'saltpay-cl' )
	  );
  }

  /**
   * Builds payment fields area
   */
  public function payment_fields() {
		$description = $this->get_description();
		if ( $description ) echo esc_html($description );
		$this->available_loans();
  }

  /**
   * Process the payment and return the result.
   *
   * @param int $order_id Order ID.
   * @return string|array Token string or error array
   */
  public function process_payment( $order_id ) {
	$order = wc_get_order( $order_id );

	if ( $order->get_total() > 0 ) {
		$posted_data = [];
		if(isset($_POST['loan_payment_id']) && $loan_payment_id = (int)$_POST['loan_payment_id']){
			$posted_data['loan_type_id'] = $loan_payment_id;
			$posted_data['number_of_payments'] = null;

			$number_of_payments = null;
			if(isset($_POST['max_number_of_payments'])){
				if($_POST['max_number_of_payments'] && !is_array($_POST['max_number_of_payments'])){
					$number_of_payments = (int)$_POST['max_number_of_payments'];
				}elseif(isset($_POST['max_number_of_payments'][$loan_payment_id]) && $_POST['max_number_of_payments'][$loan_payment_id]) {
					$number_of_payments = (int)$_POST['max_number_of_payments'][$loan_payment_id];
				}
			}
			if($number_of_payments){
				$posted_data['number_of_payments'] = $number_of_payments;
			}
		}else{
			$posted_data['loan_type_id'] = null;
		}

		$posted_data['email'] = null;
		$email = ( isset($_POST['billing_email']) && $_POST['billing_email'] ) ? sanitize_email($_POST['billing_email']) : '';
		$email = apply_filters('saltpay_cl_checkout_email', $email);
		if(!empty($email) && preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", $email)) $posted_data['email'] = $email;

		$posted_data['phone'] = null;
		$phone = (isset($_POST['billing_phone']) && $_POST['billing_phone']) ? wc_sanitize_phone_number($_POST['billing_phone']) : '';
		$phone = apply_filters('saltpay_cl_checkout_phone', $phone);
		if( !empty($phone) && preg_match("/^[6-8][0-9]{6}$/", $phone) ) $posted_data['phone'] = $phone;

		$posted_data['ssid'] = null;
		$ssid = (isset($_POST['ssid']) && $_POST['ssid']) ? (int)$_POST['ssid'] : '';
		$ssid = apply_filters('saltpay_cl_checkout_ssid', $ssid);
		if( !empty($ssid) && preg_match("/[0-9]{10}$/", $ssid) ){
			$posted_data['ssid'] = $ssid;
		}
		$posted_data['description'] = $this->order_items_description($order);

		$token = $this->api->generate_token($posted_data, $order);

		if($token && is_string($token)){
			WC()->cart->empty_cart();
			self::log( sprintf( __( 'get_portal_url, %s' ), $this->api->get_portal_url( $token ) ) );
			$order->add_order_note( __( 'Сustomer redirected to Teya consumer loan website', 'saltpay-cl' ) );
			return array(
				'result'   => 'success',
				'redirect' => $this->api->get_portal_url( $token ),
			);
		}elseif(is_array($token)){
			$errorId = (isset($token['errorId'])) ? $token['errorId'] : '';
			$message = (isset($token['message'])) ? $token['message'] :  __( 'Token generation error', 'saltpay-cl' );
			wc_add_notice( $errorId . ' ' . $message, 'error') ;
			return array(
				'result'   => 'failure'
			);
		  }
		} else {
			$order->payment_complete();
			WC()->cart->empty_cart();

			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}
	}

	/**
	* Render list of Teya loans
	*/
	public function available_loans(){
		$cart_total = WC()->cart->total;
		$loans = $this->api->get_loans($cart_total);
		$message = (isset($loans['message']) && $loans['message']) ? sanitize_text_field($loans['message']) : null;
		$html = '';
		if(!empty($loans)){
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/loans.php';
			$html = ob_get_clean();
		}
		$allowed_html = wp_kses_allowed_html( 'post' );
		$allowed_html['input'] = array('type'=>1, 'id'=>1, 'name'=>1, 'class'=>1, 'value'=>1, 'checked'=>1);
		echo '<div class="loans">' . wp_kses($html, $allowed_html) . '</div>';
	}

	/**
	* Output for the order received page.
	*
	* @param int $order_id Order ID.
	*/
	public function thankyou_page($order_id) {
		global $wp;
		$order = wc_get_order( $order_id );
		$token = ($_GET['token']) ? sanitize_text_field( $_GET['token'] ) : '';
		if(!empty($token)){
			$current_url = home_url(add_query_arg(array($_GET), $wp->request));
			$loan = $this->api->validate_loan($token, $current_url);
			if(!empty($loan) && isset($loan['contractNumber']) && $contract_number = sanitize_text_field($loan['contractNumber'])){
				$order->add_order_note( sprintf(__( 'Contract number: %s', 'saltpay-cl' ), $contract_number ) );
				$order->update_meta_data( '_contractNumber', $contract_number );
				$order->payment_complete();
				if( $authorizationNumber = sanitize_text_field($loan['authorizationNumber']) ){
					$order->add_order_note( sprintf(__( 'Authorization number: %s', 'saltpay-cl' ), $authorizationNumber ) );
					$order->update_meta_data( '_authorizationNumber', $authorizationNumber );
				}
				if($ssn = sanitize_text_field($loan['socialSecurityNumber'])){
					$order->update_meta_data( '_ssn', $ssn );
				}
				$order->save();
			}
		}
	}

	/**
	* Check if this gateway is enabled and available in the user's country
	*
	* @return bool
	*/
	public function is_valid_for_use() {
		if ( ! in_array(get_woocommerce_currency(), array('ISK')) ) {
		  return false;
		}
		return true;
	}

	/**
	* Return order items description
	*
	* @param WC_Order $order    Created WC_Order
	* 
	* @return string
	*/
	public function order_items_description($order){
		$item_description = '';
		$order_items = $order->get_items( array('line_item', 'fee') );
		if ( sizeof($order_items) > 0 ) {
			foreach ( $order_items as $item ) {
				$item_name = strip_tags( $item->get_name() );
				if( !empty($item_description) ) $item_description .= ', ';
				$item_description .= $item_name;
			}
			//if (strlen($item_description) > 499) $item_description = mb_substr($item_description, 0, 496) . '...';
		}
		return $item_description;
	}
}