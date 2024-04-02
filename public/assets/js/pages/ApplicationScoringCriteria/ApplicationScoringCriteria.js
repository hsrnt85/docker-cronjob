//--------------------------------------------------------------------------------------------------------------------------------
// GLOBAL DECLARATION
//--------------------------------------------------------------------------------------------------------------------------------
//BUTTON
var btnRemoveCriteria = "btnRemoveCriteria";
var btnRemoveSubCriteria = "btnRemoveSubCriteria";
var btnRemoveSubCriteriaManual = "btnRemoveSubCriteria";
var btnAdd = "btnAdd";
var btnDuplicateRowSubCriteria = "btnDuplicateRowSubCriteria";
var btnDuplicateRowSubCriteriaManual = "btnDuplicateRowSubCriteriaManual";

//TABLE, TR
var table_criteria = 'table-criteria';
var tr_criteria = 'tr-criteria-';

//--------------------------------------------------------------------------------------------------------------------------------
// ON LOAD PAGE
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    showHideSectionElement( $('.section-manual'), 1 );
    showHideSectionElement( $('.section-data-hrmis'), 0 );
    showHideSectionElement( $('.section-auto-dropdown'), 0 );
    showHideSectionElement( $('.section-auto-range'), 0 );
   // showHideSectionElement( $('.section-auto-range .div-row-subcriteria'), 0 )

    $('.range_to').each(function(){
        $(this).prop("disabled", true);
    });

    $('#'+table_criteria).attr('data-list');
    if($('#'+table_criteria).attr('data-list') == 0){
        $('#'+tr_criteria+'0').hide();
    }

    //CHECK ON LOAD/SUBMIT
    $('.calculation_method').each(function(){
        let section = $(this).closest('tr');
        if($(this).is(':checked')){
            showHideSectionElement( section.find('.section-data-hrmis'), 1);
        }else{
            showHideSectionElement( section.find('.section-data-hrmis'), 0);
        }
    });

    //CHECK ON LOAD/SUBMIT - SHOW HIDE SECTION
    $('.scoring_mapping_hrmis').each(function(index){

        let section = $(this).closest('tr');
        if($(this).find(':selected').attr('data-is-dropdown')==1){
            section.find('.section-auto-dropdown').hide();
            let element = $(this).find(':selected').attr('data-section');
            showHideSectionElement( section.find('.'+element), 1);
            showHideSectionElement( section.find('.section-auto-range'), 0);
            showHideSectionElement( section.find('.section-manual'), 0);
        }else if($(this).find(':selected').attr('data-is-range')==1){
            showHideSectionElement( section.find('.section-auto-range'), 1);
            showHideSectionElement( section.find('.section-auto-range .div-row-subcriteria'), 1);
            showHideSectionElement( section.find('.section-auto-dropdown'), 0);
            showHideSectionElement( section.find('.section-manual'), 0);
        }else{
            showHideSectionElement( section.find('.section-manual'), 1);
            showHideSectionElement( section.find('.section-auto-range'), 0);
            showHideSectionElement( section.find('.section-auto-dropdown'), 0);
        }
    });

    //SUBCRITERIA - CHECK ON LOAD/SUBMIT - SHOW/ HIDE ADD / REMOVE BUTTON IN
    $('.'+btnDuplicateRowSubCriteria+':visible').each(function(){

        let criteria_i = $(this).attr('data-criteria-index');
        let i = $(this).attr('data-row-index');

        if(i>0){
            //HIDE BUTTON ADD
            //$('#'+btnAdd+'-'+criteria_i+"--"+i).toggle();
            //SHOW BUTTON REMOVE
            //$('#'+btnRemoveSubCriteria+'-'+criteria_i+'--'+i).toggle();
        }
    });

    $(".operator_id").each(function(){
        section = $(this).closest('.div-row-subcriteria');
        if($(this).val() == 2){
            section.find('.range_to').prop('disabled', false);
        }else{
            section.find('.range_to').val('');
            section.find('.range_to').prop('disabled', true);
        }
    });

    //STYLING MARK
    $('#row_total_mark').css('background-color','#ffe2b0');
    $('#total_mark').css("font-weight", "bold");

    //VIEW - TOTAL MARK
    sumMark();

});

//NEW/EDIT PAGE - TOTAL MARK
$('.mark:visible').keyup(function () {
    sumMark();
});

//--------------------------------------------------------------------------------------------------------------------------------
//ROW CRITERIA
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
//DUPLICATE ROW CRITERIA
//--------------------------------------------------------------------------------------------------------------------------------
function duplicateRowCriteria(){

    //GET ROW INDEX
    var row_index = $('#'+table_criteria).find('tbody>tr:visible').length;
    if(row_index==0){
        $('#'+tr_criteria+'0').show();
    }else{
        row_index--;
        let tr_criteria_id = '#'+tr_criteria+''+row_index;

        //FIND ROW
        let $tr = $(tr_criteria_id).closest(tr_criteria_id);
        //SET CLONE PARAM
        let $clone = $tr.clone(true);
        row_index++;
        //CLEAR/RESET CLONE SECTION/INPUT VALUE/ATTRIBUTE
        $clone.find('.section-data-hrmis').hide();
        $clone.find('.section-auto-dropdown').hide();
        $clone.find('.section-auto-range').hide();
        showHideSectionElement( $clone.find('.section-auto-range .div-row-subcriteria'), 0);
        $clone.find('.section-manual .div-row-subcriteria-manual').slice(1).remove();

        $clone.find('input.range_to').prop("disabled", true);
        $clone.find(':text').val('');
        $clone.find(':checkbox').prop('checked', false);
        $clone.find('input:hidden').val('');
        $clone.find('select').prop('selectedIndex',0);
        //SET BACKGROUND COLOR - ODD/EVEN
        let row_bil = $("#"+table_criteria).find('tbody tr').length; row_bil++;
        checkBackgroundColor($clone, row_bil);
        setBackgroundColor($clone, row_bil);
        //ENABLED ELEMENT
        $clone.find('.section-manual select:disabled').removeAttr('disabled');
        $clone.find('.section-manual input:disabled').removeAttr('disabled');
        //UPDATE ROW INDEXING ID/NAME/CLASS
        updateElementCounter($clone, tr_criteria, row_index, row_bil);
        //DEFAULT = SHOW MANUAL MARKING SECTION
        showHideSectionElement( $clone.find('.section-manual'), 1);
        //DUPLICATE ROW
        $tr.after($clone);
    }
}

//--------------------------------------------------------------------------------------------------------------------------------
//REMOVE ROW /DISABLED EXISTING ROW -> IF  ID >0
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on('click', '.'+btnRemoveCriteria, function(e){

    let criteria_id = $(this).attr('data-criteria-id');

    if(criteria_id>0){
        let criteria_i = $(this).attr('data-criteria-index');
        //IF EXIST IN DB
        $('#criteria_id').val(criteria_id);
        swalDeleteScoring('c','delete-form-by-criteria');
    }else{
        var row_index = $('#'+table_criteria).find('tr').length-2;
        if(row_index==0){
            $('#'+tr_criteria+'0').hide();
        }else{
            //IF NOT EXIST IN DB - REMOVE ROW
            $(this).closest("tr").remove();
            //RESET ROW BIL, BACKGROUND COLOR
            row_bil = 0;
            $("#"+table_criteria).find('tbody tr').each(function(row_index) {
                row_bil++;
                let section = $(this).children('td');
                checkBackgroundColor(section, row_bil);
                //UPDATE ROW INDEXING ID/NAME/CLASS
                updateElementCounter($(this), tr_criteria, row_index, row_bil);
                //SET BACKGROUND COLOR - ODD/EVEN
                setBackgroundColor(section, row_bil);
            });
        }

    }

    sumMark();
});

//--------------------------------------------------------------------------------------------------------------------------------
// ROW SUBCRITERIA MANUAL & AUTO
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
//DUPLICATE ROW SUBCRITERIA MANUAL & AUTO
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on('click', '.'+btnDuplicateRowSubCriteriaManual, function(e){
    let div_name = 'div-row-subcriteria-manual';
    duplicateRowSubCriteria($(this), div_name);
});

$(document).on('click', '.'+btnDuplicateRowSubCriteria, function(e){
    let div_name = 'div-row-subcriteria';
    duplicateRowSubCriteria($(this), div_name);
});

function duplicateRowSubCriteria(element, div_name){

    let regex_id = /(\--\d)/;// => id --0
    let regex_name = /\[\d+](?!.*\[\d)/;// => name[0][0]
    let criteria_i = element.attr('data-criteria-index');
    //INITIAL DIV ROW INDEX
    let row_index = 0;
    element.closest("#tr-criteria-"+criteria_i).find('.'+div_name+':visible').each(function(index){
        //alert(index);
        let section = element.closest("#tr-criteria-"+criteria_i).find('.'+div_name+':visible');
        let size = section.length-1;
        if(index == size) row_index = index;
    });

    let div = "#"+div_name+'-'+criteria_i+"--"+row_index;

    //FIND DIV
    let $div    = $(div).closest(div);
    //SET CLONE PARAM
    let $clone = $div.clone(true);
    row_index++;
    //CLEAR/RESET CLONE INPUT VALUE/ATTRIBUTE
    $clone.find(':text').val('');
    $clone.find('select').prop('selectedIndex',0);
    $clone.find('input.range_to').prop("disabled", true);
    $clone.find(':text').css("border","");
    $clone.find(':text').css("font-weight", "normal");
    //UPDATE DIV ID - div-row-subcriteria
    $clone.attr('id', div_name+'-'+criteria_i+"--"+row_index);
    $clone.attr('data-criteria-index', criteria_i);
    $clone.attr('data-row-index', row_index);
    //UPDATE ELEMENT ID/NAME IN DIV - div-row-subcriteria
    $clone.find('select, input, a').each(function(){
        let input_id = this.id ;
        let input_name = this.name ;
        if(input_id.length){
            $(this).attr('id',$(this).attr('id')?.replace(regex_id, '--'+row_index));
            $(this).attr('data-criteria-index', criteria_i);
            $(this).attr('data-row-index', row_index);
            $(this).attr('data-subcriteria-id', 0);
        }
        if(input_name != undefined && input_name.length){
            $(this).attr('name',$(this).attr('name')?.replace(regex_name, '['+row_index+']'));
            $(this).attr('data-criteria-index', criteria_i);
            $(this).attr('data-row-index', row_index);
            $(this).attr('data-subcriteria-id', 0);
        }
    });
    //SHOW BUTTON REMOVE
    $clone.find('#'+btnRemoveSubCriteriaManual+'-'+criteria_i+"--"+row_index).show();
    $clone.find('#'+btnRemoveSubCriteria+'-'+criteria_i+"--"+row_index).show();
    //HIDE BUTTON ADD
    $clone.find('#'+btnAdd+'-'+criteria_i+"--"+row_index).hide();
    //DUPLICATE DIV
    $div.after($clone);
}
//--------------------------------------------------------------------------------------------------------------------------------
//REMOVE ROW SUBCRITERIA
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on('click', '.'+btnRemoveSubCriteriaManual, function(e){
    let div_name = 'div-row-subcriteria-manual';
    removeSubCriteria($(this), div_name);
});

$(document).on('click', '.'+btnRemoveSubCriteria, function(e){
    let div_name = 'div-row-subcriteria';
    removeSubCriteria($(this), div_name);
});

function removeSubCriteria(element, div_name){

    let subcriteria_id = element.attr('data-subcriteria-id');
    if(subcriteria_id>0){
        //IF EXIST IN DB
        $('#subcriteria_id').val(subcriteria_id);
        swalDeleteScoring('sc','delete-form-by-subcriteria');
    }else{
        //IF NOT EXIST IN DB - REMOVE ROW
        element.closest('.'+div_name).remove();
    }

    sumMark();
}

//--------------------------------------------------------------------------------------------------------------------------------
// SUM MARK
//--------------------------------------------------------------------------------------------------------------------------------
function sumMark(){
    let total_mark = 0;
    let val = 0;
    $('.mark:visible').each(function(){

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
    });
    $('#total_mark').val(total_mark);
}
//--------------------------------------------------------------------------------------------------------------------------------
//ONCHANGE calculation_method
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on("change", ".calculation_method", function(e){
    let element = $(this).closest('tr');
    if ($(this).prop('checked')==true){
        $(this).val(1);
        showHideSectionElement( element.find('.section-data-hrmis'), 1 );
        showHideSectionElement( element.find('.section-auto-dropdown'), 0 );
        showHideSectionElement( element.find('.section-auto-range'), 0 );
        showHideSectionElement( element.find('.section-manual'), 0 );
    }else{
        $(this).val(0);
        showHideSectionElement( element.find('.section-manual'), 1 );
        showHideSectionElement( element.find('.section-data-hrmis'), 0 );
        showHideSectionElement( element.find('.section-auto-dropdown'), 0 );
        showHideSectionElement( element.find('.section-auto-range'), 0 );
    }
});
//--------------------------------------------------------------------------------------------------------------------------------
//scoring_mapping_hrmis
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on("change", ".scoring_mapping_hrmis", function(e){

    let section = $(this).closest('tr');
    let is_dropdown = $(this).find(':selected').attr('data-is-dropdown');
    let is_range = $(this).find(':selected').attr('data-is-range');
    let element = $(this).find(':selected').attr('data-section');
    let mapping_name = $(this).find(':selected').attr('data-mapping-name');

    if(is_dropdown == 1){
        showHideSectionElement( section.find('.section-auto-dropdown'), 0);
        showHideSectionElement( section.find('.'+element), 1);
        showHideSectionElement( section.find('.section-auto-range'), 0);
        section.find('.criteria_name').val(mapping_name);
    }
    else if(is_range == 1){
        showHideSectionElement( section.find('.section-auto-range'), 1);
        //remove if exist duplicate remarks from prev row except 1st row
        showHideSectionElement( section.find('.section-auto-range .div-row-subcriteria'), 1);
        section.find('.section-auto-range .div-row-subcriteria').slice(1).remove();
        showHideSectionElement( section.find('.section-auto-dropdown'), 0);
        section.find('.criteria_name').val(mapping_name);
    }
    else{
        showHideSectionElement( section.find('.section-auto-range'), 0);
        showHideSectionElement( section.find('.section-auto-dropdown'), 0);
    }
});
//--------------------------------------------------------------------------------------------------------------------------------
//ONCHANGE operator
//--------------------------------------------------------------------------------------------------------------------------------
$(document).on("change", ".operator_id", function(e){
    section = $(this).closest('.div-row-subcriteria');
    if($(this).val() == 2){
        section.find('.range_to').prop('disabled', false);
    }else{
        section.find('.range_to').val('');
        section.find('.range_to').prop('disabled', true);
    }
});

//--------------------------------------------------------------------------------------------------------------------------------
// FUNCTION
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
// UPDATE ELEMENT INDEXING BY ATTRIBUTE ID/NAME/CLASS
//--------------------------------------------------------------------------------------------------------------------------------
function updateElementCounter(section, element_name, row_index, row_bil){

    let regex = /(\d)/;// => 0
    let regex_name = /\[\d+](?!.*\[\d]\[)/;// => name[0][

    section.attr('id', element_name+row_index);
    section.attr('data-tr-index', row_index);
    //UPDATE ELEMENT ID/NAME/CLASS IN ROW
    section.find('span, select, input, a, div, table, tr').each(function(){
        let input_id = this.id ;
        let input_name = this.name ;
        let input_class = this.class ;
        if(input_id != undefined && input_id.length){
            $(this).attr('id', $(this).attr('id').replace(regex, row_index));
            $(this).attr('data-criteria-index', row_index);
            $(this).attr('data-row-index', 0);//reset to 0
            $(this).attr('data-criteria-id', 0);//reset to 0
        }
        if(input_name != undefined && input_name.length){
            $(this).attr('name', $(this).attr('name').replace(regex_name, '['+row_index+']'));
        }
        if(input_class != undefined && input_class.length){
            $(this).attr('class', $(this).attr('class').replace(regex, row_index));
        }
    });
    //UPDATE ROW BIL COUNTER
    section.find('#bil-criteria-'+row_index).html(row_bil);
}
//--------------------------------------------------------------------------------------------------------------------------------
// SHOW SECTION & ENABLE INPUT
// HIDE SECTION & DISABLED INPUT
//--------------------------------------------------------------------------------------------------------------------------------
function showHideSectionElement(section, flag){
    //IF SECTION IS HIDDEN, ELEMENT DISABLED AND VICE VERSA
    if(flag==0){
        section.hide();
        section.hide().find('input, select').prop('disabled', true);
    }else{
        section.show();
        section.show().find('input, select').prop('disabled', false);
    }
}
//--------------------------------------------------------------------------------------------------------------------------------
// CHECK CURRENT BACKGROUND COLOR CLASS ODD/EVEN
//--------------------------------------------------------------------------------------------------------------------------------
function checkBackgroundColor(section){
    section.css('background-color','');
    if(section.hasClass('row-odd')){ section.removeClass('row-odd'); }
    if(section.hasClass('row-even')){ section.removeClass('row-even'); }
}
//--------------------------------------------------------------------------------------------------------------------------------
// SET BACKGROUND COLOR BY CHECKING ROW = ODD/EVEN
//--------------------------------------------------------------------------------------------------------------------------------
function setBackgroundColor(section, row_bil){
    if (row_bil % 2 == 0){//even
        section.css('background-color','#e4eafb');
    }else{//odd
        section.css('background-color','');
    }
}


//--------------------------------------------------------------------------------------------------------------------------------
// ON LOAD PAGE - REORDER MARK - ONLY FOR CLASS section-auto-dropdown 
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    //service type
    let sectionServicesType = $('.services_type:visible');
    let rowsServicesType = sectionServicesType.find('.services_type_row').sort(function(a, b) {
        let ap = $(a).find('.mark');
        let bp = $(b).find('.mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionServicesType).append(rowsServicesType);

    //position type
    let sectionPositionType = $('.position_type:visible');
    let rowsPositionType = sectionPositionType.find('.position_type_row').sort(function(a, b) {
        let ap = $(a).find('.mark');
        let bp = $(b).find('.mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionPositionType).append(rowsPositionType);

    //marital_status
    let sectionMaritalStatus = $('.marital_status:visible');
    let rowsMaritalStatus = sectionMaritalStatus.find('.marital_status_row').sort(function(a, b) {
        let ap = $(a).find('.mark');
        let bp = $(b).find('.mark');
        if (ap.length && bp.length) {
            return bp.val() - ap.val();
        }
    });
    $(sectionMaritalStatus).append(rowsMaritalStatus);

});