//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------
$(function() {
    checkTabs();
});

//------------------------------------------------------------------------------------------
// CARIAN REKOD
//------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------------------
jQuery(document).ready(function ($) {

    var page = $("#page").val();
    var checking_status = $(".checking_status"); // status semakan
    var approval_status  = $(".approval_status"); // status kelulusan

    var current_status = $("#current_status").val();
    var current_officer = $("#current_officer"); // peg. login
    var approver = $("#approver"); // peg. pelulus
    var checker = $("#checker"); // peg. penyemak
    var preparer_id = $("#preparer_id"); // peg. penyedia
    var checker_id = $("#checker_id"); // peg. penyemak

    var kuiri_remarks = $("#kuiri_remarks");
    var div_kuiri_remarks = $("#section-kuiri");
    var hide_for_preparer = $("#hide-for-preparer");
    var hide_for_checker = $("#hide-for-checker");

    var proses_sedia = $("#proses_sedia").val();
    var proses_semak = $("#proses_semak").val();
    $("#msg-vot-hasil").text("");

    $("#section_alert").hide();

    if(page == 'new') {

        initMaklumatVotAkaun();
        getMaklumatBank();

        $("#btn_simpan").attr("disabled",true); // disabled until jumlah vot list > 0.00
    }else{

        getMaklumatBank();
        getMaklumatVotAkaun();
        getSenaraiKutipanHasil();

        div_kuiri_remarks.hide();

        //-------------------------------------------------------------
        // PROSES SEDIA
        //-------------------------------------------------------------
        if(proses_sedia)
         {
            if(preparer_id.val() == current_officer.val()) {
                hide_for_preparer.hide();
                checking_status.removeAttr('required');
                approver.removeAttr('required');
                approval_status.removeAttr('required');
                kuiri_remarks.removeAttr('required');

                if(current_status == 1 || current_status == 5){
                    $("#btn-hantar").show();
                    $("#btn-submit").show();
                }else{
                    $("#btn-hantar").hide();
                    $("#btn-submit").hide();
                }
            }
         }

        //-------------------------------------------------------------
        // PROSES SEMAK
        //-------------------------------------------------------------
        if(proses_semak)
        {
            //PAGE PEGAWAI PENYEMAK
            if(checker_id.val() == current_officer.val()) {
                hide_for_checker.hide();
            }
            //PAGE PEGAWAI PENYEDIA
            else if(preparer_id.val() == current_officer.val()) {
                hide_for_preparer.hide();
                approver.removeAttr('required');
                checking_status.removeAttr('required');
                $("#btn-hantar").hide();
                $("#btn-submit").hide();
            }
            approval_status.removeAttr('required');
            kuiri_remarks.removeAttr('required');
        }

        //-------------------------------------------------------------
        // PAGE BATAL (DISABLE NAV TAB SENARAI)
        //-------------------------------------------------------------
        if((current_status == 6 || current_status == 7 || current_status == 8)){
            var nav_senarai = $("#nav-senarai-kutipan-hasil")
            nav_senarai.removeAttr('data-bs-toggle');
            nav_senarai.removeAttr('href');
        }
    }

    //-----------------------------------------------------------------
    // DATE
    //-------------------------------------------------------------
    $('#date').datepicker('reset').datepicker('destroy').datepicker({
        'format': 'dd/MM/yyyy',
        // 'endDate': new Date (),
        'autoHide': 1
    });

    $('#date_from').datepicker('reset').datepicker('destroy').datepicker({
        'format': 'dd/MM/yyyy',
        // 'endDate': new Date (),
        'autoHide': 1
    });

    $('#date_from').change(function(){

        var startDateTo = $('#date_from').val();
        $('#date_to').datepicker('reset').datepicker('destroy').datepicker({
            'format': 'dd/MM/yyyy',
            'startDate': startDateTo,
            // 'endDate': new Date (),
            'autoHide': 1
        });
    });

    $('#bank_slip_date').datepicker('reset').datepicker('destroy').datepicker({
        'format': 'dd/MM/yyyy',
        // 'endDate': new Date (),
        'autoHide': 1
    });
    $('.payment_method').change(function(){

        getMaklumatVotAkaun();
        getSenaraiKutipanHasil();

    });

    customDatatableIndex();

});

function initMaklumatVotAkaun(){
    $('.listDatatableVotAkaun').DataTable({
        searching: false,
        lengthChange: false,
        paginate: false,
        info: false,
        "language": {
        "emptyTable": "Tiada rekod",
        }
    });

    $('.listDatatableKutipanHasil').DataTable({
        searching: false,
        lengthChange: false,
        paginate: false,
        info: false,
        "language": {
        "emptyTable": "Tiada rekod",
        "zeroRecords": "Tiada rekod ditemui"
        }
    });
}

//REMOVE SEARCHING BOX
function customDatatableIndex(){

    $('.indextable').DataTable({
        "language": {
            "lengthMenu": "Papar _MENU_ rekod per mukasurat",
            "search": "Carian:",
            "zeroRecords": "Tiada rekod ditemui",
            "info": "Papar mukasurat _PAGE_ dari _PAGES_",
            "infoEmpty": "Tiada rekod ditemui",
            "infoFiltered": "(Carian dari _MAX_ jumlah rekod)",
            "emptyTable": "Tiada rekod",
            "paginate": {
                "first": "Mula",
                "last": "Tamat",
                "next": ">",
                "previous": "<"
            },
        },
        "searching": false,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ],
    });
}
//----------------------------------------------------------------------------------------------------------------------
//                                                  AJAX
//----------------------------------------------------------------------------------------------------------------------

function getMaklumatBank() {

    let isRequest = false; // prevent duplicate ajax data
    let url       = '/PenyataPemungut/get-maklumat-bank'
    let _token    = $('meta[name="csrf-token"]').attr('content');

    var bank_slip          = $("#bank_slip");
    var payment_method     = $("#payment_method");
    var selected_bank_name = $('#selected-bank-name')

    const $select = $("#bank_name");

    $(document).on('change', '#payment_method', function () {
        if (!isRequest) {
            isRequest = true;

            if(!$("div.spinner-wrapper-bank").hasClass("spinner-border")) $('div.spinner-wrapper-bank').toggleClass("spinner-border");
            
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                data: {
                    pm: payment_method.val(), 
                    _token: _token
                }
            }).done(function (response, textStatus, jqXHR) {

                // Clear previous options
                $select.empty().append("<option value=''>  -- Sila Pilih --  </option>");
                bank_slip.val('');
                if(response.bank_account_transit.length > 0 && payment_method.val() != '') {
                    
                    // Append Selection Nama Bank
                    $.each(response.bank_account_transit, function (index, value) { 
                        var option = $("<option>").val(value.id).text(value.bank_name).attr("data-account-no", value.account_no);
                        $select.append(option); 
                    });
                
                    // Set the selected value (Nama Bank)
                    $select.change(function () {
                        var selectedValue = $(this).find("option:selected").val();

                        if(selectedValue!= ''){ // Nama Bank has value
                            selected_bank_name.val(selectedValue);
                            bank_slip.val($(this).find("option:selected").attr('data-account-no'));
                        }else{
                            selected_bank_name.val('');
                            bank_slip.val('');
                        }
                    });
                }else{

                    if(payment_method.val() == ''){
                        $select.empty().append("<option value=''>  -- Sila Pilih --  </option>");
                    }else{
                        $select.empty().append("<option value=''>  -- Tiada Data --  </option>");
                    }

                    bank_slip.val('');
                    selected_bank_name.val('');
                }

                isRequest = false; // Reset the flag after the request is complete

            }).fail(function(jqXHR, textStatus, errorThrown){

            }).always(function(){
                if($("div.spinner-wrapper-bank").hasClass("spinner-border")) $('div.spinner-wrapper-bank').toggleClass("spinner-border");
            });
        }
    });
}


function getMaklumatVotAkaun(){

    var date_from = $("#date_from").val();
    var date_to = $("#date_to").val();
    var payment_method = $(".payment_method").val();
    var fieldHTML = "";
    var wrapper = $('.listDatatableVotAkaun')
    var isRequest = false; // prevent duplicate ajax data

    if(date_from!="" && date_to!="" && payment_method!= "" && !isRequest){

        $(".listDatatableVotAkaun" + " tbody").empty(); // empty old data after new date_to has changed

        if(!$("div.spinner-wrapper").hasClass("spinner-wrapper")) $('div.spinner-wrapper').toggleClass("spinner-border");

         $(".listDatatableVotAkaun").each(function() {

            let _token  = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/PenyataPemungut/get-kutipan-hasil-by-vot',
                type:"GET",
                dataType:"json",
                data:{
                    df:date_from,
                    dt:date_to,
                    pm:payment_method,
                    _token: _token
                }

            }).done(function(data, textStatus, jqXHR){



                //MAKLUMAT VOT HASIL
                var jumlah_keseluruhan = 0.00; // Initialize jumlah_keseluruhan variable
                isRequest = true;

                jQuery.each(data, function(i, valVot) {

                    var bil = valVot[0];
                    var income_code = valVot[1];
                    var jumlah_amaun = valVot[2];
                    var id_vot = valVot[3];

                    fieldHTML +='<tr class="text-center">';
                    fieldHTML +='<td >'+bil+'<input type="hidden" name="id_vot[]" value="'+id_vot+'"></td>';
                    fieldHTML +='<td class="text-left">'+income_code+'</td>';
                    fieldHTML +='<td>'+jumlah_amaun+'<input type="hidden" name="jumlah_amaun[]" value="'+jumlah_amaun+'"></td>';
                    fieldHTML +='</tr>';

                    $('#jumlah_kutipan').val(jumlah_keseluruhan);

                    jumlah_keseluruhan = valVot[4];  // Update jumlah_keseluruhan inside the loop
                });

                // $('div.spinner-wrapper').remove();
                if(jumlah_keseluruhan > 0) // disabled button simpan until jumlah vot list > 0.00
                {
                    $("#btn_simpan").attr("disabled",false);

                    fieldHTML +='<tr>';
                    fieldHTML +='<td class="text-center" colspan="2"><b>'+'JUMLAH KESELURUHAN (RM)'+'</b></td>';
                    fieldHTML +='<td class="text-center"><b>'+numberFormatRM(jumlah_keseluruhan)+'</b><input type="hidden" name="jumlah_keseluruhan" value="'+jumlah_keseluruhan+'"></td>';
                    fieldHTML +='</tr>';

                    $("#msg-vot-hasil").text("");
                }
                else
                {
                    $("#btn_simpan").attr("disabled",true);

                    fieldHTML +='<tr>';
                    fieldHTML +='<td class="text-center" colspan="3"> Tiada Rekod</td>';
                    fieldHTML +='</tr>';

                    $("#msg-vot-hasil").text("*** Maklumat kutipan tidak ditemui.");

                }

                $(wrapper).append(fieldHTML);
            });
        });
    }
}

$("#nav-senarai-kutipan-hasil").on('click', function() {
    getSenaraiKutipanHasil();
});

function getSenaraiKutipanHasil(){

    var date_from = $("#date_from").val();
    var date_to = $("#date_to").val();
    var payment_method = $(".payment_method").val();
    var fieldHTML = "";
    var wrapper = $('.listDatatableKutipanHasil')
    var isRequest = false; // prevent duplicate ajax data

    if(date_from!="" && date_to!="" && payment_method !="" && !isRequest){

        if(!$("div.spinner-wrapper").hasClass("spinner-wrapper")) $('div.spinner-wrapper').toggleClass("spinner-border");

        $(".listDatatableKutipanHasil" + " tbody").empty(); // empty old data after new date_to has changed

         $(".listDatatableKutipanHasil").each(function() {

            let _token  = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "/PenyataPemungut/senarai-kutipan-penyata-pemungut",
                type:"GET",
                dataType:"json",
                data:{
                    df:date_from,
                    dt:date_to,
                    pm:payment_method,
                    _token: _token
                }

            }).done(function(data, textStatus, jqXHR){

                var jumlah_keseluruhan = 0.00;
                var total_left = 0;
                var send_alert = false;
                isRequest = true;

                jQuery.each(data, function(i, valVot) {

                    var bil = valVot[0];
                    var id = valVot[1];
                    var no_notis_bayaran = valVot[2];
                    var tarikh_bayaran = valVot[3];
                    var butiran = valVot[4];
                    var no_resit = valVot[5];
                    var amaun = valVot[6];

                    fieldHTML +='<tr class="text-center">';
                    fieldHTML +='<td >'+bil+'<input type="hidden" name="tpt_id[' + bil + ']" value="'+ id +'"></td>';
                    fieldHTML +='<td>'+no_notis_bayaran+'</td>';
                    fieldHTML +='<td>'+tarikh_bayaran+'</td>';
                    fieldHTML +='<td>'+butiran+'</td>';
                    fieldHTML +='<td>'+no_resit+'</td>';
                    fieldHTML +='<td>'+amaun+'</td>';
                    fieldHTML +='</tr>';

                    $('#jumlah_kutipan').val(jumlah_keseluruhan);

                    jumlah_keseluruhan = valVot[7];  // Update jumlah_keseluruhan inside the loop
                    total_left = valVot[8]; // total penyata pemungut yg tinggal jika melebihi 200
                    send_alert = valVot[9];
                });

                // $('div.spinner-wrapper').remove();


                if(jumlah_keseluruhan > 0.00){
                    fieldHTML +='<tr>';
                    fieldHTML +='<td class="text-center" colspan="5"><b>JUMLAH KESELURUHAN (RM)</b></td>';
                    fieldHTML +='<td class="text-center"><b>'+numberFormatRM(jumlah_keseluruhan)+'</b></td>';
                    fieldHTML +='</tr>';
                }else{
                    fieldHTML +='<tr>';
                    fieldHTML +='<td class="text-center" colspan="6">Tiada Rekod</td>';
                    fieldHTML +='</tr>';
                }

                // ALERT STATEMENT
                if(send_alert == true){
                    var sentence = 'Hanya 200 rekod notis bayaran akan diproses dalam satu penyata pemungut. Rekod notis bayaran selebihnya akan didaftarkan seacara automatik pada penyata pemungut yang baru. Jumlah rekod yang akan didaftarkan secara automatik adalah '+total_left+'.'

                    $("#section_alert").show();
                    $("#alert_data_more_than_200").text(sentence);
                }else if(send_alert == false){
                    $("#section_alert").hide();
                }

                $(wrapper).append(fieldHTML);

            });
        });
    }
}

//----------------------------------------------------------------------------------------------------------------------
//                                          PAGE > VALIDATE / HIDE , SHOW AND DISABLE INPUT
//----------------------------------------------------------------------------------------------------------------------

// RADIO BUTTON STATUS SEMAKAN
$(document).on("change", ".checking_status", function(e){

    var approver = $("#approver");
    var kuiri_remarks = $("#kuiri_remarks");
    var section_kuiri = $("#section-kuiri");

    if ($(this).val() == 5 ){  //kuiri

        approver.attr("disabled",true);
        approver.attr("required",false);
        kuiri_remarks.attr("disabled",false);
        kuiri_remarks.attr("required",true);

        approver.parsley().destroy();
        kuiri_remarks.parsley().validate();

        section_kuiri.show();
    }
    else if ($(this).val() == 3 ){ // semakan

        approver.attr("disabled",false);
        approver.attr("required",true);

        kuiri_remarks.val("");
        kuiri_remarks.attr("disabled",true);
        kuiri_remarks.attr("required",false);

        kuiri_remarks.parsley().destroy();
        approver.parsley().validate();

        section_kuiri.hide();
    }
});

// RADIO BUTTON STATUS KELULUSAN
$(document).on("change", ".approval_status", function(e){

    var kuiri_remarks = $("#kuiri_remarks");
    var section_kuiri = $("#section-kuiri");

    if ($(this).val() == 5 )  //kuiri
    {
        kuiri_remarks.attr("disabled",false);
        kuiri_remarks.attr("required",true);

        kuiri_remarks.parsley().validate();

        section_kuiri.show();
    }
    else // semakan
    {
        kuiri_remarks.val("");
        kuiri_remarks.attr("disabled",true);
        kuiri_remarks.attr("required",false);

        kuiri_remarks.parsley().destroy();

        section_kuiri.hide();
    }
});

//----------------------------------------------------------------------------------------------------------------------
//                                                  FUNCTION
//----------------------------------------------------------------------------------------------------------------------

//FORMAT NUMBER WITH COMMA
function numberFormatRM(num){
    if((num)){

    const options = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      };

      if (num < 0) {
        return '(' + (-num).toLocaleString(undefined, options) + ')';
      } else {
        return num.toLocaleString(undefined, options);
      }
    } else {
      return '0.00';
    }
}

//----------------------------------------------------------------------------------------------------------------------
//                                                  SWAL MESSAGE
//----------------------------------------------------------------------------------------------------------------------
var $selector = $('#form');

//SWAL KEMASKINI PENYATA PEMUNGUT
$(document).on("click", ".swal-kemaskini-penyata", function(e){
    let thisButton = $(this);

    e.preventDefault();

    Swal.fire({
        title: "Anda pasti untuk kemaskini?",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#485ec4",
        cancelButtonColor: "#74788d",
        confirmButtonText: "Ya",
        cancelButtonText: "Tutup"
    }).then(function(result) {
        // if confirm clicked....
        if (result.value)
        {
            document.getElementById('btn_type_input').value = 'kemaskini'; //penyata pemungut - edit
            thisButton.closest('form').trigger("submit");
        }
    })
});

$(document).on("click", ".swal-simpan-penyata", function(e){

    form = $selector.parsley();
    form.validate();

    if (form.isValid()) {
        let thisButton = $(this);
        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk simpan?",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#485ec4",
            cancelButtonColor: "#74788d",
            confirmButtonText: "Simpan",
            cancelButtonText: "Kembali"
        }).then(function(result) {
            // if confirm clicked....
            if (result.value)
            {
                document.getElementById('btn_type_input').value = 'simpan'; //penyata pemungut - edit
                thisButton.closest('form').trigger("submit");
            }
        })
    }

    // e.preventDefault();
});

    //SWAL BATAL TEMUJANJI ADUAN
    $(document).on("click", ".swal-batal-penyata", function(e){

        var form = $('#form-cancel');
        let thisButton = $(this);
        let cs_id = $(this).attr('data-index');
        let page = $(this).attr('data-page');

        e.preventDefault();

        Swal.fire({
            text: "Anda pasti untuk Batal?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            confirmButtonColor: "#485ec4",
            cancelButtonColor: "#74788d",
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    text: "Sebab Batal ?",
                    icon: "warning",
                    input: "text",
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#485ec4",
                    cancelButtonColor: "#74788d",
               }).then((result)=>{
                    if (result.isConfirmed) {
                        if (result.value === false) return false;
                        if (result.value  === "") {
                            swal.fire({text:"Sila masukkan sebab penyata pemungut dibatalkan !",icon:"error"});
                            return false
                        }
                        swal.fire({
                            text: "Anda pasti untuk Batal ?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ya",
                            cancelButtonText: "Tidak",
                            confirmButtonColor: "#485ec4",
                            cancelButtonColor: "#74788d",
                        }).then(function  () {

                            var sebab_batal = result.value;
                            if (result.isConfirmed) {
                                $('#cancel_remarks_'+cs_id).val(sebab_batal);
                                if(page == "index"){
                                    thisButton.closest("td").find("form").trigger("submit");
                                }else{
                                    form.submit()
                                }
                            }

                        },function(dismiss) {
                            if (dismiss === 'cancel') {
                            } else {
                                throw dismiss;
                            }
                        });
                    }
                },function(dismiss) {
                    if (dismiss === 'cancel') {
                    } else {
                        throw dismiss;
                    }
                });

            }})
        });
