<?php
/**
 * Plugin Name: Event Management
 * Description: Publish your event With Us
 * Version: 1.0
 * Author: Sahil Singla
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-event-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-event-handler.php';


register_activation_hook( __FILE__, 'event_plugin_create_tables' );
function event_plugin_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_bookings = $wpdb->prefix . 'event_bookings';
    $table_attendees = $wpdb->prefix . 'event_booking_attendees';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql1 = "CREATE TABLE IF NOT EXISTS {$table_bookings} (
      id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      event_id BIGINT UNSIGNED NOT NULL,
      schedule_date DATE NOT NULL,
      slot_start VARCHAR(32) NOT NULL,
      slot_end VARCHAR(32) NOT NULL,
      seats_requested INT NOT NULL DEFAULT 1,
      status VARCHAR(20) NOT NULL DEFAULT 'pending', /* pending | confirmed | cancelled */
      user_name VARCHAR(191) NOT NULL,
      user_email VARCHAR(191) NOT NULL,
      otp VARCHAR(10) DEFAULT NULL,
      otp_expires BIGINT DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      KEY event_idx (event_id),
      KEY schedule_idx (schedule_date)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE IF NOT EXISTS {$table_attendees} (
      id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      booking_id BIGINT UNSIGNED NOT NULL,
      attendee_name VARCHAR(191) NOT NULL,
      attendee_age INT DEFAULT NULL,
      attendee_gender VARCHAR(20) DEFAULT NULL,
      PRIMARY KEY (id),
      KEY booking_idx (booking_id)
    ) $charset_collate;";

    dbDelta($sql1);
    dbDelta($sql2);
}

// Initialize plugin
new Event_Admin();
new Event_Handler();

