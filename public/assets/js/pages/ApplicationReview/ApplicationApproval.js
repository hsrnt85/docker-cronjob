//--------------------------------------------------------------------------------------------------------------------------------
// CHECK REMARKS
// master.js -> setLabelRequired()
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    if($('.application_status').val()==6){
        $('#remarks').attr('required',true);
        $('#remarks').prop('disabled', false);
    }else{
        $('#remarks').attr('required',false);
        $('#remarks').prop('disabled', true);
        $('#remarks').val('');
    }

    setLabelRequired();
});

$(document).on('click', '.application_status', function(){

    if($(this).val()==6){
        $('#remarks').attr('required',true);
        $('#remarks').prop('disabled', false);
    }else{
        $('#remarks').attr('required',false);
        $('#remarks').prop('disabled', true);
        $('#remarks').val('');
    }

    setLabelRequired();
});


//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

$(function() {
    checkTabs();
});
