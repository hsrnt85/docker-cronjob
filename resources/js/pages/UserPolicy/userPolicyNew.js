

$( document ).ready(function() {

    $("#tbody_data").empty();
    var wrapper = $('#tbody_data'); 
    var row_index = 0;	
    var fieldHTML = '';
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

        checkbox_abilities_a = (action.includes('A')) ? '<input type="checkbox" name="abilities'+ row_index +'[0]" value="A" ></input>' : ""; 
        checkbox_abilities_u = (action.includes('U')) ? '<input type="checkbox" name="abilities'+ row_index +'[1]" value="U" ></input>' : ""; 
        checkbox_abilities_v = (action.includes('V')) ? '<input type="checkbox" name="abilities'+ row_index +'[2]" value="V" ></input>' : ""; 
        checkbox_abilities_d = (action.includes('D')) ? '<input type="checkbox" name="abilities'+ row_index +'[3]" value="D" ></input>' : ""; 
        
        fieldHTML =  '<tr class="tabContent sub_view'+ menu_id +'">';
        fieldHTML += '<td width="40%"> '+submenu+' <input type="hidden" name="ids'+ row_index +'" value="'+ menu_id +'-'+ submenu_id +'"></td>';
        fieldHTML += '<td>'+checkbox_abilities_a+'</td>';
        fieldHTML += '<td>'+checkbox_abilities_u+'</td>';
        fieldHTML += '<td>'+checkbox_abilities_v+'</td>';
        fieldHTML += '<td>'+checkbox_abilities_d+'</td>';            
        fieldHTML += '</tr>';

        $(wrapper).append(fieldHTML); 

        row_index ++;	

    });

    
    fieldHTML += '<input type="hidden" name="total_record" value="'+ row_index +'">';
        $(wrapper).append(fieldHTML); 

    //HIDE ALL SUBMODULE
    $('.tabContent').hide();

    //SHOW SELECTED SUBMODULE
    $('.sub_view1').show();

});  

function showSection(event, subView, menu_id) {

    //HIDE ALL SUBMODULE
    $('.tabContent').hide();
    
    //SHOW SELECTED SUBMODULE
    $('.'+subView+menu_id).show();

}
