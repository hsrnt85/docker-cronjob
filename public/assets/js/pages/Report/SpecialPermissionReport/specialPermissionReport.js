$(document).ready(function() {
    // Disable "No. Kad Pengenalan" and "Nama" fields when "Carian Daerah" is filled
    $('#carian_daerah').on('input', function() {
        if ($(this).val() !== '') {
            $('#new_ic, #name').prop('disabled', true);
        } else {
            $('#new_ic, #name').prop('disabled', false);
        }
    });

    // Disable "Carian Daerah" and "Nama" when "No. Kad Pengenalan" field is filled
    $('#new_ic').on('input', function() {
        if ($(this).val() !== '') {
            $('#carian_daerah, #name').prop('disabled', true);
        } else {
            $('#carian_daerah, #name').prop('disabled', false);
        }
    });

    // Disable "Carian Daerah" and "No. Kad Pengenalan" when "Nama" field is filled
    $('#name').on('input', function() {
        if ($(this).val() !== '') {
            $('#new_ic, #carian_daerah').prop('disabled', true);
        } else {
            $('#new_ic, #carian_daerah').prop('disabled', false);
        }
    });

});
