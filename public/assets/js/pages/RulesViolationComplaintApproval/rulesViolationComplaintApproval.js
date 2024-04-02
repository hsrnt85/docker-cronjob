function showImages(imgs) {

    var expandImg = document.getElementById("expandedImg");
    var clickingText = document.getElementById("clickingText");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
}

function ShowHideReason(flag)
{

    if(flag == 2)
    {
        $('#section_rejection_reason').show();
        $('#rejection_reason').attr("disabled",false);
        $('#rejection_reason').attr('required',true);

    }else
    {

        $('#section_rejection_reason').hide();
        $('#rejection_reason').attr("disabled",true);
        $('#rejection_reason').attr('required',false);
    }
}

//ON CHANGE CHECK RADIO complaint_status
$(document).on("click", ".complaint_status", function(e){

    if ($(this).is(':checked') && $(this).val() == 2)

    {
        ShowHideReason(2);
    }
});


//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

$(function() {
    checkTabs();
});


