
$(document).ready(function() {

    //--------------------------------------------------------------------------------
    // INPUT MASK
    //--------------------------------------------------------------------------------
    $(".input-mask").inputmask()

    //--------------------------------------------------------------------------------
    // PAGE BUKA REKOD
    //--------------------------------------------------------------------------------
    var checking_status = $(".checking_status"); // status semakan
    var approval_status  = $(".approval_status"); // status kelulusan

    var current_status = $("#current_status").val();
    var login_officer = $("#current_officer"); // peg. login
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
    var proses_lulus = $("#proses_lulus").val();

    div_kuiri_remarks.hide();
    // PROSES SEDIA
    if(proses_sedia)
     {
        if(preparer_id.val() == login_officer.val()) {
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

    // PROSES SEMAK
    if(proses_semak)
    {
        //PAGE PEGAWAI PENYEMAK
        if(checker_id.val() == login_officer.val()) {
            hide_for_checker.hide();
        }
        //PAGE PEGAWAI PENYEDIA
        else if(preparer_id.val() == login_officer.val()) {
            hide_for_preparer.hide();
            approver.removeAttr('required');
            checking_status.removeAttr('required');
            $("#btn-hantar").hide();
            $("#btn-submit").hide();
        }
        approval_status.removeAttr('required');
        kuiri_remarks.removeAttr('required');
    }

    if(proses_lulus)
    {
        kuiri_remarks.removeAttr('required');
    }


    // PAGE BATAL (DISABLE NAV TAB SENARAI)
    if((current_status == 6 || current_status == 7 || current_status == 8)){
        var nav_senarai = $("#nav-senarai-kutipan-hasil")
        nav_senarai.removeAttr('data-bs-toggle');
        nav_senarai.removeAttr('href');
    }

    //--------------------------------------------------------------------------------
    // TABLE VOT AKAUN
    //--------------------------------------------------------------------------------
    // var row_index   =  $(this).attr('data-row-index');
    // row_index = (row_index) ? row_index : 0;

    // $(document).on("change", ".account-code", function(e){

    //     var credit      =  $("#credit"+row_index);
    //     var debit       =  $("#debit"+row_index);

    //     credit.prop("readonly", false);
    //     debit.prop("readonly", false);

    //     $(document).on("keyup", ".debit", function(e){

    //         if ($(this).val() > 0) { alert(row_index);
    //             debit .attr("required", true);
    //             credit.prop("readonly", true);
    //             credit.val('0.00');
    //             credit.attr("required", false);
    //         } else{
    //             debit .val('0.00');
    //             debit .attr("required", false);
    //             debit .prop("readonly", true);
    //             credit.attr("required", true);
    //         }
    //     });


    // });
});

//--------------------------------------------------------------------------------------------------------------------------------
// FUNCTION DUPLICATE ROW
//--------------------------------------------------------------------------------------------------------------------------------
var table_vot_akaun = 'table-vot-akaun';

var page = $("#page").val();
var msg_error_debit_kredit = "Jumlah amaun debit & kredit tidak seimbang !";

if (page=="new" ){
    $('#total_debit').val("0.00");
    $('#total_credit').val("0.00");
}
$('#msg_error').html("");


function duplicateRow(){

    var lbl_sila_pilih = "Sila Pilih";
    var table_vot_akaun = 'table-vot-akaun';
    //GET ROW INDEX
    var row_index = $('#'+table_vot_akaun).find('tbody>tr:visible').length;

    var wrapper = $('.field_wrapper_listing');
    var fieldHTML    = "<tr>";
    fieldHTML       += "<td><select name='id_vot[]' id='id_vot_"+row_index+"' class='form-control'><option value=''>"+lbl_sila_pilih+"</option>";
    fieldHTML       += "<td><input type='text' id='debit_"+row_index+"' name='debit[]' class='form-control debit' placeholder='0.00' onkeyup='checkAmaunKredit("+row_index+");'/></td> ";
    fieldHTML       += "<td><input type='text' id='credit_"+row_index+"' name='credit[]' class='form-control credit' placeholder='0.00' onkeyup='checkAmaunDebit("+row_index+");'/></td> ";
    fieldHTML       += "<td data-name='del' class='text-center'><input type='hidden' id='flag_proses_{{ $bil }}'name='flag_proses[]' value='1'> <input type='hidden' name='id_jurnal[]' value='0'><a href='javascript:void(0);' name='del0' class='btnRemove btn btn-warning'><i class='mdi mdi-minus mdi-16px'></i></a></td>";
    fieldHTML       += "</tr>";

    $(wrapper).append(fieldHTML);

    total_row = Number(row_index);
    $("#total_row").val(total_row);

    $(".btnRemove").bind("click", removeRow);

    senaraiVotAkaun(row_index);

}

//---------------------------------------------------------------------------------------------------------------------------------
//CALL BACK SENARAI - GET LIST ...
//---------------------------------------------------------------------------------------------------------------------------------
function senaraiVotAkaun(row_index){
    $.ajax({
        url: '/get_senarai_vot_akaun',
        type:"GET",
        dataType:"json",
        success:function(data) {

            var dataSenarai = data;
            var optionValue = "";
            var optionText = "";

            $.each(dataSenarai, function(keySenarai, valueSenarai){

                optionValue = valueSenarai.id;
                optionText = valueSenarai.income_code;

                var fieldHTML = '<option value="' + optionValue + 'hehehehhe' + '">' + optionText + '</option></select></td> ';

                $('#id_vot_'+row_index).append(fieldHTML);
            });
        },
        error: function (xhr, status, error) {
            console.log("AJAX request failed: " + error);
          }
    });
}

//---------------------------------------------------------------------------------------------------------------------------------
//removeRow
//---------------------------------------------------------------------------------------------------------------------------------
function removeRow(){
	var par = $(this).parent().parent();
    par.remove();

    $('td.row-index').html(function (i) {

        var counter = i + 1;
        $("#total_row").val(counter);

        return counter;
    });

    updateTotal();
}


function checkAmaunKredit(row_index){

    if($('#credit_'+row_index).val()!="") {

        $('#credit_'+row_index).val("");
        $('#credit_'+row_index).prop('readonly', true);

    }else{
        $('#credit_'+row_index).prop('readonly', false);
    }

    updateTotal();

}

function checkAmaunDebit(row_index){

    if($('#credit_'+row_index).val()!="") {

        $('#debit_'+row_index).val("");
        $('#debit_'+row_index).prop('readonly', true);

    }else{
        $('#debit_'+row_index).prop('readonly', false);
    }

    updateTotal();

}

function updateTotal(row_index){

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
    if(sum_debit!=sum_kredit){
        $('#msg_error').html(msg_error_debit_kredit);
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

    //SWAL BATAL TEMUJANJI ADUAN
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

            }})
        });
