
$(document).ready(function() {

    var btn_simpan = $('#btn_simpan');
    var btn_hantar = $('#btn_hantar');
    var btn_simpan_edit = $('#btn_simpan2');
    var btn_hantar_edit = $('#btn_hantar2');

    //--------------------------------------------------------------------------------
    // FILTER PAGE BUKA REKOD
    //--------------------------------------------------------------------------------
    var checking_status = $(".checking_status");
    var approval_status  = $(".approval_status");

    var current_status = $("#current_status").val();
    var login_officer = $("#current_officer");
    var approver = $("#approver");
    var preparer_id = $("#preparer_id");
    var checker_id = $("#checker_id");

    var kuiri_remarks = $("#kuiri_remarks");
    var div_kuiri_remarks = $("#section-kuiri");
    var hide_for_preparer = $("#hide-for-preparer");
    var hide_for_checker = $("#hide-for-checker");

    var proses_sedia = $("#proses_sedia").val();
    var proses_semak = $("#proses_semak").val();
    var proses_lulus = $("#proses_lulus").val();

    div_kuiri_remarks.hide();

    if(proses_sedia){
        if(preparer_id.val() == login_officer.val()) {
            hide_for_preparer.hide();
            checking_status.removeAttr('required');
            approver.removeAttr('required');
            approval_status.removeAttr('required');
            kuiri_remarks.removeAttr('required');

            if(current_status == 1 || current_status == 5){
                btn_hantar.show();
                btn_simpan.show();
            }else{
                btn_hantar_edit.hide();
                btn_simpan_edit.hide();
            }
        }
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
            btn_hantar_edit.hide();
            btn_simpan_edit.hide();
        }
        approval_status.removeAttr('required');
        kuiri_remarks.removeAttr('required');
    }

    if(proses_lulus){
        kuiri_remarks.removeAttr('required');
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

    //------------------------------------------------------------------------------------------
    //DECIMAL
    //------------------------------------------------------------------------------------------
   
    $(document).on("keyup", ".debit", function(e){
        $('.debit').each(function(index){
            var val_debit = $(this).val();
            var credit = $(".credit"+index); //console.log(index);
            if (!isNaN(val_debit) && val_debit.length != 0) { val_debit = parseFloat(val_debit); }
            if(val_debit > 0){
                credit.prop('readonly', true);
                credit.prop('required', false);
            }else{
                credit.prop('readonly', false);
                credit.prop('required', true);
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
    });

    $(document).on("keyup", ".credit", function(e){
        $('.credit').each(function(index){
            var val_credit = $(this).val(); 
            var debit = $(".debit"+index);
            if (!isNaN(val_credit) && val_credit.length != 0) { val_credit = parseFloat(val_credit); }
            if(val_credit > 0){
                debit.prop('readonly', true);
                debit.prop('required', false);
            }else{
                debit.prop('readonly', false);
                debit.prop('required', true);
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

    // Additional code to disable the counterpart field when the other is focused

    // $(document).on("focus", ".credit", function(e) {
    //     $('.debit').prop('readonly', true);
    //     $('.debit').prop('required', false);
    // });
    // $(document).on("focus", ".debit", function() {
    //     $('.credit').prop('readonly', true);
    //     $('.credit').prop('required', false);
    // });

    // $(document).on("focus", ".credit", function(e){
    //     $('.credit').each(function(index){
    //         $('.debit').prop('readonly', true);
    //         $('.debit').prop('required', false);
    //     });
    // });



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
        if (validateRowInputs(row_index)) {

            $clone.find(':text').val('');
            $clone.find('input').prop("readonly", false);
            //$clone.find('input').prop("readonly", true);
            $clone.find('select').val("");
            $clone.find('select').attr("required", true);
            // $('#error_code').val('sila masukkan'); //alert()
            // $clone.parsley().validate();
            // $clone.find('.account-code').parsley().validate();
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

            //FOR CLONING - SELECT2
            $clone.closest('tr').children('td').find(".select2:last").remove();
            $clone.closest('tr').children('td').find("select").select2({ width:'100%' });

        }

        //DUPLICATE ROW
        $tr.after($clone);

        //ENABLE SELECT 2 IN ALL ROW
        $(".select2-account").select2();

        //VALIDATION KOD AKAUN
        // var income_code = $(".account-code"); alert(income_code)
        // income_code.prop('required', true);
        //income_code.parsley().validate();
    }
}

   // Function to validate inputs in a row
   function validateRowInputs(rowId) {
    var isValid = true;
    var $row = $('.row[data-row-id="' + rowId + '"]');
    $row.find('input').each(function () {
        if ($(this).val() === '') {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    return isValid;
}

//--------------------------------------------------------------------------------------------------------------------------------
//REMOVE NEW ROW /DISABLED EXISTING ROW -> IF complaint_others_id ID >0
//--------------------------------------------------------------------------------------------------------------------------------

$(document).on("click", ".btnRemove", function(e) {

    var row_index = $(this).attr('data-row-index');
    var votlist_id = $("#votlist_id"+row_index).val();

    //  PAGE EDIT
    if(votlist_id>0){

        var row = $('#id_by_row').val(votlist_id);
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

    if(!isNaN(sum_debit)){
        $('#total_debit').val(sum_debit.toFixed(2));
    }else{
        $('#total_debit').val("0.00");
    }

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

    if(!isNaN(sum_kredit)){
        $('#total_credit').val(sum_kredit.toFixed(2));
    }else{
        $('#total_credit').val("0.00");
    }

    $('#msg_error').html("");
    if((sum_debit>0 || sum_kredit>0) && sum_debit!=sum_kredit){
        $('#msg_error').html("Jumlah amaun debit & kredit tidak seimbang !");
        $('#btn_simpan').attr('disabled', true);
        $('#btn_simpan2').attr('disabled', true);
        $('#btn_hantar2').attr('disabled', true);
    }
    else if(sum_debit==0 && sum_kredit==0){
        $('#msg_error').html("Sila lengkapkan maklumat Vot/Kod Akaun");
        $('#btn_simpan').attr('disabled', true);
        // $('#btn_hantar').attr('disabled', true);
    }
    else if(sum_debit== sum_kredit){
        $('#btn_simpan').attr('disabled', false);
        // $('#btn_hantar').attr('disabled', false);
        $('#btn_simpan2').attr('disabled', false);
        $('#btn_hantar2').attr('disabled', false);
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
//SWAL BATAL TEMUJANJI ADUAN
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

