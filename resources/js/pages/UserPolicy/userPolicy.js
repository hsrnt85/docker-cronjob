

$( document ).ready(function() {

    $("#tbody_data").empty();
    var wrapper = $('#tbody_data'); 
    var row_index = 0;	
    var fieldHTML = '';
    let dataSubmenu = jQuery.parseJSON($('#configSubmenu').val());//alert(dataSubmenu);
    let dataRolesAbilities = jQuery.parseJSON($('#rolesAbilities').val());//alert(dataSubmenu);
   
    checkbox_abilities_a = ""; 
    checkbox_abilities_u = ""; 
    checkbox_abilities_v = ""; 
    checkbox_abilities_d = ""; 

    $.each(dataSubmenu, function(i, data){
        
        menu_id = (data.config_menu_id) ? data.config_menu_id : ''; 
        submenu_id = (data.id) ? data.id : ''; 
        submenu = (data.submenu) ? data.submenu : ''; 
        action = (data.action) ? data.action : ''; 

        checked_a = ''; 
        checked_u = ''; 
        checked_v = ''; 
        checked_d = ''; 

        if(dataRolesAbilities){
           
            $.each(dataRolesAbilities, function(j, dataA){     
                if(dataA.config_submenu_id == submenu_id ){
                    roles_abilities_id = (dataA.id) ? dataA.id : '0'; 
                    checked_a = (dataA.abilities.includes('A')) ? 'checked' : ''; 
                    checked_u = (dataA.abilities.includes('U')) ? 'checked' : ''; 
                    checked_v = (dataA.abilities.includes('V')) ? 'checked' : ''; 
                    checked_d = (dataA.abilities.includes('D')) ? 'checked' : ''; 
                } 
            });

            checkbox_abilities_a = (action.includes('A')) ? '<input type="checkbox" name="abilities'+ row_index +'[0]" value="A" '+ checked_a +'></input>' : ""; 
            checkbox_abilities_u = (action.includes('U')) ? '<input type="checkbox" name="abilities'+ row_index +'[1]" value="U" '+ checked_u +'></input>' : ""; 
            checkbox_abilities_v = (action.includes('V')) ? '<input type="checkbox" name="abilities'+ row_index +'[2]" value="V" '+ checked_v +'></input>' : ""; 
            checkbox_abilities_d = (action.includes('D')) ? '<input type="checkbox" name="abilities'+ row_index +'[3]" value="D" '+ checked_d +'></input>' : ""; 
        }

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
