<?php
if (!defined('ABSPATH')) {
    exit;
}

class Event_Admin {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_menu', [$this, 'add_event_menu']);
        add_action('init', [$this, 'register_event_cpt']);
        add_action('add_meta_boxes', [$this, 'add_event_meta_boxes']);
        add_action('save_post_event', [$this, 'save_event_meta']);
    }

    public function enqueue_admin_assets() {
        wp_enqueue_script(
            'event-admin-js', 
            plugin_dir_url(__FILE__) . '../assets/js/event-admin.js', 
            ['jquery'], 
            null, 
            true
        );

        wp_enqueue_style(
            'custom-form-admin-css', 
            plugin_dir_url(__FILE__) . '../assets/css/event-admin.css'
        );

        wp_localize_script('event-admin-js', 'eventAdminData', [
            'minDate' => date('Y-m-d'),  // Today's date
        ]);
    }

    public function add_event_menu() {
        add_menu_page(
            'Event Management',
            'Event Management',
            'manage_options',
            'event_management',
            [$this, 'render_admin_event_page'],
            'dashicons-calendar-alt',
            4
        );
    }

    public function register_event_cpt() {
        $labels = [
            'name'               => 'Events',
            'singular_name'      => 'Event',
            'menu_name'          => 'Events',
            'add_new'            => 'Add New Event',
            'add_new_item'       => 'Add New Event',
            'edit_item'          => 'Edit Event',
            'new_item'           => 'New Event',
            'view_item'          => 'View Event',
            'all_items'          => 'All Events',
            'search_items'       => 'Search Events',
            'not_found'          => 'No events found.',
            'not_found_in_trash' => 'No events found in Trash.',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'show_in_menu'       => 'event_management',
            'supports'           => ['title', 'thumbnail'],
            'rewrite'            => ['slug' => 'events'],
        ];

        register_post_type('event', $args);
    }

    public function add_event_meta_boxes() {
        add_meta_box(
            'event_details',
            'Event Details',
            [$this, 'render_admin_event_page'],
            'event',
            'normal',
            'high'
        );
    }

    public function render_admin_event_page($post) {
        $event_description = get_post_meta($post->ID, 'event_description', true);
        $event_schedules   = get_post_meta($post->ID, 'event_schedules', true);
        $event_schedules   = json_decode($event_schedules, true);
        $event_schedules   = is_array($event_schedules) ? $event_schedules : [];

        wp_nonce_field('save_event_meta', 'event_meta_nonce');
        ?>
        <div class="wrap">
            <h1>Event Details</h1>

            <input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID()); ?>">

            <div class="event-date-time-section">
                <div class="form-group">
                    <label for="event_description"><strong>Event Description</strong></label>
                    <textarea name="event_description" id="event_description" rows="3" style="width: 100%;"><?php echo esc_textarea($event_description); ?></textarea>
                </div>

                <h2>Event Schedule</h2>

                <div id="event-schedule-container">
                    <?php foreach ($event_schedules as $index => $schedule): ?>
                        <div class="schedule-group bordered-box">
                            <label>Date:</label>
                            <input type="date" name="event_schedules[<?php echo $index; ?>][date]" value="<?php echo esc_attr($schedule['date']); ?>" min="<?php echo date('Y-m-d'); ?>" required>

                            <div class="time-slots">
                                <?php foreach ($schedule['slots'] as $slot_index => $slot): ?>
                                    <div class="time-slot">
                                        <input type="text" name="event_schedules[<?php echo $index; ?>][slots][]" value="<?php echo esc_attr($slot); ?>" placeholder="e.g., 10:00 AM - 12:00 PM" required>
                                        <button type="button" class="remove-slot button">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="button" id="add-schedule-btn" class="button">➕ Add New Date Schedule</button>
        </div>
        <?php
    }

    public function save_event_meta($post_id) {
        if (!isset($_POST['event_meta_nonce']) || !wp_verify_nonce($_POST['event_meta_nonce'], 'save_event_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Save Event Description
        if (isset($_POST['event_description'])) {
            update_post_meta($post_id, 'event_description', sanitize_textarea_field($_POST['event_description']));
        }

        // Save Event Schedules
        if (isset($_POST['event_schedules'])) {
            $cleaned = [];
            foreach ($_POST['event_schedules'] as $schedule) {
                $date = sanitize_text_field($schedule['date']);
                $slots_array = array_map('sanitize_text_field', (array) $schedule['slots']);

                if ($date && !empty($slots_array)) {
                    $cleaned[] = [
                        'date'  => $date,
                        'slots' => $slots_array,  // Store slots as array
                    ];
                }
            }
            echo "<pre>";
            print_r($cleaned);
            die;
            update_post_meta($post_id, 'event_schedules', json_encode($cleaned));
        }

        // Save post_author and post_nicename
        $user = wp_get_current_user();
        wp_update_post([
            'ID' => $post_id,
            'post_author' => $user->ID,
        ]);
        update_post_meta($post_id, 'post_nicename', $user->user_nicename);
    }

}
