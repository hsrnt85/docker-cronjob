
$(document).ready(function() {

    var meeting_id = $("#meeting_id").val();
    let data = jQuery.parseJSON($('#quarters_category_id_arr').val());
    let route = $('#application-list').attr('data-route');
    let page = $('#application-list').attr('data-page');

    //GET SENARAI PERMOHONAN BY ROUTE, MEETING ID, QUARTERS CATEGORY ID
    $.each(data, function(quarters_category_id){
        //alert(route );
        getApplicationList(page, route, quarters_category_id, meeting_id);

    });

});

//GET APPLICATION LIST
function getApplicationList(page, route, quarters_category_id, meeting_id=0){

    //let msg = $('#application-list').attr('data-application-msg');

    if(quarters_category_id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');
        var tableId = "application_data_category"+quarters_category_id;

        $("#"+tableId).empty();
        var wrapper = $('#'+tableId);
        var counter = 0;

        $.ajax({
            type: 'POST',
            url: route,
            data:{
                mid:meeting_id,
                qcid:quarters_category_id,
                p:page,
                _token: _token
            },
            success:function(data){
                checkbox_meeting = "";
                $.each(data, function(key, value){

                    application_id = value.id;
                    applicant_name = value.applicant_name;
                    services_type = value.services_type;
                    application_date = value.application_date;
                    total_mark = value.total_mark;
                    meeting_application_id =  0;
                    if(value.meeting_application_id) meeting_application_id = value.meeting_application_id;
                    checkbox_meeting = (application_id == meeting_application_id ) ? 'checked' : "";
                    counter++;

                    var fieldHTML = '';

                    fieldHTML+='<tr>';
                    fieldHTML+='<td class="text-center">'+ counter +'</td>';
                    fieldHTML+='<td>'+ applicant_name +'</td>';
                    fieldHTML+='<td>'+ services_type +'</td>';
                    fieldHTML+='<td>'+ application_date +'</td>';
                    fieldHTML+='<td class="text-center">'+ total_mark +'</td>';
                    fieldHTML+='<td width="1%">';
                    fieldHTML+='<div class="form-check">';
                    fieldHTML+='<input onclick="checkApplicationById(this.id)" type="checkbox" class="form-check-input application_category_ids" id="application_category_ids_'+quarters_category_id+'_'+application_id+'" value disabled '+ checkbox_meeting;
                    // fieldHTML+='required data-parsley-required-message="'+ msg +'"';
                    // fieldHTML+='data-parsley-mincheck="1" data-parsley-mincheck-message="'+ msg +'"';
                    // fieldHTML+='data-parsley-errors-container="#parsley-errors-all"';
                    fieldHTML+='> ';
                    fieldHTML+='</div>';
                    fieldHTML+='</td>';

                    $(wrapper).append(fieldHTML);

                });

            },
            error: function(){ }
        });
    }
}

//CHECK SELECTED CHAIRMAN - ONLY 1
var elem_chairmain = "meeting_chairmain_ids";
$(document).on("click", "."+elem_chairmain, function(e){

    $(this).each(function(){
        let flag_checked = this.checked;
        let id = this.value;

        if(flag_checked){
            $('.'+elem_chairmain).not(this).prop('checked', false);
            $('.'+elem_chairmain).not(this).attr('disabled', true);
            $('.'+elem_chairmain).not(this).css('cursor', 'default');
            $('#meeting_internal_panel_ids_'+id).prop('checked', true);
        }else{
            $('.'+elem_chairmain).not(this).prop('checked', false);
            $('.'+elem_chairmain).not(this).attr('disabled', false);
            $('.'+elem_chairmain).not(this).css('cursor', 'pointer');
            $('#meeting_internal_panel_ids_'+id).prop('checked', false);
        }
    });

});

//CHECK IF SELECTED - CHAIRMAN -> PANEL SELECTED TOO - MANDATORY !!
var elem_internal_panel = "meeting_internal_panel_ids";
$(document).on("click", "."+elem_internal_panel, function(e){

    $(this).each(function(){
        let id = this.value;

        if($('#meeting_chairmain_ids_'+id).is(':checked')) {
            $('#meeting_internal_panel_ids_'+id).prop('checked', true);
        }
    });

});

//SELECT/UNSELECT ALL APPLICATION
var elem_select_all_application = "select_all_application";
$(document).on("click", "#"+elem_select_all_application, function(e){

    let flag_checked_all = this.checked;
    var elem_application_all = "application_all_ids";
    var elem_application_category = "application_category_ids";

    if(flag_checked_all){
        $('.'+elem_application_all).prop('checked', true);
        $('.'+elem_application_category).prop('checked', true);
    }else{
        $('.'+elem_application_all).prop('checked', false);
        $('.'+elem_application_category).prop('checked', false);
    }

});

//CHECK APPLICATION
var elem_application_all = "application_all_ids";
$(document).on("click", "."+elem_application_all, function(e){

    let quarters_category_arr = jQuery.parseJSON($('#quarters_category_id_arr').val());

    $(this).each(function(){
        let flag_checked = this.checked;
        let id = this.value;

        if(flag_checked){
            checkApplicationByCategory(quarters_category_arr, flag_checked, id);
        }else{
            checkApplicationByCategory(quarters_category_arr, flag_checked, id);
        }
    });

});

function checkApplicationByCategory(quarters_category_arr, flag_checked, id){
    $.each(quarters_category_arr, function(quarters_category_id){
        if(flag_checked) {
            $('#application_category_ids_'+quarters_category_id+'_'+id).prop('checked', true);
            $('#application_category_ids_'+quarters_category_id+'_'+id).not(this).attr('disabled', true);
            $('#application_category_ids_'+quarters_category_id+'_'+id).not(this).css('cursor', 'default');
            $('#application_category_ids_'+quarters_category_id+'_'+id).val('checked');
        }else{
            $('#application_category_ids_'+quarters_category_id+'_'+id).prop('checked', false);
            $('#application_category_ids_'+quarters_category_id+'_'+id).val('');
        }
    });
}

function checkApplicationById(inputId){
    $('#'+inputId).val();
    if($('#'+inputId).val()=='checked') {
        $('#'+inputId).prop('checked', true);
    }
}

//---------------------------------------DATE PICKER -----------------------------------------------

jQuery(document).ready(function ($) {
    
    $("input[name='date']").datepicker({
        startDate: new Date(),
    });
    $("#date").unbind("keyup");
  });
