// jQuery(document).ready(function ($) {
//     console.log('Testing : Enhanced Event Admin JS Loaded');

//     // Add New Date Schedule
//     $('#add-schedule-btn').on('click', function () {
        
//         const lastCountMain = $('#event-schedule-container .schedule-group:last').length
//         ? parseInt($('#event-schedule-container .schedule-group:last').attr('main-box')) + 1
//         : 0;

//         const newSchedule = `
//             <div class="schedule-group bordered-box" main-box="${lastCountMain}">
//                 <div class="group-row">
//                     <label>Date:</label>
//                     <input type="date" name="event_schedules[${lastCountMain}][date]" min="${eventAdminData.minDate}" required>

//                     <div class="time-slots"></div>
//                     <button type="button" class="add-slot button">➕ Add Time Slot</button>
//                 </div>

//                 <button type="button" class="remove-schedule button">❌ Remove Date and Slots</button>
//             </div>
//         `;

//         $('#event-schedule-container').append(newSchedule);
//         scheduleIndex++;
//     });

//     // Remove Date Schedule
//     $('#event-schedule-container').on('click', '.remove-schedule', function () {
//         $(this).closest('.schedule-group').remove();
//     });

//     // Add Time Slot to a Date Block
//     $('#event-schedule-container').on('click', '.add-slot', function () {
//         const scheduleGroup = $(this).closest('.schedule-group');
//         const groupIndex = $('#event-schedule-container .schedule-group').index(scheduleGroup);

//         const lastcount = scheduleGroup.find('.time-slots .time-slot:last').length
//         ? parseInt(scheduleGroup.find('.time-slots .time-slot:last').attr('data-index')) + 1
//         : 0;

//         const newSlot = `
//             <div class="time-slot group-row" data-index="${lastcount}">
//                 <label>Start Time:</label>
//                 <input type="time" name="event_schedules[${groupIndex}][slots][${lastcount}][start_time]" required>

//                 <label>End Time:</label>
//                 <input type="time" name="event_schedules[${groupIndex}][slots][${lastcount}][end_time]" required>

//                 <label>No. of Persons:</label>
//                 <input type="number" min="1" name="event_schedules[${groupIndex}][slots][${lastcount}][persons]" placeholder="No. of Persons" required>

//                 <button type="button" class="remove-slot button">❌ Remove Slot</button>
//             </div>
//         `;

//         scheduleGroup.find('.time-slots').append(newSlot);
      
//     });

//     // Remove Time Slot
//     $('#event-schedule-container').on('click', '.remove-slot', function () {
//         $(this).closest('.time-slot').remove();
//     });
// });




let scheduleGroupCount = 0;  // Unique stable identifier for each schedule group

jQuery(document).ready(function ($) {
    console.log('Enhanced Event Admin JS Loaded');

    // Add New Date Schedule
    $('#add-schedule-btn').on('click', function () {
        const newSchedule = `
            <div class="schedule-group bordered-box" data-schedule-id="${scheduleGroupCount}">
                <div class="group-row">
                    <label>Date:</label>
                    <input type="date" name="event_schedules[${scheduleGroupCount}][date]" min="${eventAdminData.minDate}" required>

                    <div class="time-slots"></div>
                    <button type="button" class="add-slot button">➕ Add Time Slot</button>
                </div>

                <button type="button" class="remove-schedule button">❌ Remove Date and Slots</button>
            </div>
        `;

        $('#event-schedule-container').append(newSchedule);
        scheduleGroupCount++;
    });

    // Remove Date Schedule
    $('#event-schedule-container').on('click', '.remove-schedule', function () {
        $(this).closest('.schedule-group').remove();
    });

    // Add Time Slot to a Date Block
    $('#event-schedule-container').on('click', '.add-slot', function () {
        const scheduleGroup = $(this).closest('.schedule-group');
        const scheduleId = scheduleGroup.attr('data-schedule-id');

        const lastSlot = scheduleGroup.find('.time-slots .time-slot:last');
        const lastcount = lastSlot.length
            ? parseInt(lastSlot.attr('data-index'), 10) + 1
            : 0;

        const newSlot = `
            <div class="time-slot group-row" data-index="${lastcount}">
                <label>Start Time:</label>
                <input type="time" name="event_schedules[${scheduleId}][slots][${lastcount}][start_time]" required>

                <label>End Time:</label>
                <input type="time" name="event_schedules[${scheduleId}][slots][${lastcount}][end_time]" required>

                <label>No. of Persons:</label>
                <input type="number" min="1" name="event_schedules[${scheduleId}][slots][${lastcount}][persons]" placeholder="No. of Persons" required>

                <button type="button" class="remove-slot button">❌ Remove Slot</button>
            </div>
        `;

        scheduleGroup.find('.time-slots').append(newSlot);
    });

    // Remove Time Slot
    $('#event-schedule-container').on('click', '.remove-slot', function () {
        $(this).closest('.time-slot').remove();
    });
});

