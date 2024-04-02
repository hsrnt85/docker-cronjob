$(document).on("change", "select#officer", function(e){
    let officer = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = $(this).attr('data-route');
    // let position  = this.value;

    if(officer)
    {
        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        $.ajax({
            url: url,
            type:"POST", // use POST method to send data to the server-side script
            data:{
                officer:officer,
                _token: _token
            }
        }).done(function(result, textStatus, jqXHR){

            if(result!=undefined){
                $('#position').val(result.position);
            }

        }).fail(function(jqXHR, textStatus, errorThrown){

        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");

        });
    }
});
