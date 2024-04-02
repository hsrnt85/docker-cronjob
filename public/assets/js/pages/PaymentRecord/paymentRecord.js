
var no_ic = $("#carian_no_ic");
var tahun = $("#carian_tahun");
var bulan = $("#carian_bulan");
var kuarters = $("#carian_bayaran");

$(document).ready(function() {

    $(".input-mask").inputmask();

    enableAllInput();

    //ONREADY
    if(no_ic.val()){ // disable input selain no ic
        no_ic.prop('required', true);
        disableOtherInput()
    }else {
        $(document).on('ready', '#carian_tahun, #carian_bulan, #carian_bayaran', function(){
            tahun.prop('required', true);
            bulan.prop('required', true);
        });
    }

});

//ONKEYUP NO IC
$(document).on('keyup', '#carian_no_ic', function () {

    let is_value = ($(this).val().length > 0);

    tahun.prop('required', false);
    bulan.prop('required', false);

    tahun.prop('disabled', is_value);
    bulan.prop('disabled', is_value);
    kuarters.prop('disabled', is_value);

    tahun.val('');
    bulan.val('');
    kuarters.val('');
});

//ONCHANGE TAHUN ATAU BULAN
$(document).on('change', '#carian_tahun, #carian_bulan, #carian_bayaran', function(){
    no_ic.prop('required', false);
    tahun.prop('required', true);
    bulan.prop('required', true);
});

$(document).on('click', '#reset', function(){ // reset  -> enable all input
    enableAllInput()
});

function disableOtherInput() {

    tahun.prop('disabled', true);
    bulan.prop('disabled', true);
    kuarters.prop('disabled', true);

    tahun.val('');
    bulan.val('');
    kuarters.val('');
    tahun.prop('required', false);
    bulan.prop('required', false);

}

function enableAllInput() {

    tahun.prop('disabled', false);
    bulan.prop('disabled', false);
    kuarters.prop('disabled', false);
}
