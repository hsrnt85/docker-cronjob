
$(document).ready(function () {

    jQuery('input:not(.not-uppercase)').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    jQuery('input:not(.not-uppercase)').blur(function() {
        $(this).val($(this).val().toUpperCase());
    });

    jQuery('textarea:not(.not-uppercase)').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    jQuery('textarea:not(.not-uppercase)').blur(function() {
        $(this).val($(this).val().toUpperCase());
    });

});
