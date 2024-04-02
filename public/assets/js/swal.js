(function ($) {

    var $selector = $('#form');
    var $selectorReview = $('#form-review');
    //SWAL ADD DATA
    $(document).on("click", ".swal-tambah", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);
            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk simpan?",
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
        }else{
            scrollToTop();
        }

        e.preventDefault();
    });

    //Swal tambah for Complaint Monitoring
    $(document).on("click", ".swal-menambah", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);

            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk simpan?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#485ec4",
                cancelButtonColor: "#74788d",
                confirmButtonText: "Simpan",
                cancelButtonText: "Kembali"
            }).then(function(result) {
                // if confirm clicked....
                if (result.value)
                {
                    // document.getElementById('form').submit();
                    thisButton.closest('form').trigger("submit");
                }
            })
        }

        e.preventDefault();
    });

    $(document).on("click", ".swal-tambahkuarters", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);
            e.preventDefault();
            Swal.fire({
                title: "Anda pasti untuk simpan?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#485ec4",
                cancelButtonColor: "#74788d",
                confirmButtonText: "Simpan",
                cancelButtonText: "Kembali"
            }).then(function(result) {
                // if confirm clicked....
                if (result.value)
                {
                    thisButton.closest('form').trigger("submit");
                }
            })
        }
        e.preventDefault();
    });

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-kemaskini", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);

            e.preventDefault();

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
                    //check if exist input btn_submit
                    //kemaskini penilaian mesyuarat
                    if($('#btn_submit').length) $('#btn_submit').val('');
                    //submit form
                    thisButton.closest('form').trigger("submit");
                }
            })
        }else{
            scrollToTop();
        }

        e.preventDefault();
    });

    $(document).on("click", ".swal-mengemaskini", function(e){
        let thisButton = $(this);

        e.preventDefault();

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

    //SWAL UPDATE DATA WITHOUT SCROLL TO TOP & VALIDATE PARSLEY
    $(document).on("click", ".swal-kemaskini-parsley", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);

            e.preventDefault();

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
                    //submit form
                    thisButton.closest('form').trigger("submit");
                }
            })
        }

        e.preventDefault();
    });

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-sah-penilaian", function(e){

        form = $selector.parsley();
        form.validate();
        
        // Checkbox is required
        $('tbody[id^="application_data"]').find('input[type="checkbox"]:not(:disabled)').prop('required', true);
        // console.log($('tbody[id^="application_data"]').find('input[type="checkbox"]'));

        if (form.isValid()) {

            let thisButton = $(this);

            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk Sahkan Penilaian Permohonan?",
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
                    $('#btn_submit').val('sah');
                    thisButton.closest('form').trigger("submit");
                }
            })
        }else{
            scrollToTop();
        }

        e.preventDefault();
    });

    //SWAL DELETE DATA
    $(document).on("click", ".swal-delete", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk hapus?",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#c35555",
            cancelButtonColor: "#74788d",
            confirmButtonText: "Ya",
            cancelButtonText: "Tutup"
        }).then(function(result) {
            // if confirm clicked....
            if (result.value)
            {
                $('#delete-form').trigger("submit"); // submit form
            }
        })
    });

    //SWAL DELETE FROM LIST
    // $(document).on("click", ".swal-delete-list", function(e){
    //     let thisButton = $(this);

    //     e.preventDefault();

    //     Swal.fire({
    //         title: "Anda pasti untuk hapus?",
    //         icon: "warning",
    //         showCancelButton: !0,
    //         confirmButtonColor: "#c35555",
    //         cancelButtonColor: "#74788d",
    //         confirmButtonText: "Ya",
    //         cancelButtonText: "Tutup"
    //     }).then(function(result) {
    //         // if confirm clicked....
    //         if (result.value)
    //         {
    //             thisButton.closest("td").find("form").trigger("submit");
    //         }
    //     })
    // });

     //SWAL DELETE FROM LIST
     $(document).on("click", ".swal-delete-list", function(e){
        let thisButton = $(this);
        let _token  = $('meta[name="csrf-token"]').attr('content');
        let url = thisButton.attr('data-route');
        let id = thisButton.attr('data-id');
        let dataCount = 0;
        if(url!=undefined || url != null){
            
            $.ajax({
                url: url,
                type:"GET",
                dataType: "json",
                data:{
                    _token:_token,
                    id:id
                },
                success: function (data) {
                    dataCount = data;
                }

            }).done(function(){

                if (dataCount > 0 ) {
                    
                    Swal.fire({
                        title: "Rekod tidak boleh dihapus kerana telah digunakan oleh transaksi lain !",
                        icon: "warning",
                        confirmButtonColor: "#485ec4",
                        confirmButtonText: "OK",
                    });
                    
                }else{

                    Swal.fire({
                        title: "Anda pasti untuk hapus?",
                        icon: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#c35555",
                        cancelButtonColor: "#74788d",
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tutup"
                    }).then(function(result) {
                        // if confirm clicked....
                        if (result.value)
                        {
                            thisButton.closest("td").find("form").trigger("submit");
                        }
                    })
                }
                    
            });
            
        }else{
            Swal.fire({
                title: "Anda pasti untuk hapus?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#c35555",
                cancelButtonColor: "#74788d",
                confirmButtonText: "Ya",
                cancelButtonText: "Tutup"
            }).then(function(result) {
                // if confirm clicked....
                if (result.value)
                {
                    thisButton.closest("td").find("form").trigger("submit");
                }
            })
        }
      
    });

    //SWAL APPROVE DATA
    $(document).on("click", ".swal-approve", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk sahkan rekod ini?",
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

     //SWAL PROCESS FROM LIST
     $(document).on("click", ".swal-process-notice-list", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk proses notis bayaran?",
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
                //thisButton.closest("td").find("form.process-notice-form-list").trigger("submit");
                thisButton.closest('form').trigger("submit");
            }
        })
    });

    //SWAL RESET - SEND EMAIL IN USER LIST
    $(document).on("click", ".swal-reset-email-list-user", function(e){
        let thisButton = $(this);
        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk set semula kata laluan pengguna?",
            text: "Emel pautan pengesahan akan dihantar kepada pengguna untuk set semula kata laluan.",
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
                let url = thisButton.closest('a').attr('href');//;alert(url);
                $(location).attr('href',url);
            }
        })
    });

    // Hantar Permohonan Greenlane
    $(document).on("click", ".swal-hantar", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk hantar?",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#c35555",
            cancelButtonColor: "#74788d",
            confirmButtonText: "Hantar",
            cancelButtonText: "Kembali"
        }).then(function(result) {
            // if confirm clicked....
            if (result.value)
            {
                //$('#send-form').trigger("submit"); // submit form
                thisButton.closest('form').trigger("submit");
            }
        })
    });

    // Hantar Permohonan Greenlane
    $(document).on("click", ".swal-review-hantar", function(e){

        form = $selectorReview.parsley();
        form.validate();

        if (form.isValid()) {
            let thisButton = $(this);

            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk hantar?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#c35555",
                cancelButtonColor: "#74788d",
                confirmButtonText: "Hantar",
                cancelButtonText: "Kembali"
            }).then(function(result) {

                // if confirm clicked....
                if (result.value)
                {
                    //$('#send-form').trigger("submit"); // submit form
                    $('#form-review').trigger("submit");
                }
            })
        }

        e.preventDefault();
    });

    //SWAL hantar // validate first > swal
    $(document).on("click", ".swal-validate-hantar", function(e){

        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);
            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk hantar?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#c35555",
                cancelButtonColor: "#74788d",
                confirmButtonText: "Hantar",
                cancelButtonText: "Kembali"
            }).then(function(result) {
                // if confirm clicked....
                if (result.value)
                {
                    document.getElementById('btn_type_input').value = 'hantar'; //penyata pemungut & jurnal - edit
                    thisButton.closest('form').trigger("submit");

                }
            })
        }

        e.preventDefault();
    });

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-update-email-surat", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk kemaskini dan emel surat?",
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

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-cetak-surat", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk cetak surat?",
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
                let url = $('.swal-cetak-surat').attr('href');
                thisButton.attr("target","_blank");
                $(location).attr('href',url);
            }
        })
    });

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-email-surat", function(e){
        let thisButton = $(this);

        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk emel surat?",
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
                let url = $('.swal-email-surat').attr('href');
                $(location).attr('href',url);
            }
        })
    });

     //SWAL PENGESAHAN KELUAR KUARTERS
    $(document).on("click", ".swal-pengesahan-keluar", function(e){
  
        form = $selector.parsley();
        form.validate();

        if (form.isValid()) {

            let thisButton = $(this);

            e.preventDefault();

            Swal.fire({
                title: "Anda pasti untuk membuat pengesahan?",
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
                    //submit form
                    thisButton.closest('form').trigger("submit");
                }
            })
        }

        e.preventDefault();
    });


})(jQuery)


function swalDeleteRow(){

    Swal.fire({
        title: "Anda pasti untuk hapus?",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#c35555",
        cancelButtonColor: "#74788d",
        confirmButtonText: "Ya",
        cancelButtonText: "Tutup"
    }).then(function(result) {
        // if confirm clicked....
        if (result.value)
        {
            $('#delete-form-by-row').trigger("submit"); // submit form
        }
    })

}

function swalDeleteScoring(flag, formId){

    var label = "";
    if(flag == 'c') label = "kriteria";
    else label = "kenyataan";
    Swal.fire({
        title: "Anda pasti untuk hapus "+ label +"?",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#c35555",
        cancelButtonColor: "#74788d",
        confirmButtonText: "Ya",
        cancelButtonText: "Tutup"
    }).then(function(result) {
        // if confirm clicked....
        if (result.value)
        {
            $('#'+formId).trigger("submit"); // submit form
        }
    })

}

function swalDeleteScoring(flag, formId){

    var label = "";
    if(flag == 'c') label = "kriteria";
    else label = "kenyataan";
    Swal.fire({
        title: "Anda pasti untuk hapus "+ label +"?",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#c35555",
        cancelButtonColor: "#74788d",
        confirmButtonText: "Ya",
        cancelButtonText: "Tutup"
    }).then(function(result) {
        // if confirm clicked....
        if (result.value)
        {
            $('#'+formId).trigger("submit"); // submit form
        }
    })

}

function scrollToTop() {
    window.scrollTo(0, 0);
}

// Global swal for generic actions 22-Nov-2022
$(function() {

    swalErrorMsg();
    swalSuccessMsg();
    swalErrorPermissionMsg();

    function swalErrorMsg() {
        if ($("input[name='error']").length) {

            let msg = $("input[name='error']").val();

            Swal.fire({
                icon: 'error',
                title: '<h5 class="text-danger">Tidak Berjaya</h5>',
                text: msg
            });
        }
    }

    function swalSuccessMsg() {
        if ($("input[name='success']").length) {

            let msg = $("input[name='success']").val();

            Swal.fire({
                icon: 'success',
                title: '<h5 class="text-success">Berjaya</h5>',
                text: msg
            });
        }
    }

    function swalErrorPermissionMsg() {
        if ($("input[name='error-permission']").length) {

            let msg = $("input[name='error-permission']").val();

            Swal.fire({
                icon: 'error',
                title: '<h5 class="text-danger">Tidak Berjaya</h5>',
                text: msg
            });
        }
    }

});



