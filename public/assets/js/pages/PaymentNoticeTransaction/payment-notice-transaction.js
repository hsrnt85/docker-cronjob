$(document).ready(function () {
   
    //SWAL PROCESS FROM LIST
    $(document).on("click", ".swal-process-notice-list", function(e){
       
        e.preventDefault();

        Swal.fire({
            title: "Anda pasti untuk proses notis bayaran individu?",
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
                e.preventDefault();
                $('.process-notice-form-list').submit(function (e) {
                    var formData = new FormData(this);
                    alert(formData);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            
                            xhr.upload.addEventListener('progress', function (e) {
                                if (e.lengthComputable) {
                                    var percent = Math.round((e.loaded / e.total) * 100);
                                    $('.progressBar').css('width', percent + '%').text(percent + '%');
                                }
                            }, false);
                            
                            return xhr;
                        },
                        success: function (response) {
                            // Handle the success response
                        },
                        error: function (xhr, status, error) {
                            // Handle the error response
                        }
                    });
                });

            }
        })
    });
});
