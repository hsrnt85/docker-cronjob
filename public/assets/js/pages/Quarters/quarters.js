

$(document).ready(function() {

    //-------------------------------------------------------------------------------------------------------
    //DATATABLE
    var table = $('#datatable_quarters').DataTable( {
        "language": {
            "lengthMenu": "Papar _MENU_ rekod per mukasurat",
            "search": "Carian:",
            "zeroRecords": "",
            "info": "Papar mukasurat _PAGE_ dari _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(Carian dari _MAX_ jumlah rekod)",
            "emptyTable": "Tiada rekod",
            "paginate": {
                "first": "Mula",
                "last": "Tamat",
                "next": ">",
                "previous": "<"
            },
        },
        orderCellsTop: true,
        fixedHeader: true,

    } );

    $('#datatable_quarters thead tr').clone(true).appendTo( '#datatable_quarters thead' );
    $('#datatable_quarters thead tr:eq(1) th').each( function (i) {
        $(this).removeClass("sorting sorting_asc");
        $(this).removeAttr("aria-label");
        var noSearchingArr = [0, 4, 5, 6];
        //if ( i!=0 && i!=4 && i!=5 && i!=6 ) {
        if(jQuery.inArray(i, noSearchingArr) === -1) {
            var inputSearch = '<input type="text" class="search"/>';
            $(this).html(inputSearch);

            $('input', this).on('change', function() {
                if ( table.column(i).search() !== this.value ) {
                    table.column(i).search( this.value ).draw();
                }
            }).on('keyup', function (e) {
                e.stopPropagation();
                var cursorPosition = this.selectionStart;
                $(this).trigger('change');
                $(this)
                    .focus()[0]
                    .setSelectionRange(cursorPosition, cursorPosition);
            });

        }else if(i==4){
            var selectSearch = '<select id="search_'+i+'" class="search"></select>';
            $(this).html(selectSearch);
            $('#search_'+i).append('<option value="">--Semua--</option>');
            $('#search_'+i).append('<option value="1">Aktif</option>');
            $('#search_'+i).append('<option value="2">Tidak Aktif</option>');

            $('select', this).on('change', function(i) {
                var selectVal = this.value ;
                // If selected records should be displayed
                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push(
                    function (settings, data, dataIndex) {
                        var row = table.row(dataIndex).node();
                        var checked = $(row).find('#data_status_' + dataIndex).prop('checked');
                        if (checked && selectVal != 2) { return true; }//CHECK IF ACTIVE
                        if (!checked && selectVal == 2) { return true; }//CHECK IF NOT ACTIVE
                        if (selectVal != "") { return false; }else{ return true; }//FALSE - HIDE DATA NOT IN CASE 1/2
                    }
                );

                table.draw();
            });

            //table.draw();

        }else{
            $(this).html('&nbsp;');
        }

    });

    $(document).on("click", ".swal-kemaskini-quarters-list", function(e){
        let thisButton = $(this);

        e.preventDefault();

        table.page.len( -1 ).draw();

        Swal.fire({
            title: "Anda pasti untuk kemaskini?",
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
                thisButton.closest('form').trigger("submit");
            }
        })
    });
    //-------------------------------------------------------------------------------------------------------

    //DISABLED responsibility
    $('.responsibility').attr("disabled",true);
    $('.qty').attr("disabled",true);

    //IWK FEE
    if($('#iwk').prop('checked')==true){
        if($('#iwk_fee_temp').val()>0) $('#iwk_fee').val($('#iwk_fee_temp').val());
        $('#iwk_fee').attr("disabled",false);
    }else{
        $('#iwk_fee').val('0.00');
        $('#iwk_fee').attr("disabled",true);
    }

    //CHECK ON STRATA - MAINTENANCE FEE
    checkLandedType($('#landed_type_id').val());

    //ON LOAD CHECK status_inventory
    $(".status_inventory").each(function(index){
        var inventory_id = $(this).attr('data-inventory-id');

        if ($(this).prop('checked')==true){
            $('.responsibility_'+inventory_id).attr("disabled",false);
            $('.responsibility_'+inventory_id).attr("required",true);
            $('.quantity_'+inventory_id).attr("disabled",false);
            $('.quantity_'+inventory_id).attr("required",true);

        }else{
            //$('.responsibility_'+inventory_id).prop("checked",false);
            $('.responsibility_'+inventory_id).attr("disabled",true);
            $('.responsibility_'+inventory_id).attr("required",false);
            $('.quantity_'+inventory_id).attr("disabled",true);
            $('.quantity_'+inventory_id).attr("required",false);
        }
    });
    //ON CHANGE CHECK status_inventory
    $(document).on("change", ".status_inventory", function(e){
        var inventory_id = $(this).attr('data-inventory-id');

        if ($(this).prop('checked')==true){
            $('.responsibility_'+inventory_id).attr("disabled",false);
            $('.responsibility_'+inventory_id).attr("required",true);
            $('.quantity_'+inventory_id).attr("disabled",false);
            $('.quantity_'+inventory_id).attr("required",true);

        }else{
            //$('.responsibility_'+inventory_id).prop("checked",false);
            $('.responsibility_'+inventory_id).attr("disabled",true);
            $('.responsibility_'+inventory_id).attr("required",false);
            $('.quantity_'+inventory_id).attr("disabled",true);
            $('.quantity_'+inventory_id).attr("required",false);

        }
    });

});

function allowQuantity(id){
    if($('#formCheck_'+id).is(':checked')){
        $('#quantity_'+id).attr("disabled",false);
    }else{
        $('#quantity_'+id).val('');
        $('#quantity_'+id).attr("disabled",true);
    }
}

$(document).on("change", "#iwk", function(e){
    //CHECK ON CHANGE IWK
    if($(this).is(':checked')){
        $('#iwk_fee').val($('#iwk_fee_temp').val());
        $('#iwk_fee').attr("disabled",false);
    }else{
        $('#iwk_fee').val('0.00');
        $('#iwk_fee').attr("disabled",true);
    }

});

$(document).on("change", "select#district", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = this.dataset.route;

    if(id)
    {
        if(!$("div#quarters-category-loading").hasClass("spinner-border")) $('div#quarters-category-loading').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST",
            data:{
                id:id,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){
            $('select#quarters_category').prop( "disabled", false );
            if($("div#quarters-category-feedback").hasClass("d-block")) $('div#quarters-category-feedback').toggleClass( "d-block" );

            let html = `<option value="">-- Pilih Kategori Kuarters (Lokasi)--</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option value="${val.id}">${val.name}</option>`;
            });

            $('select#quarters_category').html(html);

        }).fail(function(jqXHR, textStatus, errorThrown){

            let html = `<option value="">-- Tiada Rekod --</option>`;

            if(jqXHR.responseJSON.error)
            {
                $('select#quarters_category').html(html);
                $('select#quarters_category').prop( "disabled", true );

                $('div#quarters-category-feedback').html(jqXHR.responseJSON.error);
                if(!$("div#quarters-category-feedback").hasClass("d-block")) $('div#quarters-category-feedback').toggleClass( "d-block" );
            }


        }).always(function(){

            if($("div#quarters-category-loading").hasClass("spinner-border")) $('div#quarters-category-loading').toggleClass("spinner-border");

        });
    }
});

$(document).on("change", "select#quarters_category", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = this.dataset.route;

    $('#landed_type').val('');

    if(id)
    {
        if(!$("div#quarters-category-data-loading").hasClass("spinner-border")) $('div#quarters-category-data-loading').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST",
            data:{
                id:id,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){

            jQuery.each(data, function(i, val) {
                $('#landed_type').val(val.landed_type);
                //CHECK ON STRATA - MAINTENANCE FEE
                checkLandedType(val.landed_type_id);
            });

        }).fail(function(jqXHR, textStatus, errorThrown){

        }).always(function(){

            if($("div#quarters-category-data-loading").hasClass("spinner-border")) $('div#quarters-category-data-loading').toggleClass("spinner-border");

        });
    }
});

 //CHECK ON STRATA - MAINTENANCE FEE
function checkLandedType(landed_type_id){
    // if(landed_type_id==2){
    //     $('#maintenance_fee').attr("disabled",false);
    // }else{
    //     $('#maintenance_fee').val('0.00');
    //     $('#maintenance_fee').attr("disabled",true);
    // }
}


$(document).ready(function() {

    $('.container').hide();

});

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

//function to enable / disable catatan input field
function enableCatatan(quarters_id, bil) {
    if (document.getElementById("data_status_"+bil).checked) {
        //enable catatan input field
        document.getElementById("inactive_remarks_"+bil).disabled = true;
        document.getElementById("data_status_"+bil).value= 1;

    } else {
        //disable catatan input field
        document.getElementById("inactive_remarks_"+bil).disabled = false;
        document.getElementById("data_status_"+bil).value= 2;

    }
  }

  var elem_select_all_application = "select_all_application";
  $(document).on("click", "#"+elem_select_all_application, function(e){

    let flag_checked_all = this.checked;
    var elem_status = "data_status";
    var elem_remarks = "inactive_remarks";
    let dt = $('#datatable').DataTable();

    if(flag_checked_all){
        $('.'+elem_status).prop('checked', true);
        $('.'+elem_status).value = 2;
        $('.'+elem_remarks).prop('disabled', false);

        $('.data_status').each(function(i){
            let data_status = $(this).val();
            if(data_status=='1'){
                document.getElementById("data_status_"+i).value= 2;
            }
        });

    }else{
        $('.data_status_temp').each(function(i){
            let data_status_temp = $(this).val();

            if(data_status_temp=='1'){
                $('#'+elem_status+'_'+i).prop('checked', false);
                $('#'+elem_remarks+'_'+i).prop('disabled', true);
                $('#'+elem_remarks+'_'+i).val('');
                document.getElementById("data_status_"+i).value= 1;
            }
        });
    }

  });

