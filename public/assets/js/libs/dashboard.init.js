setTimeout(function () {
    $("#subscribeModal").modal("show");
}, 2e3);

$(function() {

    // STATISTIK BILANGAN KUARTERS
    getKuartersBaik();
    getKuartersSelenggaraRosak();
    // STATISTIK PENGHUNI KUARTERS
    getPenghuniSemasa();
    getKuartersKosong();
    // STATISTIK PENYELENGGARAAN
    getKuartersSelenggara();
    // STATISTIK ADUAN AWAM
    getAduanAwam();
    // STATISTIK ADUAN KEROSAKAN
    getAduanKerosakan();
    
    //-------------------------------------------------------------------------------------------------------------------------------------
    function getKuartersBaik() {

        let divTotal    = $('div#bil-kuarters-jumlah');
        let container   = $('div#kuarters-boleh-diduduki');
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let condition   = container.data('condition');
        let url         = container.data('route');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
                condition_id:condition
            }
        }).done(function(data, textStatus, jqXHR){
            let valBaik = data.data; 
            let view = container.find('h4');
            view.html(data.data);

            let valSelenggaraRosak = parseInt($('#val-tidak-boleh-diduduki').html());
           
            let total = valBaik + valSelenggaraRosak;
            let viewTotal = divTotal.find('h4');
            viewTotal.html(total);

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    }
    
    function getKuartersSelenggaraRosak() {

        let divSelenggaraRosak = $('div#kuarters-tidak-boleh-diduduki');
        let divTotal    = $('div#bil-kuarters-jumlah');
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let flag        = divSelenggaraRosak.attr('flag');
        let url         = divSelenggaraRosak.data('route');
        
        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
                flag:flag
            }
        }).done(function(data, textStatus, jqXHR){
            let valSelenggaraRosak = data.data; 
            let view = divSelenggaraRosak.find('h4');
            view.html(valSelenggaraRosak);

            let valBaik = parseInt($('#val-boleh-diduduki').html());
        
            let total = valBaik + valSelenggaraRosak;
            let viewTotal = divTotal.find('h4');
            viewTotal.html(total);

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    }

    function getKuartersSelenggara() {

        let container   = $('div#kuarters-selenggara');
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let url         = container.attr('data-route');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token
            }
        }).done(function(data, textStatus, jqXHR){

            data = data.data;
            let i=1;
            $.each( data, function( key, value ) {
                let subcontainer   = $('div#kuarters-selenggara-'+i); i++;
                let view = subcontainer.find('h4');
                view.html(value);
            });

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    }

    // function getKuartersRosak() {

    //     let container   = $('div#kuarters-rosak');
    //     let _token      = $('meta[name="csrf-token"]').attr('content');
    //     let condition   = container.data('condition');
    //     let url         = container.data('route');

    //     $.ajax({
    //         url: url,
    //         type: "POST",
    //         data:{
    //             _token: _token,
    //             condition_id:condition
    //         }
    //     }).done(function(data, textStatus, jqXHR){

    //        let view = container.find('h4');
    //        view.html(data.data);

    //     }).fail(function(jqXHR, textStatus, errorThrown){

    //         console.log(jqXHR.responseJSON);

    //     }).always(function(){

    //     });
    // }

    function getKuartersKosong() {

        let container   = $('div#kosong');
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let url         = container.data('route');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
            }
        }).done(function(data, textStatus, jqXHR){

            let valKuartersBolehDiduduki = parseInt(data.data);
            let valBerpenghuni = parseInt($('#val-berpenghuni').html());
            let bal = valKuartersBolehDiduduki - valBerpenghuni;
            $('#val-tidak-berpenghuni').html(bal);
            
        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    }

    function getPenghuniSemasa() {

        let container   = $('div#berpenghuni');
        let _token      = $('meta[name="csrf-token"]').attr('content');
        let url         = container.data('route');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
            }
        }).done(function(data, textStatus, jqXHR){

           let view = container.find('h4');
           view.html(data.data);

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    }

    // function getKuartersJumlah() {
        
    //     let container   = $('div#kuarters-jumlah');
    //     let _token      = $('meta[name="csrf-token"]').attr('content');
    //     let condition   = container.data('condition');
    //     let url         = container.data('route');
        
    //     $.ajax({
    //         url: url,
    //         type: "POST",
    //         data:{
    //             _token: _token,
    //             condition_id:condition
    //         }
    //     }).done(function(data, textStatus, jqXHR){

    //        let view = container.find('h4');
    //        view.html(data.data);

    //     }).fail(function(jqXHR, textStatus, errorThrown){

    //         console.log(jqXHR.responseJSON);

    //     }).always(function(){

    //     });
    // }

    //-----------------------------------------------------------------------------------------------------------------------------------------
    //ADUAN AWAM
    //-----------------------------------------------------------------------------------------------------------------------------------------
    function getAduanAwam() {

        let container   = $('div#aduan-awam');
        let complaint_type  = container.attr('data-complaint-type');
        let url = container.attr('data-route');
        let _token      = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
                complaint_type : complaint_type
            }
        }).done(function(data, textStatus, jqXHR){
            data = data.data;
            for(let i=1; i<=4; i++){
                let subcontainer   = $('div#aduan-awam-'+i);
                let view = subcontainer.find('h4');
                if(i==1) view.html(data.new);
                else if(i==2) view.html(data.in_action);
                else if(i==3) view.html(data.rejected);
                else if(i==4) view.html(data.done);
                $('div#aduan-awam-jumlah').find('h4').html(data.total);
            }

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
    
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------
    //ADUAN KEROSAKAN
    //-----------------------------------------------------------------------------------------------------------------------------------------
    function getAduanKerosakan() {
        
        let container   = $('div#aduan-kerosakan');
        let complaint_type  = container.attr('data-complaint-type');
        let url = container.attr('data-route');
        let _token      = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: _token,
                complaint_type : complaint_type
            }
        }).done(function(data, textStatus, jqXHR){
            data = data.data;
            for(let i=1; i<=4; i++){
                let subcontainer   = $('div#aduan-kerosakan-'+i);
                let view = subcontainer.find('h4');
                if(i==1) view.html(data.new);
                else if(i==2) view.html(data.in_action);
                else if(i==3) view.html(data.rejected);
                else if(i==4) view.html(data.done);
                $('div#aduan-kerosakan-jumlah').find('h4').html(data.total);
            }

        }).fail(function(jqXHR, textStatus, errorThrown){

            console.log(jqXHR.responseJSON);

        }).always(function(){

        });
        
    }

});

