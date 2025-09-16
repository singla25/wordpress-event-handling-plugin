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
// require_once plugin_dir_path(__FILE__) . 'includes/class-event-handler.php';


// Initialize plugin
new Event_Admin();
// new Event_Handler();

