<?php
if (!defined('ABSPATH')) {
    exit;
}

class Event_Handler {
    private $table_bookings;
    private $table_attendees;

    public function __construct() {
        global $wpdb;
        $this->table_bookings  = $wpdb->prefix . "event_bookings";
        $this->table_attendees = $wpdb->prefix . "event_attendees";

        add_action('wp_enqueue_scripts', [$this, 'enqueue_handler_assets']);
        add_shortcode('event_handler', [$this, 'event_handler_shortcode']);
        add_filter('single_template', [$this, 'load_single_event_template']);

        add_action('wp_ajax_nopriv_event_get_dates_slots', [$this, 'ajax_get_dates_slots']);
        add_action('wp_ajax_event_get_dates_slots', [$this, 'ajax_get_dates_slots']);

        add_action('wp_ajax_nopriv_event_create_booking', [$this, 'ajax_create_booking']);
        add_action('wp_ajax_event_create_booking', [$this, 'ajax_create_booking']);
    }

    public function enqueue_handler_assets() {
        wp_enqueue_script(
            'event-booking-js',
            plugin_dir_url(__FILE__) . '../assets/js/event-booking.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script('event-booking-js', 'EventBookingData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('event_booking_nonce'),
        ]);

        wp_enqueue_style(
            'event-booking-css',
            plugin_dir_url(__FILE__) . '../assets/css/event-booking.css',
            [],
            '1.0.0'
        );

        if (is_singular('event')) {
            wp_enqueue_style(
                'event-single-css',
                plugin_dir_url(__FILE__) . '../assets/css/event-single.css',
                [],
                '1.0.0'
            );
        }
    }

    public function event_handler_shortcode() {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = [
            'post_type'      => 'event',
            'posts_per_page' => 9,
            'post_status'    => 'publish',
            'paged'          => $paged,
        ];

        $events = new WP_Query($args);
        ob_start();

        echo '<div class="events-archive-container">';
        echo '<div class="events-archive-header"><h2>Events</h2></div>';

        if ($events->have_posts()) {
            echo '<div class="event-card-container">';
            while ($events->have_posts()) {
                $events->the_post();
                $event_id = get_the_ID();
                $img = get_the_post_thumbnail_url($event_id, 'medium');
                ?>
                <div class="event-card">
                    <a href="<?php echo get_permalink($event_id); ?>">
                        <div class="event-card-img">
                            <?php if ($img): ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php the_title(); ?>">
                            <?php else: ?>
                                <div class="no-img">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="event-card-content">
                            <h3><?php the_title(); ?></h3>
                        </div>
                    </a>
                </div>
                <?php
            }
            echo '</div>';
            echo '<div class="events-pagination">';
            echo paginate_links([
                'total'   => $events->max_num_pages,
                'current' => $paged,
                'prev_text' => '&laquo; Prev',
                'next_text' => 'Next &raquo;',
            ]);
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>No events found.</p>';
        }

        echo '</div>';

        // Popup HTML
        ?>
        <div id="popup-wrapper" style="display:none;">
            <div id="popup-box">
                <span id="popup-close">&times;</span>
                <div id="popup-content"></div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    public function load_single_event_template($template) {
        global $post;
        if ($post->post_type === 'event') {
            $custom_template = plugin_dir_path(__FILE__) . '../templates/single-event.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }

    public function ajax_get_dates_slots() {
        check_ajax_referer('event_booking_nonce', 'nonce');

        $event_id = intval($_POST['event_id'] ?? 0);
        if (!$event_id) {
            wp_send_json(['status' => 'error', 'html' => '<p>Invalid event ID</p>']);
        }

        $schedules = get_post_meta($event_id, 'event_schedules', true);
        if (empty($schedules)) {
            wp_send_json(['status' => 'error', 'html' => '<p>No schedules found</p>']);
        }

        ob_start();
        ?>
        <form id="event-booking-form">
            <input type="hidden" name="event_id" value="<?php echo esc_attr($event_id); ?>">

            <label for="event-date">Select Date:</label>
            <select id="event-date" name="schedule_date" required>
                <option value="">-- Choose Date --</option>
                <?php foreach ($schedules as $schedule) :
                    $date = $schedule['date'] ?? '';
                    ?>
                    <option value="<?php echo esc_attr($date); ?>"><?php echo esc_html($date); ?></option>
                <?php endforeach; ?>
            </select>

            <?php foreach ($schedules as $schedule) :
                $date  = $schedule['date'] ?? '';
                $slots = $schedule['slots'] ?? [];
                ?>
                <div id="slots-<?php echo esc_attr($date); ?>" class="slots-container" style="display:none; margin-top:15px;">
                    <strong>Available Slots:</strong>
                    <div class="slots-list">
                        <?php foreach ($slots as $slot) : ?>
                            <button type="button" class="slot-btn" 
                                data-start="<?php echo esc_attr($slot['start']); ?>" 
                                data-end="<?php echo esc_attr($slot['end']); ?>">
                                <?php echo esc_html($slot['start'].' - '.$slot['end']); ?> 
                                (<?php echo intval($slot['persons']); ?> persons)
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <input type="hidden" name="slot_start" id="slot_start">
            <input type="hidden" name="slot_end" id="slot_end">

            <label>Name:</label>
            <input type="text" name="user_name" required>

            <label>Email:</label>
            <input type="email" name="user_email" required>

            <label>Seats:</label>
            <input type="number" name="seats_requested" min="1" value="1" required>

            <button type="submit" class="confirm-booking-btn">Confirm Booking</button>
        </form>
        <?php
        $output = ob_get_clean();

        wp_send_json(['status' => 'success', 'html' => $output]);
    }

    public function ajax_create_booking() {
        check_ajax_referer('event_booking_nonce', 'nonce');
        global $wpdb;

        $event_id       = intval($_POST['event_id']);
        $schedule_date  = sanitize_text_field($_POST['schedule_date']);
        $slot_start     = sanitize_text_field($_POST['slot_start']);
        $slot_end       = sanitize_text_field($_POST['slot_end']);
        $seats_requested= intval($_POST['seats_requested']);
        $user_name      = sanitize_text_field($_POST['user_name']);
        $user_email     = sanitize_email($_POST['user_email']);

        if (!$event_id || !$schedule_date || !$slot_start || !$slot_end) {
            wp_send_json(['status' => 'error', 'msg' => 'Missing booking details']);
        }

        $wpdb->insert($this->table_bookings, [
            'event_id'       => $event_id,
            'schedule_date'  => $schedule_date,
            'slot_start'     => $slot_start,
            'slot_end'       => $slot_end,
            'seats_requested'=> $seats_requested,
            'user_name'      => $user_name,
            'user_email'     => $user_email,
            'status'         => 'pending',
        ]);

        if ($wpdb->insert_id) {
            wp_send_json(['status' => 'success', 'msg' => 'Booking created successfully!']);
        } else {
            wp_send_json(['status' => 'error', 'msg' => 'Booking failed!']);
        }
    }
}
