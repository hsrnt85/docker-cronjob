
var msg_tenant_not_exist = "No. Kad Pengenalan tidak wujud!";
var msg_tenant_was_leave = "No. Kad Pengenalan tidak wujud! Penghuni Telah Keluar dari Kuarters!";
var msg_ic_error = "Sila masukkan No. Kad Pengenalan (Baru) dengan 12 digit tanpa (-)cc";

function check_ic(){
    var ic_numb = $("#ic_numb").val();
    var quarters_cat_id = $("#q_category_id").val();
    let route = $('#ic_numb').attr('data-route-tenant');
    let _token = $('meta[name="csrf-token"]').attr('content');
    $('#tenant-leave-error').html('');
    $('#btn-submit').attr('disabled', 'disabled');
    if(ic_numb.length == 12){

        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        $.ajax({
            url     : route,
            type    : 'POST',
            data    : {
                        new_ic:ic_numb,
                        quarters_category_id:quarters_cat_id,
                        _token: _token
            },
            dataType: 'json',
        }).done(function(result,textStatus, jqXHR){


                if(result.tenant == null)// not tenant
                {
                    $('#tenant_name').val('');
                    $('#phone_numb').val('');
                    $('#quarters_cat').val('');

                    $('#tenant-leave-error').html('<span class="text-danger">'+ msg_tenant_not_exist +'</span>');
                    $('#tenant-leave-error').addClass('has-error');
                    $('#btn-submit').attr('disabled', 'disabled');
                }
                else  // tenant
                {
                    if(result.isTenant ) // active
                    {
                        $('#tenant_id').val(result.isTenant.id);
                        $('#tenant_name').val(result.isTenant.name);
                        $('#phone_numb').val(result.isTenant.phone_no_hp);
                        $('#quarters_cat').val(result.quarters_category);

                        $('#tenant-leave-error').html('');
                        $('#tenant-leave-error').removeClass('has-error');
                        $('#btn-submit').attr('disabled', false);
                    }
                    else if(result.tenantWasLeave ) // left
                    {
                        $('#tenant_name').val('');
                        $('#phone_numb').val('');
                        $('#quarters_cat').val('');

                        $('#tenant-leave-error').html('<span class="text-danger">'+ msg_tenant_was_leave +'</span>');
                        $('#tenant-leave-error').addClass('has-error');
                        $('#btn-submit').attr('disabled', 'disabled');
                    }
                }


            }).fail(function(jqXHR, textStatus, errorThrown){

                $('#tenant_name').val('');
                $('#phone_numb').val('');
                $('#quarters_cat').val('');

            }).always(function(){
                if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            });
        // })
    }else{
        // $('#tenant-leave-error').html('<span class="text-danger">'+ msg_ic_error +'</span>');
    }
}

$(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/[^\d\.]/g, '');
});

var validateAmount = function(e)
{
    var t = e.value;
    e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
}


//---------------------------------------DATE PICKER -----------------------------------------------
// cannot backdated
jQuery(document).ready(function ($) {
    $("input[name='penalty_date']").datepicker({
        endDate: new Date(),
    });

  });
