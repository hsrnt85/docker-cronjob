
const notice_no  = $(".notice_no");
const kod_akaun  = $(".account-code");
const debit      = $(".debit");
const credit     = $(".credit");
const btn_simpan = $('#btn_simpan');
const btn_hantar = $('#btn-hantar');
const btn_submit = $('#btn-submit');

$(document).ready(function() {

    const checking_status = $(".checking_status");
    const approval_status = $(".approval_status");

    const current_status = $("#current_status").val();
    const login_officer  = $("#current_officer");
    const approver       = $("#approver");
    const preparer_id    = $("#preparer_id");
    const checker_id     = $("#checker_id");

    const kuiri_remarks     = $("#kuiri_remarks");
    const div_kuiri_remarks = $("#section-kuiri");
    const hide_for_preparer = $("#hide-for-preparer");
    const hide_for_checker  = $("#hide-for-checker");

    const proses_sedia = $("#proses_sedia").val();
    const proses_semak = $("#proses_semak").val();
    const proses_lulus = $("#proses_lulus").val();

    //--------------------------------------------------------------------------------
    // FILTER PAGE BUKA REKOD
    //--------------------------------------------------------------------------------
    btn_simpan.attr('disabled', true);
    div_kuiri_remarks.hide();

    if(proses_sedia && preparer_id.val() == login_officer.val()) {

        hide_for_preparer.hide();
        checking_status.removeAttr('required');
        approver.removeAttr('required');
        approval_status.removeAttr('required');
        kuiri_remarks.removeAttr('required');

        (current_status == 1 || current_status == 5) ? btn_hantar.show() && btn_submit.show() : btn_hantar.hide() && btn_submit.hide();
    }

    if(proses_semak) {
        //PAGE PEGAWAI PENYEMAK
        if(checker_id.val() == login_officer.val()) {
            hide_for_checker.hide();
        }
        //PAGE PEGAWAI PENYEDIA
        else if(preparer_id.val() == login_officer.val()) {

            hide_for_preparer.hide();
            approver.removeAttr('required');
            checking_status.removeAttr('required');

            btn_hantar.hide();
            btn_submit.hide();
        }
        approval_status.removeAttr('required');
        kuiri_remarks.removeAttr('required');
        notice_no.removeAttr('required');
    }

    if(proses_lulus){
        kuiri_remarks.removeAttr('required');
        notice_no.removeAttr('required');
    }

    //--------------------------------------------------------------------------------
    // INDEX DATATABLE -> REMOVE SEARCHING BOX
    //--------------------------------------------------------------------------------
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

    //--------------------------------------------------------------------------------
    // TABLE VOT AKAUN
    //--------------------------------------------------------------------------------

    // $('.credit, .debit').prop('disabled', true);

    // $('.credit').each(function(index){
    //     $(this).prop('disabled', true);
    // });

    // $('.debit').each(function(index){
    //     $(this).prop('disabled', true);
    // });

    $(document).on("change", ".notice_no", function(e){ // No. Notis Bayaran

        var tenant_id    =  $('#tenant_id');
        var tenant_name  =  $('#tenant_name');
        var payment_notice_amount =  $('#payment_notice_amount');
        var final_amount =  $('#final_amount');
        var payment_category_id = $('#payment_category_id');
        //clear input when onchange
        tenant_id.val('');
        tenant_name.val('');
        payment_notice_amount.val('');
        final_amount.val('');
        payment_category_id.val('');
        kod_akaun.val('0');
        debit.val('');
        credit.val('');

        if ($(this).val()){

            //AJAX DISPLAY NAME & AMOUNT ------------------------------------------------
            var tpn_id = $("#notice_no").val();
            let route =  $('#notice_no').attr('data-route-tenant');
            let _token = $('meta[name="csrf-token"]').attr('content');

            if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

            $.ajax({
                url     : route,
                type    : 'GET',
                data    : {  id:tpn_id,  _token: _token },
                dataType: 'json',

            }).done(function(result,textStatus, jqXHR){
                var rs = result.tenant_payment_notice;
                if(rs) {
                    tenant_id.val(rs.tenants_id);
                    tenant_name.val(rs.tenants_name);
                    payment_notice_amount.val(rs.total_amount);
                    payment_category_id.val(rs.payment_category_id);

                    if(payment_category_id.val()>0){
                        getMaklumatVotAkaun(payment_category_id.val());
                    }
                }

            }).fail(function(jqXHR, textStatus, errorThrown){

                tenant_id.val('');
                tenant_name.val('');
                payment_notice_amount.val('');
                final_amount.val('');

            }).always(function(){
                if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
            });

        }
    });

    $(document).on("keyup", ".debit", function(e){
        $('.debit').each(function(index){
            var val_debit = $(this).val();
            var credit = $(".credit"+index);
            var kod_akaun = $(".account-code"+index);
            $('#notice_no').attr('data-row-index');
            if (!isNaN(val_debit) && val_debit.length != 0) { val_debit = parseFloat(val_debit); }
            if(val_debit > 0){
                credit.val('0.00');
                credit.prop('readonly', true);
                credit.prop('required', false);
                credit.removeAttr('pattern');
                kod_akaun.prop('required', true);
                // kod_akaun.parsley().validate();
            }else{
                credit.prop('readonly', false);
                credit.prop('required', true);
                credit.attr('pattern', '^[1-9]\\d*(\\.\\d+)?$');
            }
        });
        updateTotal();
    });

    $(document).on("blur", ".debit", function(e){
        $('.debit').each(function(index){
            var val_debit = $(this).val();
            if (!isNaN(val_debit) && val_debit.length != 0) { val_debit = parseFloat(val_debit); }
            if(val_debit > 0){
                $(this).val(val_debit.toFixed(2));
            }
        });
        updateTotal();
    });

    $(document).on("ready, keyup", ".credit", function(e){
        $('.credit').each(function(index){
            var val_credit = $(this).val();
            var debit = $(".debit"+index);
            var kod_akaun = $(".account-code"+index);
            if (!isNaN(val_credit) && val_credit.length != 0) { val_credit = parseFloat(val_credit); }

            if(val_credit > 0){
                debit.val('0.00');
                debit.prop('readonly', true);
                debit.prop('required', false);
                debit.removeAttr('pattern');
                kod_akaun.prop('required', true);
                // kod_akaun.parsley().validate();
            }else{

                debit.prop('readonly', false);
                debit.prop('required', true);
                debit.attr('pattern', '^[1-9]\\d*(\\.\\d+)?$');
            }
        });
        updateTotal();
    });

    $(document).on("blur", ".credit", function(e){
        $('.credit').each(function(index){
            var val_credit = $(this).val();
            if (!isNaN(val_credit) && val_credit.length != 0) { val_credit = parseFloat(val_credit); }
            if(val_credit > 0){
                $(this).val(val_credit.toFixed(2));
            }
        });
        updateTotal();
    });


    //--------------------------------------------------------------------------------
    //  DISPLAY TOTAL AT EDIT PAGE
    //--------------------------------------------------------------------------------
    updateTotal();

    //--------------------------------------------------------------------------------
    //  SHOW ROW 1
    //--------------------------------------------------------------------------------

    $('#table-vot-akaun').attr('data-list');
    if($('#table-vot-akaun').attr('data-list') == 0){
        $('#tbl-row-0').show();
        // $('#tbl-row-1').show();
    }

});

//--------------------------------------------------------------------------------------------------------------------------------
// FUNCTION DUPLICATE ROW
//--------------------------------------------------------------------------------------------------------------------------------
var table_vot_akaun = 'table-vot-akaun';

var regex_id = /(\d)/;// => id 0
let regex_name = /\[\d+](?!.*\[\d)/;// => name[0][0]

function duplicateRow(){

    //GET ROW INDEX
    var row_index = $('#'+table_vot_akaun).find('tbody>tr:visible').length;

    //$("#row_index").val(row_index) ;

    if(row_index==0){
        $('#tbl-row-0').show();
        $('#tbl-row-0').children('td').css('background-color','#d9ffdb');
    }else{
        row_index--;
        var table_row = "#tbl-row-"+row_index;
        //FIND ROW
        var $tr    = $(table_row).closest(table_row);
        //SET CLONE PARAM
        var $clone = $tr.clone(true);
        row_index++;

        // Check if the original row inputs are valid

        $clone.find(':text').val('');
        $clone.find('input').prop("disabled", false);
        $clone.find('select').val('0');
        $clone.find('select').attr("required", true);

        $clone.find('input[type=hidden]').attr('value','');
        $clone.closest('tr').children('td').css('background-color','#d9ffdb');

        //UPDATE ROW ID
        $clone.attr('id', 'tbl-row-'+row_index);
        //UPDATE ELEMENT ID IN ROW
        $clone.find('input, select, a, span').each(function(){
            var input_id = this.id ;
            //var match = id.match(regex) || [];
            if(input_id.length){
                $(this).attr('id',$(this).attr('id')?.replace(regex_id, row_index));
                $(this).attr('class',$(this).attr('class')?.replace(regex_id, row_index));
                $(this).attr('data-row-index', row_index);
            }
        });

        //DUPLICATE ROW
        $tr.after($clone);

    }
}

//--------------------------------------------------------------------------------------------------------------------------------
//REMOVE NEW ROW /DISABLED EXISTING ROW -> IF complaint_others_id ID >0
//--------------------------------------------------------------------------------------------------------------------------------

$(document).on("click", ".btnRemove", function(e) {

    var row_index = $(this).attr('data-row-index');
    var votlist_id = $("#votlist_id"+row_index).val();

    //  PAGE EDIT
    if(votlist_id>0){

        var row = $('#row_vot_list_id').val(votlist_id);
        //REMOVE VALIDATION AFTER ROW HAS BEEN REMOVED
        row.attr("required",false);
        swalDeleteRow();

    }else{
        var row_index = $('#'+table_vot_akaun).find('tr').length-2;
        if(row_index==0){
            $('#tbl-row-0').hide();
        }else{
            $(this).closest("tr").remove();
            $("#"+table_vot_akaun).find('tbody tr').each(function(row_index) {

                //UPDATE ROW INDEXING ID/NAME/CLASS
                $(this).attr('id', 'tbl-row-'+row_index);
                // $(this).attr('class', 'tbl-row-'+row_index);
                updateElementCounter($(this), row_index);
            });
        }
    }
    $("#row_index").val(row_index) ;
    updateTotal();
});

//--------------------------------------------------------------------------------------------------------------------------------
// UPDATE ELEMENT INDEXING BY ATTRIBUTE ID/NAME
//--------------------------------------------------------------------------------------------------------------------------------
function updateElementCounter(section, row_index){
    //UPDATE ELEMENT ID/NAME/CLASS IN ROW
    section.find('select, input, a, span').each(function(){
        let input_id = this.id ; //alert (input_id);
        if(input_id != undefined && input_id.length){
            $(this).attr('id',$(this).attr('id')?.replace(regex_id, row_index));
            $(this).attr('class',$(this).attr('class')?.replace(regex_id, row_index));
            $(this).attr('data-row-index', row_index);
        }
    });
}

//--------------------------------------------------------------------------------------------------------------------------------
// UPDATE TOTAL DEBIT & CREDIT
//--------------------------------------------------------------------------------------------------------------------------------
function updateTotal(){

    var total_tpn = $('#payment_notice_amount').val();
    (!isNaN(total_tpn ) && total_tpn .length != 0) ? total_tpn  = parseFloat(total_tpn ) : total_tpn  = 0;

    var sum_debit = 0;
    $('.debit').each(function(){
        var val_debit = $(this).val();
        if (!isNaN(val_debit) && val_debit.length != 0) {
            val_debit = parseFloat(val_debit);
        }else{
            val_debit = 0;
        }
        sum_debit += val_debit;

    });

    var sum_kredit = 0;
    $('.credit').each(function(){
        var val_kredit = $(this).val();
        if (!isNaN(val_kredit) && val_kredit.length != 0) {
            val_kredit = parseFloat(val_kredit);
        }else{
            val_kredit = 0;
        }
        sum_kredit += val_kredit;
    });

    var final_amount = 0.00;
    final_amount = (( total_tpn + sum_debit) - sum_kredit) ;
    var adjustment_amount = 0.00;
    adjustment_amount = sum_debit - sum_kredit ;

    if(!isNaN(final_amount)){
        $('#final_amount_hidden').val(final_amount);
        if (final_amount >= 0) {
            $('#final_amount').val(final_amount.toFixed(2));
        } else {
            //add '()' and remove '-' if result is negative
            const absoluteAmount = Math.abs(final_amount);
            $('#final_amount').val('(' + absoluteAmount.toFixed(2) + ')');
        }
    }else{
        $('#final_amount').val("0.00");
        $('#final_amount_hidden').val("0.00");
    }

    if(!isNaN(adjustment_amount)){
        $('#adjustment_amount').val(adjustment_amount);
    }else{
        $('#adjustment_amount').val("0.00");
    }

    $('#msg_error').html("");

    if(sum_debit>0 || sum_kredit>0){
        $('#btn_simpan').attr('disabled', false);
    }
    else if(sum_debit==0 && sum_kredit==0){
        $('#msg_error').html("Sila lengkapkan maklumat Vot/Kod Akaun");
        $('#btn_simpan').attr('disabled', true);
    }
}

//----------------------------------------------------------------------------------------------------------------------
//  VALIDATE/HIDE/SHOW AND DISABLE INPUT BY RADIO
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

function getMaklumatVotAkaun(payment_category_id){

    var select = $('.account-code');

    var fieldHTML = "";

    if(payment_category_id != "" ){

        var data = $("#account_code").val();
        var data = jQuery.parseJSON(data);

        Object.keys(data).forEach(function (key) {
             if(payment_category_id == data[key]['payment_category_id']){
                var id = data[key]['id'];
                var income_code = data[key]['income_code'];
                var income_code_description = data[key]['income_code_description'];

                fieldHTML +='<option value='+ id +' >'+ income_code +' - '+ income_code_description +'</option>';
             }
        });
        select.each(function(i, obj) {
            $('#income_code'+i).find('option').not(':first').remove();
            $('#income_code'+i).append(fieldHTML);
        });

    }
}

//--------------------------------------------------------------------------------------------------------------------------------
// SWAL MESSAGE
//--------------------------------------------------------------------------------------------------------------------------------
var $selector = $('#form');

//SWAL KEMASKINI PENYATA PEMUNGUT
$(document).on("click", ".swal-kemaskini-jurnal", function(e){
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
            document.getElementById('btn_type_input').value = 'kemaskini';
            thisButton.closest('form').trigger("submit");
        }
    })
});

$(document).on("click", ".swal-simpan-jurnal", function(e){

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

//--------------------------------------------------------------------------------------------------------------------------
//SWAL BATAL
//--------------------------------------------------------------------------------------------------------------------------
$(document).on("click", ".swal-batal-jurnal", function(e){

    var form = $('#form-cancel');
    let thisButton = $(this);
    let jurnal_id = $(this).attr('data-index');
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
                        swal.fire({text:"Sila masukkan sebab jurnal dibatalkan !",icon:"error"});
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
                            $('#cancel_remarks_'+jurnal_id).val(sebab_batal);
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
        }
    })
});

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------
$(function() {
    checkTabs();
});