
//VALIDATION ONKEYUP - PASSWORD
$("#msg-katalaluan-baru").css("display", "none");
//$("#msg-pengesahan-katalaluan").css("display", "none");

function validatePassword(inputName, msgName) {

    var inputValue = $("#"+inputName).val();
    var minlength = "minlength";
    var lowercase = "lowercase";
    var uppercase = "uppercase";
    var number = "number";
    var symbol = "symbol";
    var counterValid = 0;

    if(inputValue != ""){
        //$("#msg-katalaluan-baru").css("display", "none");
        $("#"+msgName).css("display", "block");
    }else{
        //$("#msg-katalaluan-baru").css("display", "block");
        $("#"+msgName).css("display", "none");
    }

    // Validate length
    if(inputValue.length >= 12) {
        if($("#"+minlength).hasClass("error")){
            $("#"+minlength).removeClass("error");
            $("#"+minlength).addClass("text-success");
        }
        counterValid++;
    } else {
        if($("#"+minlength).hasClass("text-success")){
            $("#"+minlength).removeClass("text-success");
            $("#"+minlength).addClass("error");
        }
        counterValid--;
    }
    //console.log(counterValid);
    // Validate lowercase lowercases
    var lowerCaseLetters = /[a-z]/g;
    if(inputValue.match(lowerCaseLetters)) {
        if($("#"+lowercase).hasClass("error")){
            $("#"+lowercase).removeClass("error");
            $("#"+lowercase).addClass("text-success");
        }
        counterValid++;
    } else {
        if($("#"+lowercase).hasClass("text-success")){
            $("#"+lowercase).removeClass("text-success");
            $("#"+lowercase).addClass("error");
        }
        counterValid--;
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if(inputValue.match(upperCaseLetters)) {
        if($("#"+uppercase).hasClass("error")){
            $("#"+uppercase).removeClass("error");
            $("#"+uppercase).addClass("text-success");
        }
        counterValid++;
    } else {
        if($("#"+uppercase).hasClass("text-success")){
            $("#"+uppercase).removeClass("text-success");
            $("#"+uppercase).addClass("error");
        }
        counterValid--;
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if(inputValue.match(numbers)) {
        if($("#"+number).hasClass("error")){
            $("#"+number).removeClass("error");
            $("#"+number).addClass("text-success");
        }
        counterValid++;
    } else {
        if($("#"+number).hasClass("text-success")){
            $("#"+number).removeClass("text-success");
            $("#"+number).addClass("error");
        }
        counterValid--;
    }

    // Validate symbol
    var symbols = /[!@#$%^&*_-]/g;
    if(inputValue.match(symbols)) {
        if($("#"+symbol).hasClass("error")){
            $("#"+symbol).removeClass("error");
            $("#"+symbol).addClass("text-success");
        }
        counterValid++;
    } else {
        if($("#"+symbol).hasClass("text-success")){
            $("#"+symbol).removeClass("text-success");
            $("#"+symbol).addClass("error");
        }
        counterValid--;
    }

    if(counterValid>0){
        $("#pengesahan_katalaluan").prop('disabled', false);
        //$("#msg-pengesahan-katalaluan").css("display", "block");
    }else{
        $("#pengesahan_katalaluan").prop('disabled', true);
        //$("#msg-pengesahan-katalaluan").css("display", "none");
    }

}


$(function() {

    swalErrorMsg();
    swalSuccessMsg();

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
});

