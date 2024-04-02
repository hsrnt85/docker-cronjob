function showClassGrade(category_class_id){

    if(category_class_id)
    { 
        let route = $('#btn-show-complaint-others').attr('data-route');
        let _token = $('meta[name="csrf-token"]').attr('content');

        var wrapper =  $('.field_wrapper_listing_maklumat_sewa').empty();

        $.ajax({
            // async: false, 
            type:'GET',
            url: route,
            dataType:"json",
            data:{
                _token: _token,
                'category_class':category_class_id
            },
            success:function(data){
                var myData = data;
                if(myData.length > 0){
                    $.each(myData, function (keyData, valueData) {
                        grade_no             = valueData.grade_no;
                        services_type             = valueData.services_type;
                        rental_fee             = valueData.rental_fee;
    
                        var row_index = document.getElementById('maklumat-sewa-modal').rows.length;
    
                        var fieldHTML = '';
                        fieldHTML +='<tr id="row_'+row_index+'">';
                        fieldHTML +='<td class="text-center" scope="row">'+row_index+'</td>';
                        
                        fieldHTML +='<td class="text-center">'+grade_no+'</td>';
                        fieldHTML +='<td class="text-center">'+services_type+'</td>';
                        fieldHTML +='<td class="text-center">'+rental_fee+'</td>';
                        fieldHTML +='</tr>';
    
                        $(wrapper).append(fieldHTML); 
                        $('#view-maklumat-sewa').modal('show') 
                    });

                }else if(myData.length == 0){
                    var row_index = document.getElementById('maklumat-sewa-modal').rows.length;
                    var fieldHTML = '';
                    fieldHTML +='<tr id="row_'+row_index+'">';
                    fieldHTML +='<td class="text-center" scope="row" colspan="4">Tiada Rekod</td>'
                    fieldHTML +='</tr>';
    
                    $(wrapper).append(fieldHTML); 
                    $('#view-maklumat-sewa').modal('show') 
                }
            },async: false, 
        });
    }
}