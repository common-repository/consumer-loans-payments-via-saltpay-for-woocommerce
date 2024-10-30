Consumer loans payments via Teya for Woocommerce
Contributors: tacticais
Tags: payments, gateway, teya, woocommerce
Requires at least: 5.6+
Tested up to: 6.4.2
WC tested up to: 8.4.0
Requires PHP: 7.0
Stable tag: 1.0.5
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Take payments in your WooCommerce store using Teya eCommerce loan process

== Description ==

Teya's eCommerce loans allows merchants to offer consumer loans through an eCommerce website. The process allows customers to apply for a loan during the checkout process. When providing an electronic identification they are prompted to allow Teya to credit score them and if approved the customer will finalize the loan in one step.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/consumer-loans-payments-via-saltpay-for-woocommerce` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Enable and configure Consumer loans payments gateway in woocommerce payments settings 
wp-admin/admin.php?page=wc-settings&tab=checkout

== Screenshots ==

1. The settings panel for the Teya gateway
2. The settings panel for the Teya gateway
3. Checkout screen

== Changelog ==

= 1.0.5 =
* Tested with WordPress 6.4.2 and WooCommerce 8.4.0
* Added payment method integration for the checkout block

= 1.0.4 =
* Tested with WordPress 6.4 and WooCommerce 8.2.1
* Changed plugin name 'Consumer loans payments via SaltPay for Woocommerce' to 'Consumer loans payments via Teya for Woocommerce'
* Fixed 'dynamic property declaration' warnings(PHP 8.2+)

= 1.0.3 =
* Tested with WordPress 6.3 and WooCommerce 7.9.0

= 1.0.2 =
* Tested with WordPress 6.2 and WooCommerce 7.6.0

= 1.0.1 =
* Updated minimum_advert_price field settings. Prevent sending requests with a minimum advert price of 0

= 1.0.0 =
* Initial release