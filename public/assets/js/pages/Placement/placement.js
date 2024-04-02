$(document).on("change", "select#quarters_category", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = this.dataset.route;

    if(id)
    {
        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST",
            data:{
                id:id,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){
            $('input#address1').val(data.data[0].address_1);
            $('input#address2').val(data.data[0].address_2);
            $('input#address3').val(data.data[0].address_3);

            let html = `<option value="">-- Pilih Unit --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option value="${val.id}">${val.unit_no}</option>`;
            });

            $('select#quarters').html(html);

        }).fail(function(jqXHR, textStatus, errorThrown){
            
        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        });
    }
});


//SWAL NOTIFICATION
$(document).on("click", "#swal-notification", function(e){
    let thisButton = $(this);

    e.preventDefault();

    Swal.fire({
        title: "Anda pasti untuk beri notifikasi?",
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
            $('#trigger-noti').trigger("submit");
        }
    })
});

//Get unit no
$(document).on("change", "select#address", function(e){
    let addr    = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = $(this).attr('data-route');
    let category_id = $(this).attr('data-category-id');

    let thisSelect = $(this);
    let container  = thisSelect.closest('tr').find('select.select_unit');

    if(addr)
    {
        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        container.prop('disabled', true);

        $.ajax({
            url: url,
            type:"POST",
            data:{
                category_id:category_id,
                addr:addr,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){

            let html = `<option class="text-center" value="">-- Pilih Unit --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option class="text-center" value="${val.id}">${val.unit_no}</option>`;
            });

            container.html(html);
	        container.prop('disabled', false);

        }).fail(function(jqXHR, textStatus, errorThrown){
            
        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        });
    }
});

//-------------------------------------------------------------------------------------------------------------------------------
let selectedUnitArrTemp = [];

$(document).on("change", "select#unit_no", function(e){
 
    let row_index = $(this).attr('data-index');
    let selectedUnit  = $(this).val();

    //check RowIndex 
    const isRowIndexExist = selectedUnitArrTemp.some(unitArr => unitArr.rowIndex === row_index);
    if(isRowIndexExist) {
        selectedUnitArrTemp= selectedUnitArrTemp.filter(function( obj ) {
            return obj.rowIndex !== row_index;
        });
    } 

    const isUnitExist = selectedUnitArrTemp.some(unitArr => unitArr.unit === selectedUnit);
    if(isUnitExist) {
        //swal
        Swal.fire({
            title: "No. unit ini telah dipilih. Sila pilih no. unit yang lain.",
            icon: "error",
            confirmButtonColor: "#485ec4",
            confirmButtonText: "OK",
        })

        $(this).prop('selectedIndex',0);
    }else {
        selectedUnitArrTemp.push({
            rowIndex: row_index, 
            unit:  selectedUnit
        });
    } 

});
//-------------------------------------------------------------------------------------------------------------------------------