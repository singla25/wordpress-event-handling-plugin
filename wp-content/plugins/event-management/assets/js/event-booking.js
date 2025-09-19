jQuery(document).ready(function($){
    // open popup
    $(document).on('click', '.book-btn', function(e){
        e.preventDefault();
        var event_id = $(this).data('event-id');

        $.post(EventBookingData.ajax_url, {
            action: "event_get_dates_slots",
            nonce: EventBookingData.nonce,
            event_id: event_id
        }, function(response){
            if(response.status === 'success'){
                $("#popup-content").html(response.html);
                $("#popup-wrapper").fadeIn();
            } else {
                alert(response.html);
            }
        }, "json");
    });

    // close popup
    $(document).on("click", "#popup-close", function(){
        $("#popup-wrapper").fadeOut();
    });

    // date change → show slots
    $(document).on("change", "#event-date", function(){
        var selected = $(this).val();
        $(".slots-container").hide();
        $("#slots-" + selected).show();
    });

    // select slot
    $(document).on("click", ".slot-btn", function(){
        $(".slot-btn").removeClass("selected");
        $(this).addClass("selected");
        $("#slot_start").val($(this).data("start"));
        $("#slot_end").val($(this).data("end"));
    });

    // booking submit
    $(document).on("submit", "#event-booking-form", function(e){
        e.preventDefault();

        $.post(EventBookingData.ajax_url, $(this).serialize() + "&action=event_create_booking&nonce=" + EventBookingData.nonce, function(response){
            if(response.status === 'success'){
                alert(response.msg);
                $("#popup-wrapper").fadeOut();
            } else {
                alert(response.msg);
            }
        }, "json");
    });
});
