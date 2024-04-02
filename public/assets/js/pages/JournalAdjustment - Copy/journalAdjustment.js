//--------------------------------------------------------------------------------------------------------------------------------
// FUNCTION DUPLICATE ROW
//--------------------------------------------------------------------------------------------------------------------------------
var table_vot_akaun = 'table-vot-akaun';

var regex_id = /(\d)/;// => id 0
let regex_name = /\[\d+](?!.*\[\d)/;// => name[0][0]

function duplicateRow(){

        //GET ROW INDEX
        var row_index = $('#'+table_vot_akaun).find('tbody>tr:visible').length;

        $("#row_index").val(row_index) ;

        if(row_index==0){
            $('#tbl-row-0').show();
            $('#tbl-row-0').children('td').css('background-color','#d9ffdb');
        }else{
            
            row_index--;
            var row_index_prev = row_index;

            var table_row = "#tbl-row-"+row_index;
            //FIND ROW
            var $tr    = $(table_row).closest(table_row);
            //SET CLONE PARAM

 
            var $clone = $tr.clone(true);
            row_index++;
            //CLEAR/RESET CLONE INPUT VALUE/ATTRIBUTE
            
            // $clone.removeAttr('data-select2-id');
            // $clone.attr('data-select2-id', "tbl-row-"+row_index);
            // $clone.closest('tr').children('td:eq(0)').find('select').attr('data-select2-id', "income_code"+row_index);
                      
            $clone.find(':text').val('');
            $clone.find('input[type=hidden]').attr('value','');

            $clone.closest('tr').children('td').css('background-color','#d9ffdb');
            //UPDATE ROW ID
            $clone.attr('id', 'tbl-row-'+row_index);
            //UPDATE ELEMENT ID IN ROW
            $clone.find('input, select').each(function(){
                var input_id = this.id ;
                //var match = id.match(regex) || [];
                if(input_id.length){
                    $(this).attr('id',$(this).attr('id')?.replace(regex_id, row_index));
                    $(this).attr('data-row-index', row_index); 
                }
            });

            //CUSTOM REPLACE ID w/o regex - SELECT2
            // $clone.find('span').each(function(){
            //     if($(this).attr('id')=='select2-income_code'+ row_index_prev +'-container'){
            //         $(this).attr('id',"select2-income_code"+ row_index +"-container");
            //     }
            //     if($(this).attr('aria-labelledby')=='select2-income_code'+ row_index_prev +'-container'){
            //         $(this).attr('aria-labelledby',"select2-income_code"+ row_index +"-container");
            //     }
            // });

            //FOR CLONING - SELECT2
            $clone.closest('tr').children('td').find(".select2:last").remove();
            $clone.closest('tr').children('td').find("select").select2({ width:'100%' });

            //DUPLICATE ROW
            $tr.after($clone);

            $(".select2-account").select2();//enable all row - select2
          
        }
    }
//--------------------------------------------------------------------------------------------------------------------------------
//REMOVE NEW ROW /DISABLED EXISTING ROW -> IF complaint_others_id ID >0
//--------------------------------------------------------------------------------------------------------------------------------

$(document).on("click", ".btnRemove", function(e) {

    var row_index = $(this).attr('data-row-index'); 
    var income_code_id = $("#income_code_id"+row_index).val();

    //  PAGE EDIT
    if(income_code_id>0){

        var row = $('#row_complaint_others_id').val(income_code_id);

        //REMOVE VALIDATION AFTER ROW HAS BEEN REMOVED
        row.attr("required",false);

        swalDeleteRow();

    }else{
        var row_index = $('#'+table_vot_akaun).find('tr').length-2;
        if(row_index==0){
            $('#tbl-row-0').hide();
        }else{
            $(this).closest("tr").remove();
            $("#"+table_vot_akaun).find('tbody tr').each(function(row_index) {

                //UPDATE ROW INDEXING ID/NAME/CLASS
                $(this).attr('id', 'tbl-row-'+row_index);
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
    section.find('select, input').each(function(){
        let input_id = this.id ; 
        if(input_id != undefined && input_id.length){
            $(this).attr('id',$(this).attr('id')?.replace(regex_id, row_index));
            $(this).attr('data-row-index', row_index);
        }
    });
}

//--------------------------------------------------------------------------------------------------------------------------------
// INPUT MASK
//--------------------------------------------------------------------------------------------------------------------------------
jQuery(function(){$(".input-mask").inputmask()});
