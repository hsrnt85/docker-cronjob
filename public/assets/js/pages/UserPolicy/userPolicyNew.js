

$( document ).ready(function() {

    $("#tbody_data").empty();
    var wrapper = $('#tbody_data');
    var wrapper_hidden = $('#section_hidden');
    var row_index = 0;
    var htmlContent = '';
    var htmlFooter = '';
    let dataSubmenu = jQuery.parseJSON($('#configSubmenu').val());//alert(dataSubmenu);

    checkbox_abilities_a = "";
    checkbox_abilities_u = "";
    checkbox_abilities_v = "";
    checkbox_abilities_d = "";

    $.each(dataSubmenu, function(i, data){

        menu_id = (data.config_menu_id) ? data.config_menu_id : '';
        submenu_id = (data.id) ? data.id : '';
        submenu = (data.submenu) ? data.submenu : '';
        action = (data.action) ? data.action : '';

        checkbox_abilities_a = (action.includes('A')) ? '<div class="form-check"><input type="checkbox" class="form-check-input abilities" name="abilities'+ row_index +'[0]" value="A" onclick="setCounter()"></input></div>' : "";
        checkbox_abilities_u = (action.includes('U')) ? '<div class="form-check"><input type="checkbox" class="form-check-input abilities" name="abilities'+ row_index +'[1]" value="U" onclick="setCounter()" ></input></div>' : "";
        checkbox_abilities_v = (action.includes('V')) ? '<div class="form-check"><input type="checkbox" class="form-check-input abilities" name="abilities'+ row_index +'[2]" value="V" onclick="setCounter()" ></input></div>' : "";
        checkbox_abilities_d = (action.includes('D')) ? '<div class="form-check"><input type="checkbox" class="form-check-input abilities" name="abilities'+ row_index +'[3]" value="D" onclick="setCounter()" ></input></div>' : "";

        htmlContent =  '<tr class="tabContent sub_view'+ menu_id +'">';
        htmlContent += '<td width="40%"> '+submenu+' <input type="hidden" name="ids'+ row_index +'" value="'+ menu_id +'-'+ submenu_id +'"></td>';
        htmlContent += '<td>'+checkbox_abilities_a+'</td>';
        htmlContent += '<td>'+checkbox_abilities_u+'</td>';
        htmlContent += '<td>'+checkbox_abilities_v+'</td>';
        htmlContent += '<td>'+checkbox_abilities_d+'</td>';
        htmlContent += '</tr>';

        $(wrapper).append(htmlContent);

        row_index ++;

    });

    htmlFooter += '<input type="hidden" name="total_record" value="'+ row_index +'">';
    $(wrapper_hidden).append(htmlFooter);

    //HIDE ALL SUBMODULE
    $('.tabContent').hide();

    //SHOW SELECTED SUBMODULE
    $('.sub_view1').show();

});

