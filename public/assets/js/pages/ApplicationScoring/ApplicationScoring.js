

$(document).ready(function() {

    $('.full_mark, .mark, .selected_mark').prop('readonly', true);

});

//--------------------------------------------------------------------------------------------------------------------------------
//STYLING MARK
//--------------------------------------------------------------------------------------------------------------------------------

$('#total_mark').css('background-color','#ffe2b0');

$('.full_mark').each(function(){

    //CHECK FULL MARK
    let i_criteria = $(this).closest('tr').attr('data-criteria-index');

    //$('#full_mark_'+i_criteria+'_0').css("border","2px solid #34c38f");
    //$('#full_mark_'+i_criteria+'_0').css("font-weight", "bold");

});

$('#final_full_mark').css("font-weight", "bold");
$('#final_total_mark').css("font-weight", "bold");

//--------------------------------------------------------------------------------------------------------------------------------
// ON SELECT REMARKS - ASSIGN SELECTED MARK TO EVALUATION MARK
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on('click', '.cb_remarks', function(e){

    let criteria_i = $(this).closest('tr').attr('data-criteria-index');
    let subcriteria_i = $(this).attr('data-subcriteria-index');

    if($(this).is(':checked')){
        //RESET ALL REMARKS - UNCHECKED/ UNMARK
        $('.cb_remarks_'+criteria_i).not($(this)).prop('checked', false);
        $('.selected_mark_'+criteria_i).val(0);

        let full_mark = $('#full_mark_'+criteria_i+"_"+subcriteria_i).val();
        //SET MARK ON SELECTED REMARKS
        $('#selected_mark_'+criteria_i+"_"+subcriteria_i).val(full_mark);

    }else{
        $('#selected_mark_'+criteria_i+"_"+subcriteria_i).val(0);
    }

    //SUM TOTAL
    var total_mark = 0;
    total_mark += sumManualMark();
    total_mark += sumSelectedMark();

    var current_total_mark = parseInt($('#current_total_mark').val());
    final_total_mark = parseInt(total_mark)+parseInt(current_total_mark);
    $('#final_total_mark').val(final_total_mark);

});
//--------------------------------------------------------------------------------------------------------------------------------
// ON KEYUP MARK - CALCULATE TOTAL MARK
//--------------------------------------------------------------------------------------------------------------------------------
$('.manual_mark').keyup(function () {

    var val = 0;
      //CHECK MARK NOT EXCEED FULL MARK
    $('.manual_mark').each(function(){
        if($(this).val()=='') $(this).val(0);
        val = parseInt($(this).val());
        let i_criteria = $(this).attr('data-criteria-index');
        let i_subcriteria = $(this).attr('data-subcriteria-index');
        let full_mark = $('#full_mark_'+i_criteria+'_'+i_subcriteria).val();
        if( val > full_mark ) {
            $(this).val(0); $(this).focus();
        }
    });

    //SUM TOTAL
    var total_mark = 0;
    total_mark += sumManualMark();
    total_mark += sumSelectedMark();

    var current_total_mark = parseInt($('#current_total_mark').val());
    final_total_mark = parseInt(total_mark)+parseInt(current_total_mark);
    $('#final_total_mark').val(final_total_mark);

});

//SUM MANUAL
function sumManualMark(){
    var total_mark = 0;
    $('.manual_mark').each(function(){
        if(!isNaN($(this).val()) && $(this).val() != ''){
            val = parseInt($(this).val());
            total_mark += parseInt(val);
        }
    });
    return total_mark;
}
//SUM SELECTED MARK
function sumSelectedMark(){
    var total_mark = 0;
    $('.selected_mark').each(function(){
        if(!isNaN($(this).val()) && $(this).val() != ''){
            val = parseInt($(this).val());
            total_mark += parseInt(val);
        }
    });
    return total_mark;
}



//--------------------------------------------------------------------------------------------------------------------------------
// ON LOAD PAGE - REORDER MARK - ONLY FOR CLASS section-auto-dropdown
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    //service type
    let sectionServicesType = $('.services_type:visible');
    let rowsServicesType = sectionServicesType.find('.services_type_row').sort(function(a, b) {
        let ap = $(a).find('.full_mark');
        let bp = $(b).find('.full_mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionServicesType).append(rowsServicesType);

    //position type
    let sectionPositionType = $('.position_type:visible');
    let rowsPositionType = sectionPositionType.find('.position_type_row').sort(function(a, b) {
        let ap = $(a).find('.full_mark');
        let bp = $(b).find('.full_mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionPositionType).append(rowsPositionType);

    //marital_status
    let sectionMaritalStatus = $('.marital_status:visible');
    let rowsMaritalStatus = sectionMaritalStatus.find('.marital_status_row').sort(function(a, b) {
        let ap = $(a).find('.full_mark');
        let bp = $(b).find('.full_mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionMaritalStatus).append(rowsMaritalStatus);

});

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

$(function() {
    checkTabs();
});
