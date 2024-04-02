

$(document).ready(function() {

    $('.full_mark, #final_full_mark, #final_total_mark').prop('readonly', true);

});
let full_mark = 0;
$('.full_mark').each(function(){

    let i = $(this).attr('data-row-index');

    if(i == 0){
        //$(this).css("border","2px solid #34c38f");
        //$(this).css("font-weight", "bold");
        if(!isNaN($(this).val()) && $(this).val() != ''){
            val = parseInt($(this).val());
            full_mark += val;
        }
    }else{
        $(this).css("border","");
        $(this).css("font-weight", "normal");
    }

    $('#final_full_mark').val(full_mark);

});
$('.mark').keyup(function () {

    var val = 0;
    var total_mark = 0;
      //CHECK MARK NOT EXCEED FULL MARK
    $('.mark').each(function(){
        if($(this).val()=='') $(this).val(0);
        val = parseInt($(this).val());
        let i_criteria = $(this).attr('data-criteria-index');
        let i_subcriteria = $(this).attr('data-subcriteria-index');
        let full_mark = $('#full_mark_'+i_criteria+'_'+i_subcriteria).val();
        if( val > full_mark ){
            $(this).val(0);
            $(this).focus();
        }else{
            if(!isNaN($(this).val()) && $(this).val() != ''){
                val = parseInt($(this).val());
                total_mark += parseInt(val);
            }
        }
    });

    final_total_mark = parseInt(total_mark);
    $('#final_total_mark').val(total_mark);

});

//SWAL UPDATE MARKAH PENILAIAN
$(document).on("click", ".swal-kemaskini-markah", function(e){

    e.preventDefault();

    Swal.fire({
        title: "Anda pasti untuk kemaskini markah ini?",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#485ec4",
        cancelButtonColor: "#74788d",
        confirmButtonText: "Ya",
        cancelButtonText: "Tutup"
    }).then(function(result) {
        // if confirm clicked....
        if (result.value)
        {
            //kemaskini penilaian mesyuarat
            //submit form
            $('#form-edit-application-scoring').trigger("submit");

        }
    })

});

//SUBMIT BACKEND
$(document).ready(function() {
    $(document).on('submit', '#form-edit-application-scoring', function(e) {

        let formData = $(this).serialize();

        e.preventDefault();
        $.ajax({
            url: "/MesyuaratPenilaian/KemaskiniMarkahPermohonan",
            type: "GET",
            data: formData,
            success: function(data){
                if(data){
                    Swal.fire({
                        icon: 'success',
                        text: 'Markah permohonan telah dikemaskini',
                        confirmButtonColor: "#485ec4",
                        confirmButtonText: 'OK',
                    }).then(function (result) {
                        if (result.value)
                        {
                            location.reload();
                        }
                    });
                }else{
                    Swal.fire({
                        icon: 'info',
                        text: 'Markah permohonan tidak dikemaskini',
                        confirmButtonColor: "#485ec4",
                        confirmButtonText: 'OK',
                    }).then(function (result) {
                        if (result.value)
                        {
                            location.reload();
                        }
                    });
                }

            },
            error: function(){

            }
        });

     });
});

//--------------------------------------------------------------------------------------------------------------------------------
// ON LOAD PAGE - REORDER MARK - ONLY FOR CLASS section-auto-dropdown 
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function() {

    $('.subcriteria_name').each(function(){
   
        let i_criteria = $(this).attr('data-criteria-index');
        let flag_auto_dropdown = $(this).attr('data-flag-auto-dropdown');

        if(flag_auto_dropdown == 1){
            let rowsSubCriteria = $('.subcriteria_name_row_'+i_criteria).sort(function(a, b) {
              
                let ap = $(a).find('.full_mark');
                let bp = $(b).find('.full_mark');
                if (ap.length && bp.length) {
                    return bp.val() - ap.val();
                }
            });  

            $(this).append(rowsSubCriteria);

        }
       
    });

});