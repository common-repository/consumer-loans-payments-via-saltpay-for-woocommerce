<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tactica.is
 * @since      1.0.0
 *
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/public
 * @author     Tactica <=author_email=>
 */
class SaltPay_CL_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * SaltPay_CL_API instance
	 *
	 * @var SaltPay_CL_API
	*/
	public $api = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$settings = get_option('woocommerce_saltpay_cl_settings');
		if(!empty($settings)){
			$this->api = new SaltPay_CL_API();
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/saltpay-cl-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/saltpay-cl-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Checkout loan payment validate
	 *
	 * @since    1.0.0
	 */
	public function checkout_loan_payment_validate(){
		if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'saltpay_cl'){
			if(isset($_POST['max_number_of_payments'])){
				$loan_payment_id = (isset($_POST['loan_payment_id'])) ? (int)$_POST['loan_payment_id'] : null;
				if(!$loan_payment_id){
					wc_add_notice(__('Please select a <strong>Consumer loan</strong>', 'saltpay-cl'), 'error');
				}
			}else{
				wc_add_notice(__('No available loans found', 'saltpay-cl'), 'error');
			}
		}
	}

	/**
	 * Remove saltpay_cl gateway if loans for amount
	 *
	 * @since    1.0.0
	 */
	function saltpay_cl_gateway_disable_for_low_amount( $available_gateways ) {
		if(isset( $available_gateways['saltpay_cl'] )) {
			$cart = WC()->cart;
			if(!empty($cart)){
				$cart_contents_total = $cart->cart_contents_total;
				$loans = $this->api->get_loans($cart_contents_total);
				if(empty($loans) || isset($loans['errorId'])){
					unset( $available_gateways['saltpay_cl'] );
				}
			}
		}
		return $available_gateways;
	}

	/**
	 * Checkout loan payment validate
	 *
	 * @since    1.0.0
	 */
	public function add_loan_advert(){
		echo do_shortcode("[loan_advert]");
	}
}
