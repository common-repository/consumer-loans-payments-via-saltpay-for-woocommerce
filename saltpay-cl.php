<?php
/**
 * Plugin Name: Consumer loans payments via Teya for Woocommerce
 * Plugin URI: https://profiles.wordpress.org/tacticais/
 * Description: Extends WooCommerce with a <a href="https://docs.borgun.is/consumerloans/ecommerceloans/" target="_blank">Teya eCommerce loan process</a> gateway.
 * Version: 1.0.5
 * Author: Tactica
 * Author URI: https://tactica.is
 * Text Domain: saltpay-cl
 * Domain Path: /languages
 * Requires at least: 5.6
 * Requires PHP: 7.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SALTPAY_CL_VERSION', '1.0.5' );

define( 'SALTPAY_CL_URL', plugin_dir_url( __FILE__ ) );
define( 'SALTPAY_CL_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-saltpay-cl-activator.php
 */
function activate_saltpay_cl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saltpay-cl-activator.php';
	SaltPay_CL_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-saltpay-cl-deactivator.php
 */
function deactivate_saltpay_cl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saltpay-cl-deactivator.php';
	SaltPay_CL_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_saltpay_cl' );
register_deactivation_hook( __FILE__, 'deactivate_saltpay_cl' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-saltpay-cl.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_saltpay_cl() {

	$plugin = new SaltPay_CL();
	$plugin->run();

}
run_saltpay_cl();
