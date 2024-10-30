<?php

/**
 * Define SaltPay Consumer Loan API functionality
 *
 *
 * @link       https://tactica.is
 * @since      1.0.0
 *
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * SaltPay Consumer Loan API
 *
 * ConsumerLoan API
 *
 * @since      1.0.0
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 */
class SaltPay_CL_API {
	/**
	* saltpay_cl gateway settings
	*
	* @var array
	*/
	protected  $settings;

	/**
	* SaltPay merchant id
	*
	* @var string
	*/
	protected  $merchant_id;

	/**
	* SaltPay merchant username
	*
	* @var string
	*/
	protected  $username;

	/**
	* SaltPay merchant password
	*
	* @var string
	*/
	protected  $password;

	/**
	*  SaltPay consumer loan website url
	*
	* @var string
	*/
	protected  $redirect_url = null;

	/**
	* Whether or not logging is enabled
	*
	* @var bool
	*/
	public static $log_enabled = false;

	/**
	* Logger instance
	*
	* @var WC_Logger
	*/
	public static $log = false;

	/**
	* Request timeout
	*
	* @var integer
	*/
	public $request_timeout = 20;

	/**
	* Request advert timeout
	*
	* @var integer
	*/
	public $request_advert_timeout = 10;

	/**
	* Transient expiration
	*
	* @var integer
	*/
	public $transient_expiration = 10;

	/**
	* Min advert price
	*
	* @var integer
	*/
	public $min_advert_price = 54000;

	/**
	*  API base url
	*
	* @var string
	*/
	private $api_base_url;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
	   $settings = get_option('woocommerce_saltpay_cl_settings');

		if( !empty($settings['testmode']) ) {
			if($settings['testmode'] === 'yes'){
				$this->api_base_url = 'https://test.borgun.is/clapi/v3/';
				$this->redirect_url = 'https://test.borgun.is/radgreidslur/Login/Token/';
			}else{
				$this->api_base_url = 'https://services.borgun.is/clapi/v3/';
				$this->redirect_url = 'https://radgreidslur.borgun.is/Login/Token/';
				//https://radgreidslur.saltpay.is/
			}
		}else {
			//fallback to dev
			$this->api_base_url = 'https://test.borgun.is/clapi/v3/';
			$this->redirect_url = 'https://test.borgun.is/radgreidslur/Login/Token/';
		}

		$this->merchant_id = ($settings['merchantid']) ? $settings['merchantid'] : Null;
		$this->username = ($settings['username']) ? $settings['username'] : Null;
		$this->password = ($settings['password']) ? $settings['password'] : Null;
		if(isset($settings['request_timeout']) && $settings['request_timeout'])
			$this->request_timeout = $settings['request_timeout'];

		if(isset($settings['request_advert_timeout']) && $settings['request_advert_timeout'])
			$this->request_advert_timeout = (int)$settings['request_advert_timeout'];

		if(isset($this->settings['transient_expiration']) && $this->settings['transient_expiration'])
			$this->transient_expiration = (int)$this->settings['transient_expiration'];

		$this->settings = $settings;
		$this->set_min_advert();
		self::$log_enabled = 'yes' === $settings['debug'];
	}

	/**
	* Get min_advert_price from gateway settings
	*
	* @since 1.0.1
	* @param array $settings saltpay_cl gateway settings
	*/
	public function set_min_advert(){
		$min_advert_price = $this->settings['min_advert_price'];
		//The field Amount must be between 1 and 2999000.
		if(is_int($min_advert_price)  && $min_advert_price<2999000 ){
			$this->min_advert_price = $min_advert_price;
		}elseif($min_advert_price == 0){
			$this->min_advert_price = 1;
		}
	}

	/**
	* Logging method.
	*
	* @since                 1.0
	* @param string $message Log message.
	* @param string $level   Optional. Default 'info'. Possible values:
	*                      emergency|alert|critical|error|warning|notice|info|debug.
	* @param string $source  Optional. Default 'saltpay-cl'
	*/
	public static function log( $message, $level = 'info', $source = 'saltpay-cl') {
		if ( self::$log_enabled ) {
		  if ( empty( self::$log ) ) {
			self::$log = wc_get_logger();
		  }
		  self::$log->log( $level, $message, array( 'source' => $source ) );
		}
	}

	/**
	* Get available SaltPay - Consumer loans
	*
	* @since                 1.0
	* @param integer $amount WC_Order total amount
	*
	* @return array API response or saved data
	*/
	public function get_loans( $amount ){
		$saved = get_transient('_saltpay_loans_' . $amount);
		if(!empty($saved) && isset($saved->loans)){
			self::log( sprintf( __( 'get_loans for amount %s from transient: %s', 'saltpay-cl' ), $amount, wc_print_r($saved->loans, true) ) );
			return $saved->loans;
		}else{
			self::log( sprintf( __( 'get_loans for amount %s', 'saltpay-cl' ), $amount ) );
			return $this->fetch_loans($amount);
		}
	}

	/**
	* Fetch available SaltPay - Consumer loans
	*
	* @since                 1.0
	* @param integer $amount WC_Order total amount
	*
	* @return array API response
	*/
	public function fetch_loans( $amount ){
		$body = array('amount' => $amount,
			'merchantNumber'=> $this->merchant_id
		);

		$args =  array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password )
			),
			'timeout'     => $this->request_timeout,
			'body' => $body
		);
		$response = wp_remote_get( $this->api_base_url . 'online/payment', $args );
		if ( is_wp_error($response) )
			self::log( sprintf( __( 'fetch_loans, error: %s', 'saltpay-cl' ), $response->get_error_message()), 'error' );

		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		self::log( sprintf( __( 'fetch_loans for amount %s, api_response: %s', 'saltpay-cl' ), $amount, wc_print_r($api_response, true) ) );
		if( !empty($api_response) ){
			if(!isset($api_response['message'])){
				$values = new stdClass();
				$values->amount = $amount;
				$values->loans = $api_response;
				set_transient('_saltpay_loans_' . $amount, $values, 60 * $this->transient_expiration);
			}
		}
		return $api_response;
	}

	/**
	* Generate SaltPay - Consumer loans web portal token
	*
	* @since                    1.0
	* @param array $posted_data Checkout data
	* @param WC_Order $order    Created WC_Order
	*
	* @return array API response
	*/
	public function generate_token( $posted_data, $order) {
		$customerInfo = [];
		$customerInfo['merchantNumber'] = $this->merchant_id;
		$customerInfo['loanTypeId'] = $posted_data['loan_type_id'];
		$customerInfo['amount'] = $order->get_total();
		$customerInfo['description'] =  $posted_data['description'];
		$customerInfo['numberOfPayments'] = $posted_data['number_of_payments'];
		$customerInfo['flexibleNumberOfPayments'] = true;  //Allow customer to choose number of payments lower or equal to submitted value of
		$customerInfo['successUrl'] = esc_url_raw($order->get_checkout_order_received_url());
		$customerInfo['cancelUrl'] = esc_url_raw( $order->get_cancel_order_url_raw() );
		$body = [
			'loanInformation'=>(object)$customerInfo,
			'progressValidMinutes'=>10,
			'tokenValidMinutes'=>10
		];

		if($posted_data['ssid'])
			$body['socialSecurityNumber'] = $posted_data['ssid'];
		if($posted_data['email'])
			$body['email'] = $posted_data['email'];
		if($posted_data['phone'])
			$body['phoneNumber'] = $posted_data['phone'];

		self::log( sprintf( __( 'generate_token, params: %s', 'saltpay-cl' ), wc_print_r($body, true) ) );

		$args = array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password )
			),
			'timeout' => $this->request_timeout,
			'body' => json_encode($body),
		);
		$response = wp_remote_post( $this->api_base_url . 'online/token/web', $args );
		if ( is_wp_error($response) )
			self::log( sprintf( __( 'generate_token, error: %s', 'saltpay-cl' ), $response->get_error_message()), 'error' );

		$response_code = wp_remote_retrieve_response_code($response);
		$body = wp_remote_retrieve_body( $response );
		self::log( sprintf( __( 'generate_token, api_response: %s', 'saltpay-cl' ), $body ) );
		if($response_code == 200){
			return $body;
		}else{
			return json_decode( $body, true );
		}
	}

	/**
	* Return Teya - Consumer loans web portal url
	*
	* @since               1.0
	* @param string $token Teya - Consumer loans web portal token
	*
	* @return void
	*/
	public function get_portal_url($token){
		return $this->redirect_url . $token;
	}

	/**
	* ValidateOnlineLoan - Validates that an eCommerce customer has created an online loan during checkout process.
	*
	* @since                    1.0
	* @param array $posted_data Checkout data
	* @param WC_Order $order    Created WC_Order
	*
	* @return array API response
	*/
	public function validate_loan( $token, $redirect_url) {
		$body = [
			'token'=>$token,
			'redirectUrl'=>$redirect_url,
			'merchantNumber'=>$this->merchant_id
		];
		self::log( sprintf( __( 'validate_loan, params: %s', 'saltpay-cl' ), wc_print_r($body, true) ) );
		$args = array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password )
			),
			'timeout'     => $this->request_timeout,
			'method' => 'PUT',
			'body' => json_encode($body),
		);
		$response = wp_remote_request( $this->api_base_url . 'online/validate', $args );
		if ( is_wp_error($response) )
			self::log( sprintf( __( 'validate_loan, error: %s', 'saltpay-cl' ), $response->get_error_message()), 'error' );
		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		self::log( sprintf( __( 'validate_loan, api_response: %s', 'saltpay-cl' ), wc_print_r($api_response, true) ) );
		return $api_response;
	}

	/**
	* Get Loan advertisement
	*
	* @since                 1.0.0
	* @param integer $amount Product price
	*
	* @return array API response or saved data
	*/
	public function get_loan_advert( $amount ){
		if($this->min_advert_price>$amount)	return;

		$saved = get_transient('_saltpay_loan_advert_' . $amount);
		if(!empty($saved) && isset($saved->advert)){
			$message = sprintf( __( 'get_loan_advert for amount %s from transient: %s', 'saltpay-cl' ), $amount, wc_print_r($saved->advert, true) ); 
			self::log($message, 'info', 'saltpay-cl-advert');
			return $saved->advert;
		}else{
			$message = sprintf( __( 'get_loan_advert for amount %s', 'saltpay-cl' ), $amount );
			self::log($message, 'info', 'saltpay-cl-advert');
			return $this->fetch_loan_advert($amount);
		}
	}

	/**
	* Fetch Loan advertisement
	*
	* @since               1.0.0
	* @param array $amount Product price
	*
	* @return array API response
	*/
	public function fetch_loan_advert($amount) {
		$numberOfPayments = (isset($this->settings['loan_advert_numberOfPayments'])) ? $this->settings['loan_advert_numberOfPayments']:6;
		$loanTypeId = (isset($this->settings['loan_advert_type_id'])) ? $this->settings['loan_advert_type_id']:23;
		$body = array(
			'amount'          => $amount,
			'merchantNumber'  => $this->merchant_id,
			'numberOfPayments'=> $numberOfPayments,
			'loanTypeId'      => $loanTypeId
		);

		$args =  array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password )
			),
			'timeout'     => $this->request_advert_timeout,
			'body' => $body
		);
		$response = wp_remote_get( $this->api_base_url . 'helpers/advert', $args );

		if ( is_wp_error($response) ){
			$message = sprintf( __( 'fetch_loan_advert, error: %s', 'saltpay-cl' ), $response->get_error_message());
			self::log($message, 'error', 'saltpay-cl-advert');
		}

		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		if( !empty($api_response) ){
			$message = sprintf( __( 'fetch_loan_advert, api_response: %s', 'saltpay-cl' ), $amount, wc_print_r($api_response, true) );
			self::log($message, 'info', 'saltpay-cl-advert');
			$values = new stdClass();
			$values->amount = $amount;
			$values->advert = $api_response;
			set_transient('_saltpay_loan_advert_' . $amount, $values, 60 * $this->transient_expiration);
		}
		return $api_response;
	}
}
