<?php
/**
 * Class for the booking form.
 *
 * @package Appointment_Booking_for_CF7
 */

class ABCF7_Form
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Register custom tags.
        add_action('wpcf7_add_form_tag', array($this, 'add_form_tags'));

        // Register shortcode.
        add_shortcode('abcf7_appointment', array($this, 'appointment_shortcode'));
    }

    /**
     * Register custom form tags.
     */
    public function add_form_tags()
    {
        wpcf7_add_form_tag('appointment_calendar', array($this, 'appointment_calendar_tag'), true);
        wpcf7_add_form_tag('appointment_time', array($this, 'appointment_time_tag'), true);
        wpcf7_add_form_tag('appointment_type', array($this, 'appointment_type_tag'), true);
    }

    /**
     * Tag for the calendar.
     *
     * @param WPCF7_FormTag $tag The tag object.
     * @return string The HTML code of the tag.
     */
    public function appointment_calendar_tag($tag)
    {
        // Generate a unique ID for the calendar.
        $calendar_id = uniqid('abcf7_calendar_');

        // Calendar HTML.
        $html = '<div class="abcf7_calendar" id="' . esc_attr($calendar_id) . '">';
        $html .= '<div class="abcf7_calendar_header">';
        $html .= '<button class="abcf7_calendar_prev" aria-label="Previous month">&lt;</button>';
        $html .= '<span class="abcf7_calendar_month_year"></span>';
        $html .= '<button class="abcf7_calendar_next" aria-label="Next month">&gt;</button>';
        $html .= '</div>';
        $html .= '<table class="abcf7_calendar_table"></table>';
        $html .= '</div>';

        // JavaScript for the calendar.
        $html .= '<script>';
        $html .= 'jQuery(document).ready(function($) {';
        $html .= '$("#' . esc_attr($calendar_id) . '").abcf7_calendar();';
        $html .= '});';
        $html .= '</script>';

        // Return the HTML and JavaScript code.
        return $html;
    }

    /**
     * Tag for time selection.
     *
     * @param WPCF7_FormTag $tag The tag object.
     * @return string The HTML code of the tag.
     */
    public function appointment_time_tag($tag)
    {
        // TODO: Implement logic for time selection.
        return '<select name="appointment_time"></select>';
    }

    /**
     * Tag for appointment type selection.
     *
     * @param WPCF7_FormTag $tag The tag object.
     * @return string The HTML code of the tag.
     */
    public function appointment_type_tag($tag)
    {
        // TODO: Implement logic for appointment type selection.
        return '<select name="appointment_type"></select>';
    }

    /**
     * Shortcode for the booking form.
     *
     * @param array $atts The shortcode attributes.
     * @return string The HTML code of the booking form.
     */
    public function appointment_shortcode($atts)
    {
        // TODO: Implement logic for the shortcode.
        return 'Booking form';
    }
}