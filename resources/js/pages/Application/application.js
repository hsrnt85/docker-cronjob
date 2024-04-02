
$(function(){

    $("#vertical-permohonan").steps(
        {
            headerTag:"h3",
            bodyTag:"section",
            transitionEffect:"slide",
            stepsOrientation:"vertical",
            labels: {
                cancel: "Kembali",
                current: "current step:",
                pagination: "Pagination",
                finish: "Simpan Draf",
                next: "Seterusnya",
                previous: "Sebelum",
                loading: "Loading ..."
            },
            onFinished: function (event, currentIndex) { 
                let thisButton = $(this);
                thisButton.closest("form").trigger("submit");
            },
        }
    )
});

//TAMBAH ANAK
$(document).on("click", "#tambah-anak", function(e){
    e.preventDefault();

    let yourhtml = `<tr>
                    <td class="text-center">-</td>
                    <td> <input type="text" class="form-control"></td>
                    <td> <input type="text" class="form-control"></td>
                    <td> <input type="file" class="form-control"></td>
                    <td class="text-center"><a class="btn btn-warning btn-sm delete-anak"><i class="mdi mdi-minus mdi-18px"></i></a></td>
                    </tr>`;
    
    $("#table-anak-list > tbody ").append(yourhtml);
});

//DELETE ANAK
$(document).on("click", ".delete-anak", function(e){
    e.preventDefault();
    
    $(this).closest("tr").remove();
});

