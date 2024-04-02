//Ajax Autofill data after insert ic
function autofill_user(){
    var new_ic = $("#new_ic").val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    if(new_ic){
        $.ajax({
            url: '/KebenaranKhas/ajaxCheckIcUser',
            type    : 'POST',
            data    : {new_ic:new_ic,
                    _token: _token},
            dataType: 'json',
            success:function(data){

                if(data.user_id == null )
                {
                    $('#new_ic_error').html('<span class="text-danger">Rekod pengguna sistem tidak wujud. Sila daftar sebagai pengguna sistem terlebih dahulu!</span>');
                    $('#new_ic').removeClass('has-error');
                    $('#btn-submit').attr('disabled', false);

                    $("#name").val('');
                    $("#user_id").val('');
                    $("#email").val('');
                    $("#position").val('');
                    $("#position_type").val('');
                    $("#position_grade").val('');
                    $("#organization").val('');
                    $("#services_type").val('');
                    $("#district").val('');
                }
                else if(data.special_user_id == null )
                {
                    $('#new_ic_error').html('');
                    $('#new_ic').removeClass('has-error');
                    $('#btn-submit').attr('disabled', false);

                    var new_ic = $("#new_ic").val();
                    let _token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/KebenaranKhas/ajaxGetField',
                        type    : 'POST',
                        data    : {new_ic:new_ic,
                                _token: _token},
                        dataType: 'json',
                        success:function(data){

                            $("#name").val(data.name);
                            $("#user_id").val(data.id);
                            $("#email").val(data.email);
                            $("#position").val(data.user_info_position_name ? data.user_info_position_name : data.position_name);
                            $("#position_type").val(data.user_info_grade_type ? data.user_info_grade_type : data.grade_type);
                            $("#position_grade").val(data.user_info_grade_no ? data.user_info_grade_no : data.grade_no);
                            $("#organization").val(data.organization_name);
                            $("#services_type").val(data.user_info_services_type ? data.user_info_services_type : data.services_type);
                            $("#district").val(data.district_name);
                        }
                    })
                }
                else
                {
                    $('#new_ic_error').html('<span class="text-danger">No. Kad Pengenalan (Baru) ini telah didaftarkan dalam Kebenaran Khas!</span>');
                    $('#new_ic').addClass('has-error');
                    $('#btn-submit').attr('disabled', 'disabled');

                    $("#name").val("");
                    $("#user_id").val("");
                    $("#email").val("");
                    $("#position").val("");
                    $("#position_type").val("");
                    $("#position_grade").val("");
                    $("#organization").val("");
                    $("#services_type").val("");
                    $("#district").val("");
                }
            }
        })
    }else{
        $('#new_ic_error').html('');
        $('#new_ic').removeClass('has-error');
        $('#btn-submit').attr('disabled', false);
    }
}

//Delete Row Supporting Document ---------------------------------------

var table_supporting_document = 'table-supporting-document';

//FUNCTION DUPLICATE ROW
var regex = /(\d)/;// => id 0
// var regex = /^(.*)(\d)+$/i;

function duplicateRow(){

        //GET ROW INDEX
        var row_index = $('#'+table_supporting_document).find('tbody>tr:visible').length;

        if(row_index==0){
            $('#tr-supporting-document0').show();
            $('#tr-supporting-document0').children('td').css('background-color','#d9ffdb');
        }else{
            row_index--;
            var tr_supporting_document = "#tr-supporting-document"+row_index;

            //FIND ROW
            var $tr    = $(tr_supporting_document).closest(tr_supporting_document);
            //SET CLONE PARAM
            var $clone = $tr.clone(true);
            row_index++;

            //CLEAR/RESET CLONE INPUT VALUE/ATTRIBUTE
            $clone.find(':text').val('');
            $clone.find('input[type=hidden]').attr('value','');
            $clone.closest('tr').children('td').css('background-color','#d9ffdb');
            $clone.find('input[type=file]').val('');
            $clone.find('label').html('');
            $clone.closest('tr').children('td').children('div').html('');
            //UPDATE ROW ID
            $clone.attr('id', 'tr-supporting-document'+row_index);
            //UPDATE ELEMENT ID IN ROW
            $clone.find('input, a').each(function(){
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

//REMOVE NEW ROW /DISABLED EXISTING ROW -> IF complaint_others_id ID >0
$(document).on("click", ".btnRemove", function(e) {

    var row_index = $(this).attr('data-row-index'); //alert(row_index)
    var supporting_document_id = $("#supporting_document_id"+row_index).val();

    if(supporting_document_id>0){

        $('#row_supporting_document_id').val(supporting_document_id);

        swalDeleteRow();

    }else{
        var row_index = $('#'+table_supporting_document).find('tr').length-2;
        if(row_index==0){
            $('#tr-supporting-document0').hide();
        }else{
            $(this).closest("tr").remove();
            $("#"+table_supporting_document).find('tbody tr').each(function(row_index) {
                //UPDATE ROW INDEXING ID/NAME/CLASS
                $(this).attr('id', 'tr-supporting-document'+row_index);
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
    section.find('input, a').each(function(){
        let input_id = this.id ;
        if(input_id != undefined && input_id.length){
            $(this).attr('id',$(this).attr('id')?.replace(regex, row_index));
            $(this).attr('data-row-index', row_index);
        }
    });
}


