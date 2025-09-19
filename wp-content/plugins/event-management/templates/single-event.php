<?php if (!defined('ABSPATH')) exit;

get_header();

global $post;
$event_id          = $post->ID;
$event_title       = get_the_title($event_id);
$event_description = get_post_meta($event_id, 'event_description', true);
$event_schedules   = get_post_meta($event_id, 'event_schedules', true);
$featured_img      = get_the_post_thumbnail_url($event_id, 'large');
?>

<div class="single-event-container">

    <!-- Row 1: Event Title -->
    <div class="event-row title-row">
        <h1 class="event-title"><?php echo esc_html($event_title); ?></h1>
    </div>

    <!-- Row 2: Two columns -->
    <div class="event-row content-row">
        <!-- Left Column: Image -->
        <div class="event-col image-col">
            <?php if ($featured_img): ?>
                <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr($event_title); ?>">
            <?php else: ?>
                <div class="no-img">No Image Available</div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Details -->
        <div class="event-col details-col">
            <h2><?php echo esc_html($event_title); ?></h2>

            <?php if ($event_description): ?>
                <p class="event-description"><?php echo esc_html($event_description); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Row 3: Event Schedules -->
    <?php if (!empty($event_schedules)): ?>
        <div class="event-schedules">
            <h3>Event Dates & Slots</h3>
            <?php foreach ($event_schedules as $schedule): ?>
                <div class="schedule-block">
                    <!-- Column 1: Date -->
                    <div class="date">
                        <h4><?php echo esc_html($schedule['date']); ?></h4>
                    </div>

                    <!-- Column 2: Time Slots -->
                    <div class="time-slots">
                        <?php if (!empty($schedule['slots'])): ?>
                            <?php foreach ($schedule['slots'] as $slot): ?>
                                <div class="time-slot">
                                    <div><?php echo esc_html($slot['start_time']); ?> - <?php echo esc_html($slot['end_time']); ?></div>
                                    <div><?php echo intval($slot['persons']); ?> Persons</div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Row 4: Book Button -->
    <div class="book-now">
        <a href="#" class="book-btn" data-event-id="<?php echo esc_attr($event_id); ?>">Book Now</a>
    </div>
</div>

<?php get_footer(); ?>
