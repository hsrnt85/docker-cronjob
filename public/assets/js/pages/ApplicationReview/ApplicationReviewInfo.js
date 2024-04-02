
$(document).ready(function() {

    $('.full_mark, #final_full_mark, #final_total_mark').prop('readonly', true);

});
//--------------------------------------------------------------------------------------------------------------------------------
// CHECK FULL MARK & CALCULATE
//--------------------------------------------------------------------------------------------------------------------------------
let val = 0;
let total_mark = 0;
$('.full_mark').each(function(){

    let i = $(this).attr('data-row-index');

    if(i == 0){
        //$(this).css("border","2px solid #34c38f");
        //$(this).css("font-weight", "bold");
        if(!isNaN($(this).val()) && $(this).val() != ''){
            val = parseInt($(this).val());
            total_mark += val;
        }
    }else{
        $(this).css("border","");
        $(this).css("font-weight", "normal");
    }

    $('#final_full_mark').val(total_mark);

});

$('.full_mark').each(function(){
    $(this).prop("readonly", true);
});
$('.mark').each(function(){
    $(this).prop("readonly", true);
});
//--------------------------------------------------------------------------------------------------------------------------------
//STYLING MARK
//--------------------------------------------------------------------------------------------------------------------------------

$('#total_mark').css('background-color','#ffe2b0');
$('#final_full_mark').css("font-weight", "bold");
$('#final_total_mark').css("font-weight", "bold");

//--------------------------------------------------------------------------------------------------------------------------------
// ON LOAD PAGE - REORDER MARK - ONLY FOR CLASS section-auto-dropdown 
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    $('.subcriteria_name').each(function(){
   
        let i_criteria = $(this).attr('data-criteria-index');
        let flag_auto_dropdown = $(this).attr('data-flag-auto-dropdown');

        if(flag_auto_dropdown == 1){
            let rowsSubCriteria = $('.subcriteria_name_row_'+i_criteria).sort(function(a, b) {
              
                let ap = $(a).find('.full_mark');
                let bp = $(b).find('.full_mark');
                if (ap.length && bp.length) {
                    return bp.val() - ap.val();
                }
            });  

            $(this).append(rowsSubCriteria);

        }
       
    });

});
