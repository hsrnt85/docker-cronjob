$(document).ready(function() {

    //section-officer-category

    $(".officer_group").each(function(){
        let flag_checked = this.checked;

        if ($(this).val()==2){
            if(flag_checked){
                $('.section-officer-category').show();
                $('.officer_category').attr("disabled",false);
                $('.officer_category').attr("required",true);
            }
            else{
                $('.section-officer-category').hide();
                $('.officer_category').attr("disabled",true);
                $('.officer_category').attr("required",false);
            }
        }
        else{
            $('.section-officer-category').hide();
            $('.officer_category').attr("disabled",true);
            $('.officer_category').attr("required",false);
        }
    });

    $(".officer_category").each(function(){
        let flag_checked = this.checked;
        if($(this).val()==4){
            if(flag_checked){
                $('#monitoring_district').attr("disabled",false);
            }
            else{
                $('#monitoring_district').attr("disabled",true);
                $('#monitoring_district').prop("checked",false);
            }
        }

    });

    //ON CHANGE CHECK status_inventory
    $(document).on("change", "#officer_group", function(e){

        if ($(this).val()==2){
            $('.section-officer-category').show();
            $('.officer_category').attr("disabled",false);
            $('.officer_category').attr("required",true);
        }
        else{
            $('.section-officer-category').hide();
            $('.officer_category').attr("disabled",true);
            $('.officer_category').attr("required",false);
        }

    });

     //ON CHANGE CHECK status_inventory
     $(document).on("change", ".officer_category", function(e){
        let flag_checked = this.checked;
        if($(this).val()==4){
            if(flag_checked){
                $('#monitoring_district').attr("disabled",false);
            }
            else{
                $('#monitoring_district').attr("disabled",true);
                $('#monitoring_district').prop("checked",false);
            }
        }

    });

});

