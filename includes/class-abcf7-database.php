<?php
/**
 * Class for database management.
 *
 * @package Appointment_Booking_for_CF7
 */

class ABCF7_Database
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Check if the tables exist on plugin load.
        add_action('plugins_loaded', array($this, 'check_tables'));
    }

    /**
     * Checks if the database tables exist and creates them if necessary.
     */
    public function check_tables()
    {
        global $wpdb;

        // Get the table names.
        $appointments_table = $wpdb->prefix . 'abcf7_appointments';
        $appointment_types_table = $wpdb->prefix . 'abcf7_appointment_types';

        // Check if the tables exist.
        if (
            $wpdb->get_var("SHOW TABLES LIKE '$appointments_table'") != $appointments_table ||
            $wpdb->get_var("SHOW TABLES LIKE '$appointment_types_table'") != $appointment_types_table
        ) {
            // Create the tables if they don't exist.
            $this->create_tables();
        }
    }

    /**
     * Creates the database tables.
     */
    public function create_tables()
    {
        global $wpdb;

        // Get the charset and collation.
        $charset_collate = $wpdb->get_charset_collate();

        // Get the table names.
        $appointments_table = $wpdb->prefix . 'abcf7_appointments';
        $appointment_types_table = $wpdb->prefix . 'abcf7_appointment_types';

        // SQL statement to create the appointments table.
        $sql_appointments = "CREATE TABLE $appointments_table (
            id INT AUTO_INCREMENT,
            appointment_date DATE NOT NULL,
            appointment_time TIME NOT NULL,
            appointment_type INT NOT NULL,
            client_name VARCHAR(255) NOT NULL,
            client_email VARCHAR(255) NOT NULL,
            client_phone VARCHAR(20),
            payment_method VARCHAR(50) NOT NULL,
            payment_status VARCHAR(50) NOT NULL,
            stripe_transaction_id VARCHAR(255),
            PRIMARY KEY (id)
        ) $charset_collate;";

        // SQL statement to create the appointment types table.
        $sql_appointment_types = "CREATE TABLE $appointment_types_table (
            id INT AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            duration INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            description TEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Include the necessary file for dbDelta().
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Create the tables.
        dbDelta($sql_appointments);
        dbDelta($sql_appointment_types);
    }

    /**
     * Inserts a new appointment into the database.
     *
     * @param array $appointment_data The appointment data.
     * @return int The ID of the inserted appointment.
     */
    public function insert_appointment($appointment_data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'abcf7_appointments';

        $wpdb->insert($table_name, $appointment_data);
        return $wpdb->insert_id;
    }

    /**
     * Gets an appointment by ID.
     *
     * @param int $appointment_id The appointment ID.
     * @return array|null The appointment data, or null if not found.
     */
    public function get_appointment($appointment_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'abcf7_appointments';

        $appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $appointment_id), ARRAY_A);
        return $appointment;
    }

    /**
     * Updates an appointment in the database.
     *
     * @param int $appointment_id The appointment ID.
     * @param array $appointment_data The appointment data.
     * @return bool True if the appointment was updated, false otherwise.
     */
    public function update_appointment($appointment_id, $appointment_data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'abcf7_appointments';

        return $wpdb->update($table_name, $appointment_data, array('id' => $appointment_id));
    }

    /**
     * Deletes an appointment from the database.
     *
     * @param int $appointment_id The appointment ID.
     * @return bool True if the appointment was deleted, false otherwise.
     */
    public function delete_appointment($appointment_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'abcf7_appointments';

        return $wpdb->delete($table_name, array('id' => $appointment_id));
    }
}