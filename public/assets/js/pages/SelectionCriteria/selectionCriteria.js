//Kriteria Pemarkahan Dependencies
$(document).on("change", "select#category", function(e){
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
            $('select#criteria').prop( "disabled", false );
            if($("div#criteria-feedback").hasClass("d-block")) $('div#user-feedback').toggleClass( "d-block" );

            let html = `<option value="">-- Pilih Kriteria --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option value="${val.id}">${val.criteria}</option>`;
            });

            $('select#criteria').html(html);

        }).fail(function(jqXHR, textStatus, errorThrown){
            // console.log(errorThrown);
            // console.log(textStatus);
            // console.log(jqXHR);
            console.log(jqXHR.responseJSON);
            // console.log(jqXHR.responseJSON.error);

            let html = `<option value="">-- Pilih pegawai --</option>`;

            if(jqXHR.responseJSON.error)
            {
                $('select#criteria').html(html);
                $('select#criteria').prop( "disabled", true );

                $('div#criteria-feedback').html(jqXHR.responseJSON.error);
                if(!$("div#criteria-feedback").hasClass("d-block")) $('div#criteria-feedback').toggleClass( "d-block" );
            }

    
        }).always(function(){
            
            if($("div#user-loading").hasClass("spinner-border")) $('div#user-loading').toggleClass("spinner-border");

        });
    }
});