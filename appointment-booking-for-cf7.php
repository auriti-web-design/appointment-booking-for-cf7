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
// require_once ABCF7_PLUGIN_PATH . 'includes/class-abcf7-stripe.php';

// Create class instances.
$abcf7_admin = new ABCF7_Admin();
$abcf7_form = new ABCF7_Form();
$abcf7_database = new ABCF7_Database();
// $abcf7_stripe = new ABCF7_Stripe();

// Initialize the plugin.
function abcf7_init()
{
    // Load the translation file.
    load_plugin_textdomain('appointment-booking-for-cf7', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'abcf7_init');

// Hook for adding new tags to the form.
add_action('wpcf7_add_form_tag', 'abcf7_add_form_tags');
function abcf7_add_form_tags()
{
    // Implemented in class-abcf7-form.php
}

// Hook for adding new shortcodes.
add_shortcode('abcf7_appointment', 'abcf7_appointment_shortcode');
function abcf7_appointment_shortcode()
{
    // Implemented in class-abcf7-form.php
}

// Enqueue frontend JavaScript.
function abcf7_enqueue_scripts()
{
    wp_enqueue_script('abcf7-frontend', plugins_url('js/frontend.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'abcf7_enqueue_scripts');

// Define the abcf7_ajax_object for JavaScript.
function abcf7_localize_scripts()
{
    wp_localize_script('abcf7-frontend', 'abcf7_ajax_object', array(
        'security' => wp_create_nonce('abcf7_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'abcf7_localize_scripts');

/**
 * Get appointment availability for a specific month and year.
 *
 * @param int $month The month (1-12).
 * @param int $year The year.
 * @return array An array of available and unavailable days.
 */
function abcf7_get_appointment_availability($month, $year)
{
    // TODO: Implement logic to get availability from the database.
    // For now, return a sample array.
    $availability = array(
        1 => true,  // 1 January is available
        2 => false, // 2 January is not available
        // ...
    );
    return $availability;
}

// Add an action for the AJAX request.
add_action('wp_ajax_abcf7_get_appointment_availability', 'abcf7_get_appointment_availability');
add_action('wp_ajax_nopriv_abcf7_get_appointment_availability', 'abcf7_get_appointment_availability');

// Plugin activation function.
function abcf7_activate()
{
    $abcf7_database = new ABCF7_Database();
    $abcf7_database->create_tables();
}
register_activation_hook(__FILE__, 'abcf7_activate');