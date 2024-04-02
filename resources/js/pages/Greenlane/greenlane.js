$(function(){
    $("#vertical-permohonan").steps(
         {
             headerTag:"h3",
             bodyTag:"section",
             transitionEffect:"slide",
             stepsOrientation:"vertical",
             labels: {
                 cancel: "Kembali",
                 current: "current step:",
                 pagination: "Pagination",
                 finish: "Simpan Draf",
                 next: "Seterusnya",
                 previous: "Sebelum",
                 loading: "Loading ..."
             },
             onInit: function () {
                 var $input = $('<li aria-hidden="false" aria-disabled="false"><a href="#" id="btn-step-kembali" onclick="history.back();" class="bg-secondary">Kembali</a></li>');
                 
                 $input.prependTo($('ul[aria-label=Pagination]'));
             },
             onFinished: function (event, currentIndex) { 
                 let thisButton = $(this);
                 // thisButton.closest("form").trigger("submit");
 
                 Swal.fire({
                     title: "Anda pasti",
                     text: "Anda pasti untuk simpan?",
                     icon: "warning",
                     showCancelButton: !0,
                     confirmButtonColor: "#485ec4",
                     cancelButtonColor: "#74788d",
                     confirmButtonText: "Simpan",
                     cancelButtonText: "Kembali"
                 }).then(function(result) {
                     // if confirm clicked....
                     if (result.value) 
                     { 
                         thisButton.closest('form').trigger("submit"); 
                     }
                 })
             },
         }
     );
 });

//TAMBAH ANAK
$(document).on("click", "#tambah-anak", function(e){
    e.preventDefault();

    let yourhtml = `<tr>
                    <td class="text-center">-</td>
                    <td> <input type="text" class="form-control"></td>
                    <td> <input type="text" class="form-control"></td>
                    <td> <input type="file" class="form-control"></td>
                    <td class="text-center"><a class="btn btn-warning btn-sm delete-anak"><i class="mdi mdi-minus mdi-18px"></i></a></td>
                    </tr>`;
    
    $("#table-anak-list > tbody ").append(yourhtml);
});

//DELETE ANAK
$(document).on("click", ".delete-anak", function(e){
    e.preventDefault();
    
    $(this).closest("tr").remove();
});

//Input mask
jQuery(function(){$(".input-mask").inputmask({removeMaskOnSubmit: true})});


$(document).ready(function(){
    $('.phone').inputmask('(999)-999-9999');
});

