
//----------------------------PEMANTAUAN BARU ADUAN KEROSAKAN ---------------------------------------

$(document).ready(function() {

    $('.complaint_inventory_check').attr("required",false);
    $('.complaint_others_check').attr("required",false);

    //PEMANTAUAN KELUAR
    $(".quantity").each(function(index){
        $(this).attr("disabled",true);
        $(this).attr("required",false);

    });
    $(".condition").each(function(index){
        $(this).attr("disabled",true);
        $(this).attr("required",false);

    });

});


$(document).on("change", ".complaint_status", function(e){

    var inventory_check =  $('.complaint_inventory_check');
    var others_check =  $('.complaint_others_check');
    var remarks =  $('#remarks');
    var file =  $('#monitoring_file');
    var rejected_reason =  $('#rejected_reason');

    // STATUS ADUAN : SELESAI DAN PERLU DISELENGGARA  -------------------------------------------------------- //

    if ($(this).val() == 3 ){

        //---------------------- DISABLE -----------------------

        // ULASAN PENOLAKAN
        HideRejectedReason();
        rejected_reason.attr('required', false);
        rejected_reason.parsley().destroy();

        //---------------------- ENABLE -----------------------

        // CHECKBOX ADUAN
        inventory_check.attr("disabled",false);
        others_check.attr("disabled",false);
        inventory_check.attr("required",true);
        others_check.attr("required",true);

        // TRANSAKSI PEMANTAUAN
        remarks.attr('required', true);
        remarks.attr('disabled', false);
        file.attr('disabled', false);

        // VALIDATE PARSLEY
        if(inventory_check.val() == undefined  )
        {
            others_check.each(function () {
                $(this).parsley().validate();
            });
            remarks.parsley().validate();
        }
        else{
            inventory_check.each(function () {
                $(this).parsley().validate();
            });
            remarks.parsley().validate();
        }

    }


    // STATUS ADUAN : DITOLAK ------------------------------------------------------------------------------- //

    else if ($(this).val() == 2 ){

        //---------------------- DISABLE -----------------------

        // CHECKBOX ADUAN
        inventory_check.attr("disabled",true);
        others_check.attr("disabled",true);
        inventory_check.removeAttr('required');
        others_check.removeAttr('required');

        // TRANSAKSI PEMANTAUAN
        remarks.removeAttr('required');
        remarks.attr('required', false);
        remarks.attr('disabled', true);
        remarks.val('');
        file.attr('disabled', true);

        // UNCHECK CHECKBOX ADUAN
        inventory_check.prop("checked", false);
        others_check.prop("checked", false);

        // DESTROY PARSLEY
        if(inventory_check.val() == undefined  )
        {
            others_check.each(function () {
                $(this).parsley().destroy();
            });

            remarks.parsley().destroy()
        }
        else{
            inventory_check.each(function () {
                $(this).parsley().destroy();
            });

            remarks.parsley().destroy()
        }

        //---------------------- ENABLE -----------------------

        // ULASAN PENOLAKAN
        ShowRejectedReason();
        rejected_reason.attr('required', true);
        rejected_reason.parsley().validate();

    }
});

$(document).on("change", ".monitoring_status", function(e){ // edit page

    var rejected_reason =  $('#rejected_reason');

    if ($(this).val() == 4 ) // ditolak
    {
        ShowRejectedReason();
        rejected_reason.attr('required', true);
        rejected_reason.parsley().validate();
    }
    else
    {
        HideRejectedReason();
        rejected_reason.attr('required', false);
        rejected_reason.parsley().destroy();
    }
});

//------------------------------- PEMANTAUAN KELUAR--------------------------------------------------------

    //ON CHANGE CHECK RADIO status_inventory
    $(document).on("change", ".inventory_status", function(e){

        var tqi = $(this).attr('data-tqi-id'); //tenants quarters inventory id
        var inventory_status = $(this).val();
        var quantity = $('.quantity_'+tqi);
        var condition = $('.condition_'+tqi);
        
        if ($(this).prop('checked')){
        
            if(inventory_status == 2){ //2: Tiada
                quantity.attr("disabled",true);
                quantity.attr("required",false);
                condition.attr("disabled",true);
                condition.attr("required",false);

                //clear input
                quantity.val('');
                condition.prop('checked', false);

            }else{ //1: Ada
                quantity.attr("disabled",false);
                quantity.attr("required",true);
                condition.attr("disabled",false);
                condition.attr("required",true);
            }
        }
    });

//-------------------------------GAMBAR PEMANTAUAN --------------------------------------------------------

// gambar pemantauan awam
function showImagesAwam(imgs) {

    var expandImg = document.getElementById("expandedImg_A");
    var clickingText = document.getElementById("clickingText_A");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
   }

//gambar pemantauan aduan kerosakan
function showImagesMonitoringKerosakan(imgs) {

    var expandImg = document.getElementById("expandedImg_K");
    var clickingText = document.getElementById("clickingText_K");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
   }

//gambar pemantauan pertama (aduan awam)
function showImagesMonitoringAwam1(imgs) {

    var expandImg = document.getElementById("expandedImg_A1");
    var clickingText = document.getElementById("clickingText_A1");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
    }

//gambar pemantauan kedua
function showImagesMonitoringAwam2(imgs) {

    var expandImg = document.getElementById("expandedImg_A2");
    var clickingText = document.getElementById("clickingText_A2");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
   }

   //gambar pemantauan ketiga
   function showImagesMonitoringAwam3(imgs) {

    var expandImg = document.getElementById("expandedImg_A3");
    var clickingText = document.getElementById("clickingText_A3");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";
   }


//Show Image By Row in modal -------------------------------------------------------------------

function showInventoryDamageList(complaint_inventory_id,flag_page) {

    if(complaint_inventory_id)
    {
        let route = $('#btn-show-inventory-damage').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'GET',
            url: route,
            data:{
                cid:complaint_inventory_id,
                flag:flag_page,
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

function showComplaintOthersList(complaint_others_id,flag_page) {

    if(complaint_others_id)
    {
        let route = $('#btn-show-complaint-others').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:'GET',
            url: route,
            data:{
                cod:complaint_others_id,
                flag:flag_page,
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

// Parsley file ----------------------------------------------------------
$(function () {

    window.Parsley.addValidator('fileextension', function (value, requirement) {
            var fileExtension = value.split('.').pop();
            var req = requirement.split('|');

            return req.includes(fileExtension);
        }, 32)
        .addMessage('en', 'fileextension', 'Fail ini tidak dibenarkan');

        window.Parsley.addValidator('maxFileSize', {
            validateString: function(_value, maxSize, parsleyInstance) {
            if (!window.FormData) {
                alert('You are making all developpers in the world cringe. Upgrade your browser!');
                return true;
            }
                var files = parsleyInstance.$element[0].files;
                return files.length != 1  || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
        });


    // $('form#form').parsley().on('field:validated', function() {
    //     var ok = $('.parsley-error').length === 0;
    //     $('.bs-callout-info').toggleClass('hidden', !ok);
    //     $('.bs-callout-warning').toggleClass('hidden', ok);
    // })
    // .on('form:submit', function() {
    //     return true;
    // });
});


//------------------------------- HIDE AND SHOW ULASAN PENOLAKAN ---------------------------------------------

function ShowRejectedReason() {

    var rejected_reason = document.getElementById("rejected_div");

    rejected_reason.style.display = "block";  // <-- Set it to block
}

function HideRejectedReason() {

    var rejected_reason = document.getElementById("rejected_div");

    rejected_reason.style.display = "none";  // <-- Hide
}

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

$(function() {
    checkTabs();
});


