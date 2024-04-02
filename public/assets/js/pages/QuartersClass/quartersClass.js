
$(document).ready(function() {

    let row_index = 0;
    if ($('#p_grade_id'+row_index+' option').length == 1) {
        senaraiGredJawatan(row_index,"");
    }
    if($('#services_type_id'+row_index+' option').length == 1) {
        senaraiKategoriPemohon(row_index,"");
    }

    $('#table-gred-list').attr('data-list');
    if($('#table-gred-list').attr('data-list') == 0){
        $('#tr-gred-list0').hide();
    }

});

var table_gred_list = 'table-gred-list';

var regex = /(\d)/;// => id 0

//FUNCTION DUPLICATE ROW
function duplicateRow(){

    //GET ROW INDEX
    var row_index = $('#'+table_gred_list).find('tbody>tr:visible').length;//alert(row_index);
    if(row_index==0){
        $('#tr-gred-list0').show();
        $('#tr-gred-list0').children('td').css('background-color','#d9ffdb');
    }else{
        row_index--;
        var tr_gred_list = "#tr-gred-list"+row_index;

        //FIND ROW
        var $tr    = $(tr_gred_list).closest(tr_gred_list);
        //SET CLONE PARAM
        var $clone = $tr.clone(true);
        row_index++;

        //CLEAR/RESET CLONE INPUT VALUE/ATTRIBUTE
        $clone.find(':text').val('');
        $clone.find('select').prop('selectedIndex',0);
        $clone.find('input[type=hidden]').attr('value','');
        $clone.closest('tr').children('td').css('background-color','#d9ffdb');
        //UPDATE ROW ID
        $clone.attr('id', 'tr-gred-list'+row_index);
        //UPDATE ELEMENT ID IN ROW
        $clone.find('select, input, a').each(function(){
            var input_id = this.id ;
            //var match = id.match(regex) || [];
            if(input_id.length){
                $(this).attr('id',$(this).attr('id')?.replace(regex, row_index));
                $(this).attr('data-row-index', row_index);
            }
        });

        //DUPLICATE ROW
        $tr.after($clone);

    }

}

// REMOVE NEW ROW /DISABLED EXISTING ROW -> IF quarters_class_grade ID >0
 $(document).on("click", ".btnRemove", function(e){

    var row_index = $(this).attr('data-row-index');
    var id_quarters_class_grade = $("#id_quarters_class_grade"+row_index).val();

    if(id_quarters_class_grade>0){

        $('#id_by_row').val(id_quarters_class_grade);

        swalDeleteRow();

    }else{
        var row_index = $('#'+table_gred_list).find('tr').length-2;
        if(row_index==0){
            $('#tr-gred-list0').hide();
        }else{
            $(this).closest("tr").remove();
            $("#"+table_gred_list).find('tbody tr').each(function(row_index) {
                //UPDATE ROW INDEXING ID/NAME/CLASS
                $(this).attr('id', 'tr-gred-list'+row_index);
                updateElementCounter($(this), row_index);
            });
        }
    }
 });

//--------------------------------------------------------------------------------------------------------------------------------
// UPDATE ELEMENT INDEXING BY ATTRIBUTE ID/NAME
//--------------------------------------------------------------------------------------------------------------------------------
function updateElementCounter(section, row_index){
    //UPDATE ELEMENT ID/NAME/CLASS IN ROW
    section.find('select, input, a').each(function(){
        let input_id = this.id ;
        if(input_id != undefined && input_id.length){
            $(this).attr('id',$(this).attr('id')?.replace(regex, row_index));
            $(this).attr('data-row-index', row_index);
        }
    });
}

//--------------------------------------------------------------------------------------
//AJAX SENARAI
//--------------------------------------------------------------------------------------
function senaraiGredJawatan(row_index,id){

    $.ajax({
        url:'/KelasKuarters/ajaxSenaraiGred' ,
        type:"GET",
        dataType:"json",
        success:function(data){

            var optionValue = "";
            var optionText = "";

            $.each(data, function(key, value){

                optionValue = value.id;
                optionText = value.grade_no;

                var fieldHTML = "";
                fieldHTML += '<option value="'+optionValue+'"';
                if(optionValue==id) fieldHTML += ' selected';

                fieldHTML += '>'+optionText+'</option>';

                $('#p_grade_id'+row_index).append(fieldHTML);

            });
        },
            error: function(){
        }
    });
}

function senaraiKategoriPemohon(row_index,id){
    $.ajax({
        url:'/KelasKuarters/ajaxSenaraiKategoriPemohon' ,
        type:"GET",
        dataType:"json",
        success:function(data){

            var optionValue = "";
            var optionText = "";

            $.each(data, function(key, value){

                optionValue = value.id;
                optionText = value.services_type;

                var fieldHTML = "";
                fieldHTML += '<option value="'+optionValue+'"';
                if(optionValue==id) fieldHTML += ' selected';

                fieldHTML += '>'+optionText+'</option>';

                $('#services_type_id'+row_index).append(fieldHTML);

            });
        },
            error: function(){
        }
    });
}

