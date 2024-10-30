<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tactica.is
 * @since      1.0.0
 *
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 * @author     Tactica <=author_email=>
 */
class SaltPay_CL {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SaltPay_CL_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SALTPAY_CL_VERSION' ) ) {
			$this->version = SALTPAY_CL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'saltpay-cl';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_gateway();
		$this->rest_api_init();
		$this->define_payment_method();
		$this->define_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SaltPay_CL_Loader. Orchestrates the hooks of the plugin.
	 * - SaltPay_CL_i18n. Defines internationalization functionality.
	 * - SaltPay_CL_Admin. Defines all hooks for the admin area.
	 * - SaltPay_CL_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-i18n.php';

		/**
		 * The class responsible for defining Saltpay - Consumer loans API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-api.php';

		/**
		 * The class responsible for defining all shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-shortcodes.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-saltpay-cl-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-saltpay-cl-public.php';

		$this->loader = new SaltPay_CL_Loader();

	}

	/**
	 * Load Saltpay - Consumer loans gateway
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_saltpay_cl_gateway(){
		/**
		 * The class responsible for defining Saltpay - Consumer loans gateway
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-gateway.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SaltPay_CL_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SaltPay_CL_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Add Saltpay cl gateway
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_gateway(){
		add_action( 'plugins_loaded', array( $this, 'int_gateway_saltpay_cl' ) );
	}

	/**
	 * Define Saltpay cl gateway
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function int_gateway_saltpay_cl() {
		if (!class_exists( 'WC_Payment_Gateway' )) return;
		$this->load_saltpay_cl_gateway();
		add_filter( 'woocommerce_payment_gateways', array( $this, 'wc_gateway_saltpay_cl' ) );
	}

	/**
	 * Add Saltpay cl gateway
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function wc_gateway_saltpay_cl($gateways) {
		$gateways[] = 'WC_Gateway_SaltPay_CL';
		return $gateways;
	}


	private function rest_api_init(){
		// Init REST API routes.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ), 10 );
	}

	public function register_rest_routes(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-route.php';
		$route = new SaltPay_CL_Route();
		$route->register_routes();
	}

	public function my_awesome_func( WP_REST_Request $request ){
		$param = $request->get_param( 'amount' );
		return $param;
	}

	/**
	 * Add Saltpay cl payment method
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_payment_method(){
		add_action( 'woocommerce_blocks_loaded',  array( $this, 'woocommerce_blocks_support' ) );
	}

	public function woocommerce_blocks_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-saltpay-cl-registration.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new PaymentMethodSaltPayCL );
				}
			);
		}
	}

	/**
	 * Register all shortcodes
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {
		$plugin_shorcodes = new SaltPay_CL_Shortcodes( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SaltPay_CL_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SaltPay_CL_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'checkout_loan_payment_validate' );

		$settings = get_option('woocommerce_saltpay_cl_settings');
		if(!empty($settings)) {
			if(isset($settings['single_product_advert']) && $settings['single_product_advert'] == 'yes') {
				$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'add_loan_advert' );
			}
			if(isset($settings['archive_products_advert']) && $settings['archive_products_advert'] == 'yes') {
				$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_public, 'add_loan_advert' );
			}
			$this->loader->add_filter( 'woocommerce_available_payment_gateways', $plugin_public, 'saltpay_cl_gateway_disable_for_low_amount' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SaltPay_CL_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
