
var table_gred_list = 'table-list-ispeks';

var regex = /(\d)/;// => id 0

// REMOVE NEW ROW /DISABLED EXISTING ROW -> IF quarters_class_grade ID >0
 $(document).on("click", ".btnRemove", function(e){

    var row_index = $(this).attr('data-row-index');
    var item_id = $("#item_id"+row_index).val();
    var kod_potongan = $("#kod_potongan"+row_index).val();

    $('#id_by_row').val(item_id);
    $('#kod_potongan').val(kod_potongan);

    swalDeleteRow();

 });
