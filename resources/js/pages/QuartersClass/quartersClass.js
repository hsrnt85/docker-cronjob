
$(document).ready(function() {

    let row_index = 0;
    if ($('#p_grade_id'+row_index+' option').length == 1) {
        senaraiGredJawatan(row_index,"");
    }
    if($('#services_type_id'+row_index+' option').length == 1) {
        senaraiKategoriPemohon(row_index,"");
    }

    var index = 0;
    var tr_gred_list = "#tr-gred-list"+index ;
    var $tr = $(tr_gred_list).closest(tr_gred_list);
    $tr.find('#btnRemove'+index ).hide();

});

var table_gred_list = 'table-gred-list';

//FUNCTION DUPLICATE ROW
var regex = /^(.*)(\d)+$/i;

function duplicateRow(p_grade_id=0, services_type_id=0){

    //GET ROW INDEX
    var row_index = $('#'+table_gred_list).find('tr').length-2;
    var tr_gred_list = "#tr-gred-list"+row_index;

    //FIND ROW
    var $tr    = $(tr_gred_list).closest(tr_gred_list);
    //SET CLONE PARAM
    var $clone = $tr.clone(true);
    row_index++;
    //SHOW BUTTON REMOVE
    $clone.find('#btnRemove0:hidden').toggle();
    //CLEAR/RESET CLONE INPUT VALUE/ATTRIBUTE
    $clone.find(':text').val('');
    $clone.find('select').prop('selectedIndex',0);
    $clone.find('input[type=hidden]').attr('value','');
    $clone.closest('tr').children('td').css('background-color','#ffffff');
    $clone.find('select:disabled').removeAttr('disabled');
    $clone.find('input:disabled').removeAttr('disabled');
    //UPDATE ROW ID
    $clone.attr('id', 'tr-gred-list'+row_index);
    //UPDATE ELEMENT ID IN ROW
    $clone.find('select, input, a').each(function(){
        var id = this.id ;
        var match = id.match(regex) || [];
        if (match.length == 3) {
            this.id = match[1]+(row_index);
        }
    });

    //DUPLICATE ROW
    $tr.after($clone);

}

//REMOVE NEW ROW /DISABLED EXISTING ROW -> IF quarters_class_grade ID >0
$(document).on("click", ".btnRemove", function(e){

    var row_index = $('#'+table_gred_list).find('tr').length-2;
    var id_quarters_class_grade = $("#id_quarters_class_grade"+row_index).val();
    var flag_proses = Number($("#flag_proses"+row_index).val());

    if(id_quarters_class_grade>0){
        if(flag_proses == 0){
            $(e.target).closest('tr').children('td').css('background-color','#FFE8E4');
            $("#p_grade_id"+row_index).prop('disabled', true);
            $("#services_type_id"+row_index).prop('disabled', true);
            $("#rental_fee"+row_index).prop('disabled', true);
            $("#flag_proses"+row_index).val('1');
        }else if(flag_proses > 0){
            $(e.target).closest('tr').children('td').css('background-color','#ffffff');
            $("#p_grade_id"+row_index).removeAttr('disabled');
            $("#services_type_id"+row_index).removeAttr('disabled');
            $("#rental_fee"+row_index).removeAttr('disabled');
            $("#flag_proses"+row_index).val('0');
        }
    }else{
        $(this).closest("tr").remove();
    }

});

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
