jQuery(document).ready(function($) {


    $('#periodpickerstart').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                maxDate:$('#periodpickerend').val()?$('#periodpickerend').val():false
            })
        },
        timepicker:false
    });
    $('#periodpickerend').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                minDate:$('#periodpickerstart').val()?$('#periodpickerstart').val():false
            })
        },
        timepicker:false
    });


    $(document).on('click', '.reset-events', function () {
        $('#eventFilter').find('select').val('');
        $('#eventFilter').find('input').val('');
        $('.event-filter').submit();
    });

    if($('body').has('#exampleSlider').length > 0){
        $('#exampleSlider').multislider({
            interval: 4000,
            slideAll: false,
            duration: 1500
        });
    }




});