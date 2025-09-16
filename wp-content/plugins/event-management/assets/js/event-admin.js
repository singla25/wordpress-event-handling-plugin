jQuery(document).ready(function ($) {
    console.log('Event Admin JS Loaded');

    let scheduleIndex = $('#event-schedule-container .schedule-group').length;

    $('#add-schedule-btn').on('click', function () {
        const newSchedule = `
            <div class="schedule-group bordered-box">
                <div class="group-row">
                    <label>Date:</label>
                    <input type="date" name="event_schedules[${scheduleIndex}][date]" min="${eventAdminData.minDate}" required>

                    <label>Time Slot:</label>
                    <input type="text" name="event_schedules[${scheduleIndex}][slots][]" placeholder="e.g., 10:00AM-12:00PM, 6:00PM-8:00PM" required>

                    <button type="button" class="remove-schedule button">❌ Remove</button>
                </div>
            </div>
        `;

        $('#event-schedule-container').append(newSchedule);
        scheduleIndex++;
    });

    $('#event-schedule-container').on('click', '.remove-schedule', function () {
        $(this).closest('.schedule-group').remove();
    });
});
