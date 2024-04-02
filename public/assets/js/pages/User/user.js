var msg_new_ic_exist = "No. Kad Pengenalan (Baru) telah wujud!";
var msg_ic_not_exist_hrmis = "No. Kad Pengenalan (Baru) tiada dalam rekod HRMIS!";

//Ajax check ic pendaftaran user
function check_ic(){
    var new_ic = $("#new_ic").val();
    let route = $('#new_ic').attr('data-route');
    let route_hrmis = $('#new_ic').attr('data-route-hrmis');
    let route_position_type = $('#new_ic').attr('data-route-position-type');
    let route_services_type = $('#new_ic').attr('data-route-services-type');
    let _token = $('meta[name="csrf-token"]').attr('content');

    if(new_ic.length == 12){
        $.ajax({
            url: route,
            type    : 'POST',
            data    : {new_ic:new_ic, _token: _token},
            dataType: 'json',
            success:function(data){
                if(data.new_ic == null)
                {
                    $('div.spinner-wrapper').toggleClass("spinner-border", true);

                    $.ajax({
                        url: route_hrmis,
                        type    : 'POST',
                        data    : {new_ic:new_ic, _token: _token},
                        dataType: 'json',
                    }).done(function(response, textStatus, jqXHR){

                        if(response.data)
                        {
                            let statusHRMIS     = response.data.table.response.StatusSemakanPemilikKompetensi.KodStatusHRMIS;
                            let peribadi        = response.data.table.response.MaklumatPeribadi;
                            let perkhidmatan    = response.data.table.response.MaklumatPerkhidmatan;

                            if(["02", "03", "04"].includes(statusHRMIS)){
                                if(statusHRMIS == "02"){
                                    msg_error = "Rekod wujud dalam HRMIS tetapi sandangan tidak aktif atau pegawai bukan berada di bawah Negeri Johor."
                                }else if(statusHRMIS == "03"){
                                    msg_error = "No. kad Pengenalan ini tidak wujud di dalam HRMIS.";
                                }else if(statusHRMIS == "04"){
                                    msg_error = "Pemilik No. kad Pengenalan ini telah bersara.";
                                }
                                $('#new_ic_error').html('<span class="text-danger">'+ msg_error +'</span>');
                                $('#new_ic').addClass('has-error');
                                $('#btn-submit').attr('disabled', 'disabled');
                            }
                            else
                            {
                                $.ajax({
                                    url: route_position_type,
                                    type    : 'POST',
                                    data    : {kod_status_lantikan:perkhidmatan.KodStatusLantikan, _token: _token},
                                    dataType: 'json',
                                    success:function(data){

                                        let position = data;

                                        $.ajax({
                                            url: route_services_type,
                                            type    : 'POST',
                                            data    : {kod_kumpulan_agensi:perkhidmatan.KodKumpulanAgensi, _token: _token},
                                            dataType: 'json',
                                            success:function(data){

                                                let services = data;

                                                $('#name').val(peribadi.Nama);
                                                $('#email').val(peribadi.Emel);
                                                $('#position').val(perkhidmatan.NamaJawatan);
                                                $('#position_type').val(position.position_type);
                                                $('#services_type').val(services.services_type);
                                                $('#organization').val(perkhidmatan.NamaAgensiRasmi);
                                                $('#district').val(perkhidmatan.PoskodPejabat);

                                                $('#new_ic_error').html('');
                                                $('#new_ic').removeClass('has-error');
                                                $('#btn-submit').attr('disabled', false);
                                                // $('.user_info').attr('readonly', true);

                                            }

                                        });
                                    }
                              });
                            }
                        }
                        else
                        {
                            $('#new_ic_error').html('<span class="text-danger">'+ msg_ic_not_exist_hrmis +'</span>');
                            $('#new_ic').addClass('has-error');
                            $('#btn-submit').attr('disabled', 'disabled');
                        }

                        if(response.data == false)
                        {

                        }
                        else
                        {
                            //console.log("xx");
                            // let route_users = $('#new_ic').attr('data-route-user');
                            // $.ajax({
                            //     url: route_users,
                            //     type    : 'POST',
                            //     data    : {new_ic:new_ic, _token: _token},
                            //     dataType: 'json',
                            //     success:function(data){

                            //         $("#name").val(data.name);
                            //         $("#email").val(data.email);
                            //         // $("#position_id").val(data.position_name);
                            //         // $("#position_type_id").val(data.grade_type);
                            //         // $("#position_grade_code_id").val(data.grade_type);
                            //         // $("#position_grade_id").val(data.grade_no);
                            //         // $("#organization_id").val(data.organization_name);
                            //         // $("#department_id").val(data.organization_name);
                            //         // $("#services_type_id").val(data.services_type);
                            //         // $("#district_id").val(data.district_name);
                            //     }
                            // })
                        }
                    }).always(function(){
                        $('div.spinner-wrapper').toggleClass("spinner-border", false);
                    });
                }
                else
                {
                    $('#new_ic_error').html('<span class="text-danger">'+ msg_new_ic_exist +'</span>');
                    $('#new_ic').addClass('has-error');
                    $('#btn-submit').attr('disabled', 'disabled');
                    $('#name').val('');
                    $('#email').val('');
                    $('#position').val('');
                    $('#position_type').val('');
                    $('#services_type').val('');
                    $('#organization').val('');
                    $('#district').val('');
                }
            }
        })
    }
}

var msg_new_ic_not_exist = "No. Kad Pengenalan tidak wujud! Sila pastikan anda telah berdaftar dengan sistem ini.";

//Ajax check ic - login
function check_ic_not_exist(){
    var new_ic = $("#new_ic").val();
    let route = $('#new_ic').attr('data-route');
    let _token = $('meta[name="csrf-token"]').attr('content');

    if(new_ic.length == 12){
        $.ajax({
            url: route,
            type    : 'POST',
            data    : {new_ic:new_ic,_token: _token},
            dataType: 'json',
            success:function(data){
                if(data.new_ic == null)
                {
                    $('#new_ic_error').html('<span class="text-danger">'+ msg_new_ic_not_exist +'</span>');
                    $('#new_ic').addClass('has-error');
                    $('#btn-submit').attr('disabled', 'disabled');

                }
                else
                {
                    $('#new_ic_error').html('');
                    $('#new_ic').removeClass('has-error');
                    $('#btn-submit').attr('disabled', false);
                }
            }
        })
    }
}

//Ajax check ic pendaftaran admin
function check_ic_admin(){
    var new_ic = $("#new_ic").val();
    let route = $('#new_ic').attr('data-route');
    let _token = $('meta[name="csrf-token"]').attr('content');

    if(new_ic.length == 12){
        $.ajax({
            url: route,
            type    : 'POST',
            data    : {new_ic:new_ic,_token: _token},
            dataType: 'json',
            success:function(data){
                if(data.new_ic == null)
                {
                    $('#new_ic_error').html('');
                    $('#new_ic').removeClass('has-error');
                    $('#btn-submit').attr('disabled', false);
                }
                else
                {
                    $('#new_ic_error').html('<span class="text-danger">'+ msg_new_ic_exist +'</span>');
                    $('#new_ic').addClass('has-error');
                    $('#btn-submit').attr('disabled', 'disabled');
                }
            }
        })
    }
}

jQuery(function(){$(".input-mask").inputmask()});

$(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/[^\d\.]/g, '');
});
