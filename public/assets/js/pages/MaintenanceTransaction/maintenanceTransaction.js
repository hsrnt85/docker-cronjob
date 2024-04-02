

//----------------------------------GAMBAR ADUAN ----------------------------------------------

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

 //----------------------------------GAMBAR PENYELENGGARAAN ------------------------------------

    function showImagesMaintenance(imgs) {

        var expandImg = document.getElementById("expandedImg1");
        var clickingText = document.getElementById("clickingText1");
        expandImg.src = imgs.src;
        expandImg.parentElement.style.display = "block";
        clickingText.style.display = "none";
        expandImg.style.width = '100%';
        expandImg.style.height = '300px';
        expandImg.style.border = "1px solid black";

    }

 //-------------------------------PARSLEY FILE FORMAT--------------------------------------------

    $(function () {

        window.Parsley.addValidator('fileextension', function (value, requirement) {
                var fileExtension = value.split('.').pop();
                var req = requirement.split('|');

                return req.includes(fileExtension);
            }, 32)
            .addMessage('en', 'fileextension', 'Fail ini tidak dibenarkan');

    });


//---------------------------------------DATE PICKER -----------------------------------------------

jQuery(document).ready(function ($) {
    // $("input[name='start_date']").datepicker({
    //     startDate: new Date(),
    // });

  });


//---------------------SHOW IMAGE BY ROW USING MAINTENANCE TRANSACTION ID-----------------------------

function showImgMaintenance(maintenance_transaction_id) {

    if(maintenance_transaction_id)
    {
        let route = $('#btn-show-maintenance-image').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'GET',
            url: route,
            data:{
                mt_id:maintenance_transaction_id,
                _token: _token
            },
            success:function(data){
                $('#maintenance-body').html(data.html);
                $('#view-image-maintenance-transaction').modal('show')
            },
            error: function(){ }
        });
    }
}

//-----------------------------SHOW IMAGE ADUAN BY ROW IN A MODAL ------------------------------------------------------------
function showInventoryDamageList(complaint_inventory_id) {

    if(complaint_inventory_id)
    {
        let route = $('#btn-show-inventory-damage').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'GET',
            url: route,
            data:{
                cid:complaint_inventory_id,
                _token: _token
            },
            success:function(data){
                $('#modal-body').html(data.html);
                $('#view-complaint-inventory-attachment').modal('show')
            },
            error: function(){ }
        });
    }
}

function showComplaintOthersList(complaint_others_id) {

    if(complaint_others_id)
    {
        let route = $('#btn-show-complaint-others').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'GET',
            url: route,
            data:{
                cod:complaint_others_id,
                _token: _token
            },
            success:function(data){
                $('#complaint-others-modal-body').html(data.html);
                $('#view-complaint-others-attachment').modal('show')
            },
            error: function(){ }
        });
    }
}

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

$(function() {
    checkTabs();
});
