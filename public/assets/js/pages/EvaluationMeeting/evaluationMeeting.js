
$(document).ready(function() {

    //CHECK ATTENDANCE STATUS
    if($('#is_check_attendance').val()==1){
        $('#tab-application-info-kategori').show();
    }else{
        $('#tab-application-info-kategori').hide();
    }

    //
    var meeting_id = $("#meeting_id").val();
    let meeting_is_done = $("#meeting_is_done").val();
    let data_quarters_category_arr = jQuery.parseJSON($('#quarters_category_id_arr').val());
    let data_application_status_arr = jQuery.parseJSON($('#application_status_arr').val());

    //GET SENARAI PERMOHONAN BY MEETING ID & QUARTERS CATEGORY ID
    $.each(data_quarters_category_arr, function(quarters_category_id){

        getApplicationList(meeting_id, quarters_category_id, data_application_status_arr, meeting_is_done);
        

    });

});


//GET APPLICATION LIST
function getApplicationList(meeting_id, quarters_category_id, data_application_status_arr, meeting_is_done){

    if(quarters_category_id)
    {
        let quarters_category_arr = jQuery.parseJSON($('#quarters_category_id_arr').val());
        let _token = $('meta[name="csrf-token"]').attr('content');
        var tableId = "application_data"+quarters_category_id;

        $("#"+tableId).empty();
        var wrapper = $('#'+tableId);
        var counter = 0;
        //alert(data_application_status_arr);
        $.ajax({
            type:'POST',
            url:'/MesyuaratPenilaian/ajaxGetApplicationList',
            data:{
                mid:meeting_id,
                qcid:quarters_category_id,
                _token: _token
            },
            success:function(dataApplicationList){

                checkbox_tangguh = "";
                checkbox_lulus = "";
                checkbox_gagal = "";
                checkbox_rayuan_semula = "";
                $.each(dataApplicationList, function(key, value){

                    application_quarters_category_id = value.application_quarters_category_id;
                    application_id = value.id;
                    applicant_name = value.applicant_name;
                    services_type = value.services_type;
                    total_mark = value.total_mark;
                    is_delay = value.is_delay;
                    meeting_application_status_id = value.meeting_application_status_id;
                    meeting_application_id = value.meeting_application_id;
                    meeting_quarters_category_id = value.meeting_quarters_category_id;
                    checkbox_meeting = (application_id == meeting_application_status_id ) ? 'checked' : "";
                    checkbox_tangguh = (is_delay == 1) ? 'checked' : "";
                    application_status_tangguh = 99;
                    //console.log(quarters_category_id+'-'+meeting_quarters_category_id);
                    //if(application_status>5) checkbox_tangguh = (is_delay == 1) ? 'checked' : "";
                    //else checkbox_tangguh = 'checked' ;
                    //checkbox_gagal = (application_status == 8) ? 'checked disabled' : "";
                    //checkbox_rayuan_semula = (application_status == 9) ? 'checked disabled' : "";
                    //checkbox_disabled = (application_status > 5) ? '' : "";
                    
                    checkbox_disabled = (meeting_is_done==1)  ? "disabled" : "";


                    counter++;

                    var fieldHTML = '';
                    fieldHTML+='<tr>';
                    fieldHTML+='<td>'+counter +'</td>';
                    fieldHTML+='<td>'+ applicant_name +'</td>';
                    fieldHTML+='<td>'+ services_type +'</td>';
                    fieldHTML+='<td class="text-center">'+ total_mark +'</td>';
                    fieldHTML+='<td class="text-center width="1%">';

                    fieldHTML+='<span id="msg_'+quarters_category_id+application_id+'" ></span>';
                    fieldHTML+='<div class="form-check row_'+quarters_category_id+application_id+'" >';
                    fieldHTML+='<input class="form-check-input application_status_'+application_id+'" type="checkbox" name="application_status['+quarters_category_id+']['+application_id+']" id="application_'+quarters_category_id+application_id+application_status_tangguh+'" value="'+application_status_tangguh+'" qcid="'+quarters_category_id+'" onclick="setApplicationStatus(this,'+quarters_category_id+','+application_id+',0)" '+ checkbox_tangguh +
                    ' data-parsley-required-message="Sila pilih keputusan" data-parsley-errors-container="#parsley-errors-container'+quarters_category_id+application_id+'" '+checkbox_disabled+'>';
                    fieldHTML+='<label class="form-check-label" for="application"'+counter+'> Tangguh </label>';
                    fieldHTML+='</div>';

                    //GET SENARAI PERMOHONAN BY MEETING ID & QUARTERS CATEGORY ID
                    $.each(data_application_status_arr, function(k, val){

                        application_status_id = val.id;
                        checkbox_application_status = (quarters_category_id==meeting_quarters_category_id && meeting_application_id == application_id && meeting_application_status_id == application_status_id) ? 'checked' : "";
                        application_status_name = capitalizeText(val.status);

                        fieldHTML+='<div class="form-check row_'+quarters_category_id+application_id+'" >';
                        fieldHTML+='<input class="form-check-input application_status_'+application_id+'" type="checkbox" name="application_status['+quarters_category_id+']['+application_id+']" id="application_'+quarters_category_id+application_id+application_status_id+'" value="'+application_status_id+'" qcid="'+quarters_category_id+'" onclick="setApplicationStatus(this,'+quarters_category_id+','+application_id+','+application_status_id+')" '+ checkbox_application_status +
                        ' data-parsley-required-message="Sila pilih keputusan" data-parsley-errors-container="#parsley-errors-container'+quarters_category_id+application_id+'"  '+checkbox_disabled+'>';
                        fieldHTML+='<label class="form-check-label" for="application'+quarters_category_id+application_id+'"> '+application_status_name+'</label>';
                        fieldHTML+='</div>';
                    });

                    fieldHTML+='<div id="parsley-errors-container'+quarters_category_id+application_id+'"></div>'
                    fieldHTML+='</td>';
                    fieldHTML+='<td class="text-center">';
                    fieldHTML+='<div class="btn-group" role="group">';
                    fieldHTML+='<a class="btn btn-outline-primary px-2 py-1" onclick="showApplicationInfo('+application_id+', '+quarters_category_id+','+meeting_id+')"><i class="mdi mdi-folder-search mdi-18px"></i></a>';
                    fieldHTML+='</div>';
                    fieldHTML+='</td>';
                    fieldHTML+='</tr>';

                    $(wrapper).append(fieldHTML);

                });

            },
            error: function(){ }
        });
    }
}

//
function setApplicationStatus(elem, quarters_category_id, application_id, application_status_id){
    //CHECK SELECTED APPLICATION STATUS - ONLY 1
    var elem_application_status = 'application_status_'+application_id;
   
    $(elem).each(function(){
        let outerThis = this;
        let flag_checked = outerThis.checked;
        let status = outerThis.value;

        if(flag_checked && status == 7){

            $('.'+elem_application_status).not(outerThis).each(function(){
                let data_quarters_category_id = $('#' + this.id).attr('qcid');
                if(data_quarters_category_id != quarters_category_id && this.value == 8){
                    $('#' + this.id).attr('disabled', false);
                    $('#' + this.id).prop('checked', true);
                } 
                else {
                    $('#' + this.id).prop('checked', false);
                    //$('#' + this.id).attr('disabled', true);
                }
            }) 

        }else if(flag_checked && status == 8){

            $('.'+elem_application_status).not(outerThis).each(function(){
                let data_quarters_category_id = $('#' + this.id).attr('qcid');
                if(data_quarters_category_id != quarters_category_id && (this.value == 99 || this.value == 9)){
                    $('#' + this.id).prop('checked', false);
                    $('#' + this.id).attr('disabled', true);
                }else if(data_quarters_category_id == quarters_category_id && (this.value != status)){
                    $('#' + this.id).prop('checked', false);
                    //$('#' + this.id).attr('disabled', true);
                }else{
                    //$('#' + this.id).prop('checked', false);
                    $('#' + this.id).attr('disabled', false);
                } 
            }) 

        }else if(flag_checked && status != 7 && status != 8){

            $('.'+elem_application_status).not(outerThis).each(function(){
                let data_quarters_category_id = $('#' + this.id).attr('qcid');
                $('#' + this.id).attr('disabled', false);
                if(data_quarters_category_id != quarters_category_id && this.value == status){
                    $('#' + this.id).prop('checked', true);
                    //$('#' + this.id).attr('disabled', false);
                }else{
                    $('#' + this.id).prop('checked', false);
                    //$('#' + this.id).attr('disabled', true);
                } 
            }) 

        }else{
            $('.'+elem_application_status).not(outerThis).attr('disabled', false);
        }

    });

}

//GET APPLICATION INFO
function showApplicationInfo(application_id, quarters_category_id, meeting_id=0){

    if(application_id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'POST',
            url:'/MesyuaratPenilaian/ajaxGetApplicationById' ,
            data:{
                id:application_id,
                qcid:quarters_category_id,
                mid:meeting_id,
                _token: _token
            },
            success:function(data){
               $('#modal-body').html(data.html);
               $('#modal-view-application').modal('show')
            },
            error: function(){ }
        });
    }
}

