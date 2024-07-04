<?php
/*
Plugin Name: Advanced Conditional COD Payment System
Description: Make your cash on delivery(cod) safe with advanced payment.
Version: 1.0
Author: Nazmus Sakib
Author URI: http://nazmussakib.site/
License: GPL2
Requires at least: 5.6
Tested up to: 6.2
Requires PHP: 7.4
WC requires at least: 4.0
Text Domain: advanced-conditional-cod-payment-system
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
define( 'CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
// Default Values
$accodps_default_percentage = 20;
$accodps_default_min_amount = 5000;
$accodps_default_payment_title = 'Advance Payment';
$accodps_default_payment_desc = 'You have exceeded the minimum order total, and now, before processing, you have to pay a minimum of 20% in advance.';
$accodps_default_payment_checkbox_text = 'You must pay 20% in advance';



$adv_pay_per = get_option('wc_advance_payment_percentage', $accodps_default_percentage);
$adv_min_order_total = get_option('wc_advance_payment_min_order_total', $accodps_default_min_amount);
$ap_title = get_option('accodps_advance_payment_title', $accodps_default_payment_title);
$ap_description = get_option('accodps_advance_payment_description', $accodps_default_payment_desc);
$ap_checkbox_text = get_option('accodps_advance_payment_checkbox_text', $accodps_default_payment_checkbox_text);

// Enqueue scripts
function accodps_wc_advance_payment_enqueue_scripts() {
    global $adv_pay_per;
    global $adv_min_order_total;
    if (is_checkout()) {
        // Enqueue Style
        wp_enqueue_style('wc-advance-cod-payment-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('wc-advance-cod-payment-ajax', plugin_dir_url(__FILE__) . 'assets/js/custom.js', array('jquery'), '1.1', true);
        wp_localize_script('wc-advance-cod-payment-ajax', 'wc_advance_payment_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wc_advance_payment_nonce'),
            'advance_payment_percentage' => $adv_pay_per,
            'min_order_total' => $adv_min_order_total,
            'currency_symbol' => get_woocommerce_currency_symbol(),
        ));
    }
}
add_action('wp_enqueue_scripts', 'accodps_wc_advance_payment_enqueue_scripts');


require_once( CD_PLUGIN_PATH . 'includes/frontend/frontend.php');
require_once( CD_PLUGIN_PATH . 'includes/admin/admin.php');