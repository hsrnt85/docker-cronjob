(function ($) {

    //SWAL ADD DATA
    $(document).on("click", ".swal-tambah", function(e){
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
    });

    //SWAL UPDATE DATA
    $(document).on("click", ".swal-kemaskini", function(e){
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
    $(document).on("click", ".swal-delete-list", function(e){
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
                thisButton.closest("td").find("form").trigger("submit");
            }
        })
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

    //SWAL RESET - SEND EMAIL IN USER LIST
    $(document).on("click", ".swal-reset-email-list-user", function(e){

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
                let url = $('.swal-email-list-user').attr('href');
                $(location).attr('href',url);
            }
        })
    });

})(jQuery)
