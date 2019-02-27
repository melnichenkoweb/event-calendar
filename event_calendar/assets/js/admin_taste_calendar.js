jQuery(document).ready(function($) {
    $('.custom_date').datetimepicker({
        format : 'Y-m-d H:i',
        minDate: 0,
        step: 30,
        defaultTime: '12:00'
    });

    $(document).on('change', '[name="_is_event_in_portfolio"]', function () {
        var isEventInPortfolio = '';
        var postId = $(this).val();
        if($(this).prop('checked') == true){
            isEventInPortfolio = 'on';
        } else {
            isEventInPortfolio = 'off';
        }
        var data = {
            'action': 'set_event_to_portfolio',
            'post_id': postId,
            'is_event_in_portfolio': isEventInPortfolio
        };
        $.post(ajaxurl, data, function (response) {
            console.log(response);
        });

    });
});