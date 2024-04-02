
$(document).ready(function () {

    $('#date_from').datepicker('reset').datepicker('destroy').datepicker({
        'autoHide': 1
    });

    $('#date_from').change(function(){

        var startDateTo = $('#date_from').val();
        $('#date_to').datepicker('reset').datepicker('destroy').datepicker({
            'startDate': startDateTo,
            'autoHide': 1
        });
    });

    //Laporan Notis Bayaran

    $(".input-mask").inputmask();

    if($('#ic_no').val()){

        $('#district').prop('disabled', true);
        $('#quarters_category').prop('disabled', true);
        $('#notice_no').prop('disabled', true);
        $('#services_type').prop('disabled', true);
    }

    $(document).on('keyup', '#ic_no', function () {
        let is_value = ($(this).val().length > 0);

        if(is_value){ $('#month').removeAttr('required'); } else { $('#month').prop('required', true); } //keyup ic, month not required

        $('#district').prop('disabled', is_value);
        $('#quarters_category').prop('disabled', is_value);
        $('#notice_no').prop('disabled', is_value);
        $('#services_type').prop('disabled', is_value);

    });

});

