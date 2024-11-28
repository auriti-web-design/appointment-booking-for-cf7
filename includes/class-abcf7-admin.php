<?php
/**
 * Class for the administration interface.
 *
 * @package Appointment_Booking_for_CF7
 */

class ABCF7_Admin
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Adds the admin menu.
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Appointment Booking for CF7', 'appointment-booking-for-cf7'), // Page title
            __('Appointment Booking', 'appointment-booking-for-cf7'), // Menu title
            'manage_options', // Capability
            'abcf7-settings', // Menu slug
            array($this, 'display_settings_page'), // Callback function
            'dashicons-calendar-alt', // Icon URL (optional)
            20 // Position (optional)
        );
    }

    /**
     * Record plugin settings.
     */
    public function register_settings()
    {
        register_setting('abcf7_settings_group', 'abcf7_appointment_types');
        add_filter('pre_update_option_abcf7_appointment_types', array($this, 'sanitize_appointment_types'));
    }

    /**
     * Sanitize appointment type data before saving it to the database.
     *
     * @param array $appointment_types The appointment types data.
     * @return array The data of the sanitized appointment types.
     */
    public function sanitize_appointment_types($appointment_types)
    {
        $sanitized_appointment_types = array();

        if (is_array($appointment_types)) {
            foreach ($appointment_types as $key => $appointment_type) {
                $sanitized_appointment_types[$key] = array(
                    'name' => sanitize_text_field($appointment_type['name']),
                    'duration' => intval($appointment_type['duration']),
                    'price' => floatval($appointment_type['price']),
                    'description' => sanitize_textarea_field($appointment_type['description']),
                );
            }
        }

        return $sanitized_appointment_types;
    }

    /**
     * Show the settings page.
     */
    public function display_settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('abcf7_settings_group');
                do_settings_sections('abcf7-settings');
                ?>

                <h2><?php _e('Appointment Types', 'appointment-booking-for-cf7'); ?></h2>

                <table class="form-table">
                    <tbody>
                        <?php
                        $appointment_types = get_option('abcf7_appointment_types', array());

                        // Loop to display existing appointment types.
                        if (!empty($appointment_types)) {
                            foreach ($appointment_types as $key => $appointment_type) {
                                ?>
                                <tr valign="top">
                                    <th scope="row">
                                        <label
                                            for="abcf7_appointment_types_<?php echo esc_attr($key); ?>_name"><?php _e('Name', 'appointment-booking-for-cf7'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="abcf7_appointment_types[<?php echo esc_attr($key); ?>][name]"
                                            id="abcf7_appointment_types_<?php echo esc_attr($key); ?>_name"
                                            value="<?php echo esc_attr($appointment_type['name']); ?>" class="regular-text">
                                    </td>
                                    <th scope="row">
                                        <label
                                            for="abcf7_appointment_types_<?php echo esc_attr($key); ?>_duration"><?php _e('Duration (minutes)', 'appointment-booking-for-cf7'); ?></label>
                                    </th>
                                    <td>
                                        <input type="number" name="abcf7_appointment_types[<?php echo esc_attr($key); ?>][duration]"
                                            id="abcf7_appointment_types_<?php echo esc_attr($key); ?>_duration"
                                            value="<?php echo esc_attr($appointment_type['duration']); ?>" class="small-text">
                                    </td>
                                    <th scope="row">
                                        <label
                                            for="abcf7_appointment_types_<?php echo esc_attr($key); ?>_price"><?php _e('Price', 'appointment-booking-for-cf7'); ?></label>
                                    </th>
                                    <td>
                                        <input type="number" name="abcf7_appointment_types[<?php echo esc_attr($key); ?>][price]"
                                            id="abcf7_appointment_types_<?php echo esc_attr($key); ?>_price"
                                            value="<?php echo esc_attr($appointment_type['price']); ?>" class="small-text">
                                    </td>
                                    <td>
                                        <a href="#" class="abcf7-remove-appointment-type"
                                            data-key="<?php echo esc_attr($key); ?>"><?php _e('Remove', 'appointment-booking-for-cf7'); ?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label
                                            for="abcf7_appointment_types_<?php echo esc_attr($key); ?>_description"><?php _e('Description', 'appointment-booking-for-cf7'); ?></label>
                                    </th>
                                    <td colspan="5">
                                        <textarea name="abcf7_appointment_types[<?php echo esc_attr($key); ?>][description]"
                                            id="abcf7_appointment_types_<?php echo esc_attr($key); ?>_description" rows="5"
                                            class="large-text"><?php echo esc_textarea($appointment_type['description']); ?></textarea>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <tr valign="top" class="abcf7-add-appointment-type-row">
                            <td colspan="6">
                                <a href="#"
                                    class="button abcf7-add-appointment-type"><?php _e('Add Appointment Type', 'appointment-booking-for-cf7'); ?></a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <script>
                    jQuery(document).ready(function ($) {
                        // Function to add a new appointment type.
                        $(document).on('click', '.abcf7-add-appointment-type', function (e) {
                            e.preventDefault();

                            var key = Date.now(); // Generate a unique key.

                            // Clone the template line and replace the placeholders with the key.
                            var newRow = $('.abcf7-add-appointment-type-row').prev().clone();
                            newRow.find('input, textarea').val('');
                            newRow.find('label, input, textarea').each(function () {
                                var id = $(this).attr('id');
                                var name = $(this).attr('name');
                                $(this).attr('id', id.replace(/\[\d+\]/g, '[' + key + ']'));
                                $(this).attr('name', name.replace(/\[\d+\]/g, '[' + key + ']'));
                            });
                            newRow.find('.abcf7-remove-appointment-type').attr('data-key', key);
                            newRow.insertBefore('.abcf7-add-appointment-type-row');
                        });

                        // Function to remove an appointment type.
                        $(document).on('click', '.abcf7-remove-appointment-type', function (e) {
                            e.preventDefault();
                            var key = $(this).data('key');
                            $(this).closest('tr').next().remove();
                            $(this).closest('tr').remove();
                        });
                    });
                </script>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
