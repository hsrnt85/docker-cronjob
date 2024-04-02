$(document).on("change", "select#district", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    // let _token  = "{{ csrf_token() }}";
    let url     = this.dataset.route;

    if(id)
    {
        if(!$("div#user-loading").hasClass("spinner-border")) $('div#user-loading').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST",
            data:{
                id:id,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){
            $('select#user').prop( "disabled", false );
            if($("div#user-feedback").hasClass("d-block")) $('div#user-feedback').toggleClass( "d-block" );

            let html = `<option value="">-- Pilih pegawai --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option value="${val.id}">${val.name}</option>`;
            });

            $('select#user').html(html);

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

            let html = `<option value="">-- Pilih pegawai --</option>`;

            if(jqXHR.responseJSON.error)
            {
                $('select#user').html(html);
                $('select#user').prop( "disabled", true );

                $('div#user-feedback').html(jqXHR.responseJSON.error);
                if(!$("div#user-feedback").hasClass("d-block")) $('div#user-feedback').toggleClass( "d-block" );
            }

            $('#jawatan').val("");
            $('#jabatan').val("");
            $('#alamat1').val("");
            $('#alamat2').val("");
            $('#alamat3').val("");
            $('#no_tel').val("");
            $('#email').val("");

        }).always(function(){

            if($("div#user-loading").hasClass("spinner-border")) $('div#user-loading').toggleClass("spinner-border");

        });
    }
});

$(document).on("change", "select#user", function(e){
    let id      = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    // let _token  = "{{ csrf_token() }}";
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
        }).done(function(response, textStatus, jqXHR){

            (response.data.jawatan) ? $('#jawatan').val(response.data.jawatan) : $('#jawatan').val("");
            (response.data.jabatan) ? $('#jabatan').val(response.data.jabatan) : $('#jabatan').val("");
            (response.data.alamat1)? $('#alamat1').val(response.data.alamat1) : $('#alamat1').val("");
            (response.data.alamat2) ? $('#alamat2').val(response.data.alamat2) : $('#alamat2').val("");
            (response.data.alamat3) ? $('#alamat3').val(response.data.alamat3) : $('#alamat3').val("");
            (response.data.no_tel) ? $('#no_tel').val(response.data.no_tel) : $('#no_tel').val("");
            (response.data.email) ?$('#email').val(response.data.email) : $('#email').val("");

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseJSON);
        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        });
    }
});
