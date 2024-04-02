
$(document).ready(function () {

    $('#date_from').datepicker('reset').datepicker('destroy').datepicker({
        'autoHide': 1
    });

    $('#date_from').change(function(){

        var startDateTo = $('#date_from').val();
        $('#date_to').datepicker('reset').datepicker('destroy').datepicker({
            'startDate': startDateTo,
            'autoHide': 1
        });
    });

    ajaxGetComplaintStatus()

    $(document).on("click", "#reset", function(e){
        $("#complaint_status").empty().append("<option value=''>  -- Pilih Status Aduan --  </option>");
    });

});

$(document).on("change", "select#complaint_type", function(e){
    ajaxGetComplaintStatus()
});


function ajaxGetComplaintStatus() {

    let type    = $('select#complaint_type').val();
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = $('select#complaint_type').attr('data-route');

    var previosSelectedComplaintType = $('#selected-complaint-type').val();
    var previousSelectedStatus = $('#selected-complaint-status').val();

    if(type)
    {
        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"GET",
            data:{
                type:type,
                _token: _token
            }
        }).done(function(response, textStatus, jqXHR){

            const $select = $("#complaint_status");

            // if complaint type was changed, clear selected option for complaint status
            previousSelectedStatus = (previosSelectedComplaintType !== type ) ? null :  previousSelectedStatus;

            // Clear previous options
            $select.empty().append("<option value=''>  -- Pilih Status Aduan--  </option>");

            $.each(response, function (index, value) {
                const option = $("<option>").val(value.id).text(value.complaint_status);
                if (value.id == previousSelectedStatus) option.attr('selected', 'selected');
                $select.append(option);
            });

        }).fail(function(jqXHR, textStatus, errorThrown){

        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        });
    }
}


