$(document).ready(function () {
    //------------------------------------------------------------------------------------------
    //LOADER
    //------------------------------------------------------------------------------------------
    if(performance.navigation.type == 2){  hidePageLoader(); }
    if(window.performance.getEntriesByType('navigation')[0].type === "back_forward"){  hidePageLoader(); }

    hidePageLoader();
    $(".btn #btnRemove").click(function () {
        var dataRow = $(this).attr('data-row-index');
        var btnTarget = $(this).attr('target');
        var btnSubmit = $(this).attr('type');
        var searchFormTarget = $(".search_form").attr('target');//alert(dataRow);
        if(btnTarget=='_blank' || searchFormTarget || btnSubmit || dataRow){ 
            hidePageLoader(); 
        }else{
            showPageLoader();
        }
    });
    $(window).on('beforeunload', function(){ 
        //showPageLoader();
    });

    function showPageLoader(){
        $("#coverScreen").show(); 
    }
    function hidePageLoader(){
        $("#coverScreen").hide(); 
    }
    //------------------------------------------------------------------------------------------
    //END LOADER
    //------------------------------------------------------------------------------------------

    //------------------------------------------------------------------------------------------
    //SET ASTERIKS(*) ON LABEL FOR REQUIRED INPUT
    //------------------------------------------------------------------------------------------
    setLabelRequired()
    //------------------------------------------------------------------------------------------
    //SET INPUT UPPERCASE
    //------------------------------------------------------------------------------------------
    jQuery('input:not(.input-not-uppercase)').keyup(function() {
        $(this).css('text-transform','uppercase');
    });
    jQuery('input:not(input[type=file], .input-not-uppercase)').blur(function() {
        if($(this).val().length>0) $(this).val($(this).val().toUpperCase());
    });

    jQuery('textarea:not(.input-not-uppercase)').keyup(function() {
        $(this).css('text-transform','uppercase');
    });
    jQuery('textarea:not(input[type=file], .input-not-uppercase)').blur(function() {
        if(!$(this).val().length>0) $(this).val($(this).val().toUpperCase());
    });

    //SET SECTION DISABLED
    $(".section-input-disabled :input").prop("disabled", "disabled");
    $("#section-input-disabled :input").prop("disabled", "disabled");

    //MODAL ATTACHMENT
    $('#iframe-attachment-pdf').hide();
    $('#iframe-attachment-image').hide();

    // Datepicker
    $('.datepicker').datepicker({
        format: 'dd/MM/yyyy',
    });

    //AUTO LOGOUT AFTER 30 MINUTES
    const timeout = 1800000;  // 1,800,000 ms = 30 minutes
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);

        idleTimer = setTimeout(function () {
            document.getElementById('logout-form').submit();
        }, timeout);
    });
    $("body").trigger("mousemove");

});

function setLabelRequired(){

    let formArr = ['#form','#form-review'];

    $.each( formArr, function( key, form ) {
        $(form).find('span.text-danger').remove();
        inputNameArr = [];
        $(form).find("input, textarea, select").each(function(){

            inputName = $(this).attr('name');

            if($(this).prop('required')){
                if (!inputNameArr.includes(inputName)) {
                    inputNameArr.push(inputName);
                }
            }

        });

        $.each(inputNameArr, function() {

            let el = $('[name="'+this+'"]').closest('.row').find('label.col-form-label');
            if(el.length){
                append = '<span class="text-danger"> *</span>';
                el.append(append);
            }

        });

    });

}
//------------------------------------------------------------------------------------------
//DECIMAL
//------------------------------------------------------------------------------------------
function checkDecimal(input){
    return input.value = input.value.replace(/[^0-9.]/g, "")// remove chars except number, point.
                        .replace(/(\.\d{1,2}).*/g, "$1") // remove multiple points.
                        .replace(/^0+(\d)/gm, "$1"); // remove multiple leading zeros.
}
//------------------------------------------------------------------------------------------
//NUMBER
//------------------------------------------------------------------------------------------
function checkNumber(input){
    return input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
}
//------------------------------------------------------------------------------------------
//FOCUS INPUT
//------------------------------------------------------------------------------------------
function focusInput(input){
    input.select();
}
//------------------------------------------------------------------------------------------
//SHOW HIDE ELEMENT
//------------------------------------------------------------------------------------------
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
//------------------------------------------------------------------------------------------
// CAITALIZE TEXT
//------------------------------------------------------------------------------------------
function capitalizeText(text){
    if(text){
        text = text.toLowerCase();
        return text = text[0].toUpperCase() + text.slice(1);//alert(text);
    }
}
//------------------------------------------------------------------------------------------
//attachment_id - for delete purpose
//------------------------------------------------------------------------------------------
function modalViewAttachment(title, src) {

    let file_ext = src.substring(src.lastIndexOf("."));
    var iframe = "";

    if(file_ext == ".pdf"){
        iframe = $('#iframe-attachment-pdf');
        $('#iframe-attachment-pdf').show();
    }else{
        iframe = $('#iframe-attachment-image');
        $('#iframe-attachment-image').show();
    }

    if ( iframe.length ) {
        let cdn = iframe.attr('data-cdn-src');
        iframe.attr('src',cdn+'/'+src);
        $('#modal-title-attachment').html(title);
    }

}

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------
function checkTabs()
{
    // Get the hash value
    var hash = window.location.hash;

    // If the hash value is not empty, activate the tab with the same id
    if (hash) {
        $('a[href="'+ hash +'"]').tab( "show" );
    }
}
//------------------------------------------------------------------------------------------
//CLEAR SEARCH INPUT
//------------------------------------------------------------------------------------------
function clearSearchInput()
{
    setFormTarget();
    //
    $(':input','.search_form')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .prop('checked', false)
        .prop('selected', false);

    //DRODDOWN SELECT2
    $('.select2','.search_form').val(null).trigger('change');
}
//------------------------------------------------------------------------------------------
//CLEAR SEARCH INPUT
//------------------------------------------------------------------------------------------
function setFormTarget(elem="")
{
    if(elem.value =="pdf") $(".search_form").attr('target', '_blank')
    else $(".search_form").attr('target', '_self')
}