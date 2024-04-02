var error_ic = $('#ic-error');
var msg_tenant_not_exist = "No. Kad Pengenalan tidak wujud!";
var msg_error_12_digit = "Sila Masukkan No. Kad Pengenalan (Baru) dengan 12 digit tanpa (-)";

var btn_submit  = $('#btn-submit');
var tenant_id   = $('#tenant_id');
var tenant_name = $('#tenant_name');
var phone_numb  = $('#phone_numb');
var market_rental  = $('#mv');

$(function () {

    $(".input-mask").inputmask();
    // error_ic.addClass('has-error').html('<span class="text-danger">' + '' + '</span>');
    // $(document).on('change', 'select[name="tid"]', function () {
    //     const selectedOption = $(this).find('option:selected'); // Get the selected option element
    //     $('#tenant_name').val(selectedOption.data('name'));
    //     $('#phone_numb').val(selectedOption.data('tel_no'));
    //     $('#mv').val(selectedOption.data('market-rental'));
    // });
    btn_submit.attr('disabled', 'disabled');

    $(document).on('keyup', '#tid', function () {
        let ic_numb = $(this).val();
        let quarters_cat = $('#q_category_id').val();
        let route = $('#tid').attr('data-route-tenant');
        let _token = $('meta[name="csrf-token"]').attr('content');

        // error_ic.addClass('has-error').html('<span class="text-danger">' + '' + '</span>');

        if (ic_numb.length > 0) {

            if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            $.ajax({
                url: route,
                method: 'GET',
                data: { new_ic: ic_numb,
                        quarters_cat: quarters_cat,
                       _token: _token},
                dataType: 'json',
                success: function (result) {
                    if(result.tenant == null){
                        tenant_id.val('');
                        tenant_name.val('');
                        phone_numb.val('');
                        market_rental.val('');
                        btn_submit.attr('disabled', 'disabled');

                        if(ic_numb.length == 12){
                            error_ic.addClass('has-error').html('<span class="text-danger">' + msg_tenant_not_exist + '</span>');
                        }else if(ic_numb.length < 12){
                            error_ic.addClass('has-error').html('<span class="text-danger">' + msg_error_12_digit + '</span>');
                        }
                    }
                    else{
                        if(result.tenant )   {
                            tenant_id.val(result.tenant.id);
                            tenant_name.val(result.tenant.name);
                            phone_numb.val(result.tenant.phone_no_hp);
                            market_rental.val(result.tenant.market_rental_amount);
                            btn_submit.attr('disabled', false);

                            error_ic.html('').removeClass('has-error');
                        }
                    }
                },
                error: function () {
                    tenant_id.val('');
                    tenant_name.val('');
                    phone_numb.val('');
                }
            }).always(function(){
                if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            });
        }else if (ic_numb.length == 0){

            error_ic.html('').removeClass('has-error');

            tenant_id.val('');
            tenant_name.val('');
            phone_numb.val('');
            market_rental.val('');

        }
    });


    $(document).on('change', '#rate', function () {
        const rate = $(this).val();
        const market_rent = $('#mv').val();
        const penalty = ((rate / 100) * market_rent).toFixed(2);

        $('input[name="penalty_amount"]').val(penalty);
    });


    $(document).on('change', 'input[name="blacklist_date"], input[name="penalty_date"]', function () {

        let url = $('#get-rate').data('route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        const getRate = async () => {
            const response = await $.ajax({
                url: url,
                type: "POST",
                data: {
                    blacklist_date: $('input[name="blacklist_date"]').val(),
                    penalty_date: $('input[name="penalty_date"]').val(),
                    _token: _token
                }
            });

            $("#rate").val(response.data.rate);
            $("#rate").trigger("change");
        }

        getRate().catch(error => {
            if (error.responseJSON.error) {
                console.log(error.responseJSON.error);
            }
        });
    });

    const otherReasonText = $('#other_reason');

    $(document).on('change', 'input[name="reason"]:radio', function () {
        // logic that needs to run on date input change
        if (this.value == "9999") {
            // Enable the textarea
            otherReasonText.prop('disabled', false);
        } else {
            // Disable the text
            otherReasonText.prop('disabled', true);
        }
    });
});


