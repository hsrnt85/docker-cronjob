//--------------------------------------------------------------------------------------------------------------------------------
// CHECK REMARKS
// master.js -> setLabelRequired()
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    if($('.application_status').val()==4){
        $('#officer_approval').attr('required',false);
        $('#officer_approval').prop('disabled', true);
        $('#remarks').attr('required',true);
        $('#remarks').prop('disabled', false);
    }else{
        $('#officer_approval').attr('required',true);
        $('#officer_approval').prop('disabled', false);
        $('#remarks').attr('required',false);
        $('#remarks').prop('disabled', true);
        $('#remarks').val('');
    }

    setLabelRequired();
});
$(document).on('click', '.application_status', function(){

    if($(this).val()==4){
        $('#officer_approval').attr('required',false);
        $('#officer_approval').prop('disabled', true);
        $('#remarks').attr('required',true);
        $('#remarks').prop('disabled', false);
    }else{
        $('#officer_approval').attr('required',true);
        $('#officer_approval').prop('disabled', false);
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
