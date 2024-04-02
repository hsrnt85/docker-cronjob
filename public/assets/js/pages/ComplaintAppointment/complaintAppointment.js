function myFunction(imgs, attachment_id) {
    // alert( attachment_id);
    $('#attachment_id').val(attachment_id);
    var expandImg = document.getElementById("expandedImg");
    var clickingText = document.getElementById("clickingText");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";

   }

   //view page
function showInventoryImage(imgs) {

    var expandImg = document.getElementById("expandedImg");
    var clickingText = document.getElementById("clickingText");
        expandImg.src = imgs.src;
        expandImg.parentElement.style.display = "block";
        clickingText.style.display = "none";
        expandImg.style.width = '100%';
        expandImg.style.height = '300px';
        expandImg.style.border = "1px solid black";
}

//view page
function showOtherComplaintImage(imgs) {

    var expandImg2 = document.getElementById("expandedImg2");
    var clickingText2 = document.getElementById("clickingText2");
        expandImg2.src = imgs.src;
        expandImg2.parentElement.style.display = "block";
        clickingText2.style.display = "none";
        expandImg2.style.width = '100%';
        expandImg2.style.height = '300px';
        expandImg2.style.border = "1px solid black";
}

//attachment_id - for delete purpose
function showImages(imgs, attachment_id) {

    $('#attachment_id').val(attachment_id);
    var expandImg = document.getElementById("expandedImg3");
    var clickingText = document.getElementById("clickingText");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
    clickingText.style.display = "none";
    expandImg.style.width = '100%';
    expandImg.style.height = '300px';
    expandImg.style.border = "1px solid black";

}

//Show Image By Row in modal --------------------------------------
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

$(document).on("change", "#appointment_date", function(e){
    // const moment = require('moment');
    let tarikh_temujanji      = this.value;

    if(tarikh_temujanji)
    {
        var wrapper =  $('.field_wrapper_listing').empty();

        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type:'GET',
                url: '/TemujanjiAduan/ajaxGetAppointmentList',
                data:{
                    tarikh_temujanji:tarikh_temujanji,
                    _token: _token
                }
        }).done(function(data, textStatus, jqXHR){

                var fieldHTML = '';
                var no_aduan = "";
                jQuery.each(data.data, function(i, val) {

                    no_aduan = val.ref_no;
                    tenant_name = val.name;
                    unit_no = val.unit_no;
                    address_1 = val.address_1;
                    address_2 = val.address_2;
                    address_3 = val.address_3;
                    appointment_time = val.appointment_time;

                    time = moment(appointment_time).format('h:mm A').format('LT');

                    var row_index = document.getElementById('table_appointment_list').rows.length;

                    fieldHTML = '<tr>';//start row
                    fieldHTML +='<td class="text-center" width="10%">'+row_index+'</td>';//bil
                    fieldHTML +='<td class="text-center" width="10%">'+no_aduan+'</td>';//bil
                    fieldHTML +='<td class="text-center" width="30%">'+tenant_name+'</td>';//bil
                    fieldHTML +='<td class="text-center">'+unit_no+', '+address_1+', '+address_2+', '+address_3+'</td>';//bil
                    fieldHTML +='<td class="text-center">'+time+'</td>';//bil
                    fieldHTML +='</tr>';

                    $(wrapper).append(fieldHTML);
                });

                if(!no_aduan)
                {
                    fieldHTML = '<tr>';
                    fieldHTML +='<td class="text-center" colspan="5">Tiada Rekod</td>';
                    fieldHTML +='</tr>';
                    $(wrapper).append(fieldHTML);
                }

        });
    }
});

jQuery(document).ready(function ($) {
    var wrapper =  $('.field_wrapper_listing');

    var fieldHTML = '';
    fieldHTML = '<tr>';
    fieldHTML +='<td class="text-center" colspan="5">Tiada Rekod</td>';
    fieldHTML +='</tr>';

    $(wrapper).append(fieldHTML);

});

    //SWAL BATAL TEMUJANJI ADUAN
    $(document).on("click", "#swal-cancel-appointment", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            text: "Anda pasti untuk batalkan temujanji ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            confirmButtonColor: "#485ec4",
            cancelButtonColor: "#74788d",
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    text: "Sebab temujanji dibatalkan ?",
                    icon: "warning",
                    input: "text",
                    showCancelButton: true,
                    confirmButtonText: "Hantar",
                    cancelButtonText: "Tidak",
                    confirmButtonColor: "#485ec4",
                    cancelButtonColor: "#74788d",
               }).then((result)=>{
                    if (result.isConfirmed) {
                        if (result.value === false) return false;
                        if (result.value  === "") {
                            swal.fire({text:"Sila masukkan sebab temujanji dibatalkan !",icon:"error"});
                            return false
                        }

                        swal.fire({
                            text: "Anda pasti untuk hantar ?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Hantar",
                            cancelButtonText: "Batal",
                            confirmButtonColor: "#485ec4",
                            cancelButtonColor: "#74788d",
                        }).then(function  () {
                            var valSebab = result.value;
                            if (result.isConfirmed) {
                                $('#officer_cancel_remarks').val(valSebab);
                                form.submit();
                            }

                        },function(dismiss) {
                            if (dismiss === 'cancel') {
                            } else {
                                throw dismiss;
                            }
                        });
                    }
                },function(dismiss) {
                    if (dismiss === 'cancel') {
                    } else {
                        throw dismiss;
                    }
                });

            }})


        });

 //------------------------------------DATE PICKER & CANNOT BACKDATED  -------------------------------------------

jQuery(document).ready(function ($) {

    // Get the current date
    var currentDate = new Date();

    // Set the start date to one day after the current date
    //currentDate.setDate(currentDate.getDate() + 1);
    // Set the start date to current date
    currentDate.setDate(currentDate.getDate());

    $("input[name='appointment_date']").datepicker({
        startDate: currentDate,
    });

  });

//------------------------------------------------------------------------------------------
//CHECK LAST ACTIVE TAB AFTER RETURN FROM VIEW PAGE
//------------------------------------------------------------------------------------------

  $(function() {
    checkTabs();
});

