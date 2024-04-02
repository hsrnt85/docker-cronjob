


//Get unit no
$(document).on("change", "select#address", function(e){
    let addr    = this.value;
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = this.dataset.route;

    let thisSelect = $(this);
    let container  = $('select.select_unit');

    if(addr)
    {
        if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        container.prop('disabled', true);

        $.ajax({
            url: url,
            type:"POST",
            data:{
                addr:addr,
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){

            let html = `<option class="" value="">-- Pilih Unit --</option>`;

            jQuery.each(data.data, function(i, val) {
                html += `<option class="" value="${val.id}">${val.unit_no}</option>`;
            });

            container.html(html);
	        container.prop('disabled', false);

        }).fail(function(jqXHR, textStatus, errorThrown){
            
        }).always(function(){
            if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
        });
    }
});


// parsley
$(function () {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
            var fileExtension = value.split('.').pop();
            var req = requirement.split('|');
            
            return req.includes(fileExtension);
        }, 32)
        .addMessage('en', 'fileextension', 'Fail ini tidak dibenarkan');

    
    window.Parsley.addValidator('maxFileSize', {
        validateString: function(_value, maxSize, parsleyInstance) {
        if (!window.FormData) {
            alert('You are making all developpers in the world cringe. Upgrade your browser!');
            return true;
        }
            var files = parsleyInstance.$element[0].files;
            return files.length != 1  || files[0].size <= maxSize * 1024;
        },
        requirementType: 'integer',
    });

	$('form#app-form').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;
		$('.bs-callout-info').toggleClass('hidden', !ok);
		$('.bs-callout-warning').toggleClass('hidden', ok);
	})
	.on('form:submit', function() {
		return true; 
	});
});

$(function () {
    // Get category
    let _token  = $('meta[name="csrf-token"]').attr('content');
    let url     = $('form#app-form').data('route-category');
    let container = $('select#quarters_category');

    $.ajax({
        url: url,
        type:"POST",
        data:{
            _token: _token
        }
    }).done(function(data, textStatus, jqXHR){

        let html = `<option class="" value="">-- Pilih Kuarters --</option>`;

        jQuery.each(data.data, function(i, val) {
            html += `<option class="" value="${val.id}">${val.name}</option>`;
        });

        container.html(html);
    })

    // Get address
    $(document).on("change", "select#quarters_category", function(e){
        let cat_id      = this.value;
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let thisSelect  = $(this);
        let url         = thisSelect.data('route-alamat');
        let alamatSelect = $('select#alamat');

        if(cat_id)
        {
            // if(!$("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
            alamatSelect.prop('disabled', true);

            $.ajax({
                url: url,
                type:"POST",
                data:{
                    cat_id:cat_id,
                    _token: _token
                }
            }).done(function(data, textStatus, jqXHR){

                let html = `<option class="" value="">-- Pilih Alamat --</option>`;

                jQuery.each(data.data, function(i, val) {
                    html += `<option class="" value="${val.address_1}">${val.address_1}</option>`;
                });

                alamatSelect.html(html);
                alamatSelect.prop('disabled', false);

            }).fail(function(jqXHR, textStatus, errorThrown){
                
            }).always(function(){
                if($("div.spinner-wrapper").hasClass("spinner-border")) $('div.spinner-wrapper').toggleClass("spinner-border");
            });
        }
    });
});
