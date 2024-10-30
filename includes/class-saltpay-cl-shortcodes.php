<?php

/**
 * Define SaltPay Cl shortcodes
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
 * SaltPay Cl Shortcodes
 *
 * SaltPay Cl Shortcodes
 *
 * @since      1.0.0
 * @package    SaltPay_CL
 * @subpackage SaltPay_CL/includes
 * @author     Tactica <=author_email=>
 */
class SaltPay_CL_Shortcodes {
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
	* SaltPay_CL_Gateway settings
	*
	* @var array
	*/
	protected  $settings;

	/**
	 * Setup class.
	 *
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$settings = get_option('woocommerce_saltpay_cl_settings');
		if(!empty($settings)) {
			$this->settings = $settings;
			$this->api = new SaltPay_CL_API();
			$this->shortcodes_init();
		}
	}

	/**
	 * Init shortcodes
	 *
	 * @since    1.0.0
	 */
	private function shortcodes_init() {
		add_shortcode('loan_advert', [ $this, 'loan_advert' ]);
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @since          1.0.0
	 * @param string   $shortcode_html Shortcode html
	 * @param array    $wrapper  Customer wrapper data.
	 * 
	 * @return string
	 */
	public static function shortcode_wrapper(
		$shortcode_html,
		$wrapper = array(
			'class'  => 'saltpay-cl-shortcode',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		echo '<div class="' . esc_attr( $wrapper['class'] ) . '">';
		echo empty( $wrapper['before'] ) ? '' : wp_kses_post($wrapper['before']);
		echo wp_kses_post($shortcode_html);
		echo empty( $wrapper['after'] ) ? '' : wp_kses_post($wrapper['after']);
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Loan advertisement shortcode

	 * @since          1.0.0
	 * @param array   $atts Attributes.

	 * @return string
	 */
	public function loan_advert( $atts ) {

		$a = shortcode_atts( array(
			'class' => '',
		), $atts );

		$html = '';
		$loan_advert = $this->get_loan_advert();
		if(!empty($loan_advert)){
			$currency_symbol = get_woocommerce_currency_symbol();
			if(isset($loan_advert['averagePayment']) && $loan_advert['numberOfPayments']){
				$html .= sprintf( __( '%s %s for %d months', 'saltpay-cl' ),
					number_format($loan_advert['averagePayment'], 0, ',', '.'),
					$currency_symbol,
					$loan_advert['numberOfPayments']
				);
			}
		}

		if(!empty($html)){
			$wrapper = array(
				'class'  => 'saltpay-cl-shortcode loan-advert-shortcode ' . esc_attr($a['class']),
				'before' => '<div class="saltpay-loan-advert"><div class="loan-advert-info">',
				'after'  =>'</div></div>'
			);
			return self::shortcode_wrapper( $html, $wrapper) ;
		}

		return;
	}

	/**
	 * Get loan advertisement from API

	 * @since       1.0.0
	 * @param array $atts Attributes
	 *
	 * @return array Loan advertisement array
	 */
	public function get_loan_advert(){
		global $product;
		if(empty($product)) return;
		$price_limit = $this->api->min_advert_price;
		$amount = $product->get_price();
		if($amount >= $price_limit)
			return $this->api->get_loan_advert($amount);
	}
}