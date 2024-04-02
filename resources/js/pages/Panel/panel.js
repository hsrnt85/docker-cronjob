$(document).on("change", "select#district", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    // let _token  = "{{ csrf_token() }}";
    let url     = this.dataset.route;

    if(id)
    {
        if(!$("div#user-loading").hasClass("spinner-border")) $('div#user-loading').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST",
            data:{
                id:id,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){
            $('select#user').prop( "disabled", false );
            if($("div#user-feedback").hasClass("d-block")) $('div#user-feedback').toggleClass( "d-block" );

            let html = `<option value="">-- Pilih Panel --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option value="${val.id}">${val.name}</option>`;
            });

            $('select#user').html(html);

        }).fail(function(jqXHR, textStatus, errorThrown){
            // console.log(errorThrown);
            // console.log(textStatus);
            // console.log(jqXHR);
            console.log(jqXHR.responseJSON);
            // console.log(jqXHR.responseJSON.error);

            let html = `<option value="">-- Pilih pegawai --</option>`;

            if(jqXHR.responseJSON.error)
            {
                $('select#user').html(html);
                $('select#user').prop( "disabled", true );

                $('div#user-feedback').html(jqXHR.responseJSON.error);
                if(!$("div#user-feedback").hasClass("d-block")) $('div#user-feedback').toggleClass( "d-block" );
            }
  
        }).always(function(){
            console.log("hello");
            
            if($("div#user-loading").hasClass("spinner-border")) $('div#user-loading').toggleClass("spinner-border");

        });
    }
});

$(document).ready(function() {
    // Select2 Multiple
    $('.select2-multiple').select2({
        placeholder: "  -- Pilih Panel -- ",
    });

});

$(document).ready(function() {

    let dataServicesTypeId = jQuery.parseJSON($('#services_type_id_arr').val());

    //GET SENARAI PERMOHONAN BY MEETING ID & SERVICE TYPE ID
    $.each(dataServicesTypeId, function(services_type_id){

        getApplicationList(services_type_id);

    });

 });


//GET APPLICATION LIST
function getApplicationList(services_type_id){

    if(services_type_id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');
        var tableId = "application_data"+services_type_id;

        $("#"+tableId).empty();
        var wrapper = $('#'+tableId);
        var counter = 0;

        $.ajax({
            type:'POST',
            url:'/Panel Mesyuarat/ajaxGetApplicationList' ,
            data:{
                stid:services_type_id,
                _token: _token
            },
            success:function(data){

                checkbox_meeting = "";
                $.each(data, function(key, value){

                    application_id = value.id;
                    applicant_name = value.name;
                    total_mark = value.total_mark;
                    //checkbox_lulus = (application_status == 7 ) ? 'checked' : "";
                    //checkbox_disabled = (application_status > 0 ) ? ' disabled' : "";
                    
                    counter++;

                    var fieldHTML = '';

                        fieldHTML+='<tr>';
                        fieldHTML+='<td class="text-center">'+ counter +'</td>';
                        fieldHTML+='<td>'+ applicant_name +'</td>';
                        fieldHTML+='<td class="text-center">'+ total_mark +'</td>';
                        fieldHTML+='<td class="text-center">'+ "Diluluskan" +'</td>';
                        fieldHTML+='<td class="text-center" width="1%">';

                        fieldHTML+='<div class="form-check">';
                        //fieldHTML+='<input class="form-check-input" type="radio" name="application_status['+application_id+']" id="application"'+counter+' value="{{ $listApplication->id }}" '+ checkbox_meeting + checkbox_disabled +'>';
                        fieldHTML+='</div>';
                        fieldHTML+='</td>';

                    $(wrapper).append(fieldHTML);

                });

            },
            error: function(){ }
        });
    }
}
