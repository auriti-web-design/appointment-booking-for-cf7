<?php
/**
 * Plugin Name: Appointment Booking for CF7
 * Plugin URI:  https://auritidesign.com/appointment-booking-for-cf7/
 * Description: WordPress plugin extending Contact Form 7 with appointment booking features. Includes a calendar, availability management, appointment types, Stripe integration, and on-site payment options.
 * Version:           1.0.0
 * Author:            Juan Camilo Auriti
 * Author URI:  https://auritidesign.com/
 * Text Domain:       appointment-booking-for-cf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define the plugin path constant.
define('ABCF7_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include the necessary files.
require_once ABCF7_PLUGIN_PATH . 'includes/class-abcf7-admin.php';
require_once ABCF7_PLUGIN_PATH . 'includes/class-abcf7-form.php';
require_once ABCF7_PLUGIN_PATH . 'includes/class-abcf7-database.php';
require_once ABCF7_PLUGIN_PATH . 'includes/class-abcf7-stripe.php';

// Create class instances.
$abcf7_admin = new ABCF7_Admin();
$abcf7_form = new ABCF7_Form();
$abcf7_database = new ABCF7_Database();
$abcf7_stripe = new ABCF7_Stripe();

// Initialize the plugin.
function abcf7_init()
{
    // Load the translation file.
    load_plugin_textdomain('appointment-booking-for-cf7', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'abcf7_init');

?>