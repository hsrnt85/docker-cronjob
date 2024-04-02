

$( document ).ready(function() {

    $("#menus").val(0);
    $("#section_hidden").hide();

});

function showSection(elem, subView, menu_id) {

    //HIDE ALL SUBMODULE
    $('.tabContent').hide();

    //SHOW SELECTED SUBMODULE
    $('.'+subView+menu_id).show();

    if(!$(elem).hasClass('active'))
    {
       $('.btn-sidemenu').removeClass('active');
    }
    $(elem).addClass('btn-sidemenu active');

}

function setCounter() {

    let counter = 0;
    $('.abilities').each(function(){
        if(this.checked) counter++;
    });
    if(counter>0){
        $("#menus").attr("disabled", true);
        $("#menus").attr("required", false);
        $("#menus").addClass("parsley-success");
    }else{
        $("#menus").attr("disabled", false);
        $("#menus").attr("required", true);
    }
    $("#menus").val(counter);

}

