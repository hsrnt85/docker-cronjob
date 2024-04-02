$(document).ready(function () {
    var year = $('#year').val();

    var month=0;
    for(var month_i=0; month_i<12; month_i++){
        //month
        month++;
        var startDate = new Date(year, month_i, 1);
        startDate = moment(startDate).format('DD/MM/YYYY').format('L');
        
        var endDate = new Date(year, month_i + 1, 0);
        endDate = moment(endDate).format('DD/MM/YYYY').format('L');

        var date = $('#payment_notice_date_'+month);
        //date.on().datepicker;

        date.datepicker('reset').datepicker('destroy').datepicker({
            'format': 'dd/MM/yyyy',
            'startDate': startDate,
            'endDate': endDate,
            'autoHide': 1
        });
    }

});
