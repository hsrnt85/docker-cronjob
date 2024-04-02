
$(document).ready(function() {

    var meeting_id = $("#meeting_id").val();
    let dataServicesTypeId = jQuery.parseJSON($('#quarters_category_id_arr').val());

    //GET SENARAI PERMOHONAN BY MEETING ID & SERVICE TYPE ID
    $.each(dataServicesTypeId, function(services_type_id){

        getApplicationList(meeting_id, services_type_id);

    });

});


//GET APPLICATION LIST
function getApplicationList(meeting_id, services_type_id){

    if(services_type_id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');
        var tableId = "application_data"+services_type_id;

        $("#"+tableId).empty();
        var wrapper = $('#'+tableId);
        var counter = 0;

        $.ajax({
            type:'POST',
            url:'/evaluationMeeting/ajaxgetapplicationlist' ,
            data:{
                mid:meeting_id,
                stid:services_type_id,
                _token: _token
            },
            success:function(data){

                $.each(data, function(key, value){

                    application_id = value.application_id;
                    applicant_name = value.name;
                    total_mark = value.total_mark;
                    application_status = value.application_status;

                    counter++;

                    var fieldHTML = '';

                        fieldHTML+='<tr>';
                        fieldHTML+='<td>'+ counter +'</td>';
                        fieldHTML+='<td>'+ applicant_name +'</td>';
                        fieldHTML+='<td class="text-center">'+ total_mark +'</td>';
                        fieldHTML+='<td class="text-center" width="1%">';

                        fieldHTML+='<div class="form-check">';
                        fieldHTML+='<input class="form-check-input" type="radio" name="application_status['+application_id+']" id="application"'+counter+' value="7" >';
                        fieldHTML+='<label class="form-check-label" for="application"'+counter+'> Lulus </label>';
                        fieldHTML+='</div>';
                        fieldHTML+='<div class="form-check">';
                        fieldHTML+='<input class="form-check-input" type="radio" name="application_status['+application_id+']" id="application"'+counter+' value="8" >';
                        fieldHTML+='<label class="form-check-label" for="application"'+counter+'> Gagal </label>';
                        fieldHTML+='</div>';
                        fieldHTML+='<div class="form-check">';
                        fieldHTML+='<input class="form-check-input" type="radio" name="application_status['+application_id+']" id="application"'+counter+' value="9" >';
                        fieldHTML+='<label class="form-check-label" for="application"'+counter+'> Gagal (Rayuan Semula) </label>';
                        fieldHTML+='</div>';
                        fieldHTML+='</td>';

                        fieldHTML+='<td class="text-center">';
                        fieldHTML+='<div class="btn-group" role="group">';
                        fieldHTML+='<a class="btn btn-outline-primary px-2 py-1" onclick="showApplicationInfo('+application_id+')"><i class="mdi mdi-folder-search mdi-18px"></i></a>';
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

//GET APPLICATION INFO
function showApplicationInfo(application_id){

    if(application_id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'POST',
            url:'/evaluationMeeting/ajaxgetapplicationbyid' ,
            data:{
                id:application_id,
                _token: _token
            },
            success:function(data){
                //console.log(data.html);
               $('#modal-body').html(data.html);
               $('#modal-view-application').modal('show')

            },
            error: function(){ }
        });
    }

}



