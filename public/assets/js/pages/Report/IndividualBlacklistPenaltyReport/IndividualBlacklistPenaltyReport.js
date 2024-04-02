var error_ic = $('#ic-error');
var msg_tenant_not_exist = "No. Kad Pengenalan tidak wujud!";
var msg_error_12_digit = "Sila Masukkan 12 Digit No. Kad Pengenalan (Baru)";

var btn_pdf  = $('#muat_turun_pdf');
var btn_cari  = $('#cari');

$(document).ready(function (){

    $(document).on('keyup', '#new_ic', function () {
        let new_ic = $(this).val();
        let route = $('#new_ic').attr('data-route-tenant');
        let _token = $('meta[name="csrf-token"]').attr('content');

        // error_ic.addClass('has-error').html('<span class="text-danger">' + '' + '</span>');

        if (new_ic.length > 0) {

            if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            $.ajax({
                url: route,
                method: 'GET',
                data: { new_ic: new_ic,
                       _token: _token},
                dataType: 'json',
                success: function (result) {
                    if(result.tenant == null){

                        btn_pdf.attr('disabled', 'disabled');
                        btn_cari.attr('disabled', 'disabled');

                        if(new_ic.length == 12){
                            error_ic.addClass('has-error').html('<span class="text-danger">' + msg_tenant_not_exist + '</span>');
                        }else if(new_ic.length < 12){
                            $('#info_tenant').hide();
                            $('#info_blacklist').hide()
                            error_ic.addClass('has-error').html('<span class="text-danger">' + msg_error_12_digit + '</span>');
                        }
                    }
                    else{
                        if(result.tenant )   {
                            btn_pdf.attr('disabled', false);
                            btn_cari.attr('disabled', false);
                            error_ic.html('').removeClass('has-error');
                        }
                    }
                },
                error: function () {
                }
            }).always(function(){
                if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            });
        }else if (new_ic.length == 0){

            error_ic.html('').removeClass('has-error');

        }
    });
});
