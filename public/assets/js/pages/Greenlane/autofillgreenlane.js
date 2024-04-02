//Ajax Autofill data after insert ic
function autofill_ic(){
    var new_ic = $("#new_ic").val();
    let _token = $('meta[name="csrf-token"]').attr('content');
 
    $.ajax({
        url: '/PermohonanGreenlane/ajaxGetField',
        type    : 'POST',
        data    : {new_ic:new_ic,
                  _token: _token},
        dataType: 'json',
        success:function(data){

            $("#name").val(data.name);
            $("#user_id").val(data.id);
            $("#email").val(data.email); 
            $("#services_type").val(data.services_type); 
            $("#position").val(data.position_name); 
            $("#position_grade").val(data.grade_no); 
            $("#marital_status").val(data.marital_status);
            $("#phone_no_hp").val(data.phone_no_hp);
            $("#address").val(data.address_1+" "+data.address_2+" "+data.address_3);
            $("#phone_no_home").val(data.phone_no_home);
            $("#position_type").val(data.position_type);
            $("#user_no_salary").val(data.no_gaji);
            $("#office_address").val(data.address_office_1+" "+data.address_office_2+" "+data.address_office_3);
            $("#current_address_1").val(data.address_1);
            $("#current_address_2").val(data.address_2);
            $("#current_address_3").val(data.address_3);

                    
            //Format date
            dates = data.date_of_service;
            getNewString = (dates) => {
                const date = new Date(dates);
                return `${date.getDate()}/${date.getMonth()+1}/${date.getFullYear()}`;
              }
            $("#date_of_service").val(getNewString(dates));
        }
    })
}

//Ajax Autofill data after insert ic
function autofill_spouse(){
    var new_ic = $("#new_ic").val();
    let _token = $('meta[name="csrf-token"]').attr('content');
 
    $.ajax({
        url: '/PermohonanGreenlane/ajaxGetFieldSpouse',
        type    : 'POST',
        data    : {new_ic:new_ic,
                  _token: _token},
        dataType: 'json',
        success:function(data){
            //Display spouse info
            $("#spouse_name").val(data.spouse_name);
            $("#spouse_new_ic").val(data.spouse_new_ic);
            $('input[name^="is_spouse_work"][value="0"]').prop('checked',true);

            if(data.spouse_name == null){
                document.getElementById("spouse_phone_no").disabled = true;
            }

            //Set Radio Button Epnj Spouse
            var spouse_new_ic = $("#spouse_new_ic").val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
            url: '/PermohonanGreenlane/ajaxGetFieldSpouseEpnj',
            type    :  'POST',
            data    :  {spouse_new_ic:spouse_new_ic,
                       _token: _token},
                       dataType: 'json',
                    success:function(data){
                    $('input[name^="is_epnj_spouse"][value="1"]').prop('checked',true);  
                    //If spouse ic not available in epnj, auto set checkbox no
                    if(data.epnj_ic == null){
                        $('input[name^="is_epnj_spouse"][value="0"]').prop('checked',true);
                    }

                    //Check radio button pasangan pemohon epnj and disable form
                    let isSpouseEpnj = $("input[name='is_epnj_spouse']:checked").val() == 1;
                        $("input[name='spouse_epnj_address_1']").prop('disabled', (i, v) => !isSpouseEpnj);
                        $("input[name='spouse_epnj_address_2']").prop('disabled', (i, v) => !isSpouseEpnj);
                        $("input[name='spouse_epnj_address_3']").prop('disabled', (i, v) => !isSpouseEpnj);
                        $("input[name='spouse_epnj_postcode']").prop('disabled', (i, v) => !isSpouseEpnj);
                        $("input[name='spouse_epnj_mukim']").prop('disabled', (i, v) => !isSpouseEpnj);

                        $("input[name='spouse_epnj_address_1']").prop('required', (i, v) => isSpouseEpnj);
                        $("input[name='spouse_epnj_address_2']").prop('required', (i, v) => isSpouseEpnj);
                        $("input[name='spouse_epnj_address_3']").prop('required', (i, v) => isSpouseEpnj);
                        $("input[name='spouse_epnj_postcode']").prop('required', (i, v) => isSpouseEpnj);
                        $("input[name='spouse_epnj_mukim']").prop('required', (i, v) => isSpouseEpnj);
                }
            })
      
        }
    })
}

//Ajax field Epnj
function autofill_Epnj(){

    //Set Radio Button Epnj
    var new_ic = $("#new_ic").val();
    let _token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/PermohonanGreenlane/ajaxGetFieldEpnj',
        type    : 'POST',
        data    : {new_ic:new_ic,
                   _token: _token},
                   dataType: 'json',
                    success:function(data){
                        $('input[name^="is_epnj_user"][value="1"]').prop('checked',true);
                        //If user ic not available in epnj, auto set checkbox no
                        if(data.epnj_ic == null){
                            $('input[name^="is_epnj_user"][value="0"]').prop('checked',true);
                        }
    
                        //Check radio button pasangan pemohon epnj and disable form
                        let isEpnj = $("input[name='is_epnj_user']:checked").val() == 1;
                            $("input[name='user_epnj_address_1']").prop('disabled', (i, v) => !isEpnj);
                            $("input[name='user_epnj_address_2']").prop('disabled', (i, v) => !isEpnj);
                            $("input[name='user_epnj_address_3']").prop('disabled', (i, v) => !isEpnj);
                            $("input[name='user_epnj_postcode']").prop('disabled', (i, v) => !isEpnj);
                            $("input[name='user_epnj_mukim']").prop('disabled', (i, v) => !isEpnj);
    
                            $("input[name='user_epnj_address_1']").prop('required', (i, v) => isEpnj);
                            $("input[name='user_epnj_address_2']").prop('required', (i, v) => isEpnj);
                            $("input[name='user_epnj_address_3']").prop('required', (i, v) => isEpnj);
                            $("input[name='user_epnj_postcode']").prop('required', (i, v) => isEpnj);
                            $("input[name='user_epnj_mukim']").prop('required', (i, v) => isEpnj);

        }
    })
}

//Ajax Table Maklumat Anak
function autofill_TableAnak(){

    var new_ic = $("#new_ic").val();
    let _token = $('meta[name="csrf-token"]').attr('content');
    var tableId = "child_info";

    $("#"+tableId).empty();
        var wrapper = $('#'+tableId);
        var counter = 0;

    $.ajax({
        url: '/PermohonanGreenlane/ajaxGetTable',
        type    : 'POST',
        data    : {new_ic:new_ic,
                  _token: _token},
        dataType: 'json',
        success:function(data){

            checkbox_cacat="";
            //Array Table Maklumat Anak
            $.each(data, function(key, value){
                child_id = value.child_id;
                child_name = value.child_name;
                child_ic = value.child_ic;
                is_cacat = value.is_cacat;
                checkbox_cacat= (is_cacat == 1 ) ? 'checked' : "";

                counter++;

                var fieldHTML = '';

                fieldHTML+='<tr>';
                fieldHTML+='<td class="text-center">'+ counter +'</td>';
                fieldHTML+='<td class="text-center" >'+ child_name
                fieldHTML+='<input type="hidden" name="child_name['+child_id +']" value="'+child_name +'"></input>';
                fieldHTML+='</td>';
                fieldHTML+='<td class="text-center" >'+ child_ic
                fieldHTML+='<input type="hidden" name="child_ic['+child_id +']" value="'+child_ic +'" ></input>';
                fieldHTML+='</td>';

                //File
                fieldHTML+='<td class="text-center">';
                fieldHTML+='<div class="form-check">';
                fieldHTML+='<input type="hidden" name="child['+child_id+']" value="'+child_id+'" ></input>';
                fieldHTML+='<input type="file" class="form-control" name="child_ic_document['+child_id+']" value="'+child_id+'" required data-parsley-errors-container="#parsley-errors" data-parsley-required-message="Sila muat naik salinan kad pengenalan/surat beranak"></input>';
                fieldHTML+='</div>';
                fieldHTML+='</td>';


                //Checkbox Cacat
                fieldHTML+='<td class="text-center width=1%">';
                fieldHTML+='<div class="form-check">';
                fieldHTML+='<input type="checkbox" class="center" name="cacat['+child_id+']" value="'+is_cacat+'" id="cacat_'+child_id+'" '+ checkbox_cacat +'></input>';
                fieldHTML+='</div>';
                fieldHTML+='</td>';
                fieldHTML+='</tr>';

                $(wrapper).append(fieldHTML);
                
            });        
        },
        error: function(){ }
    });
}

$(document).on("change", "input[name='is_spouse_work']", function(e){
	let isSpouseWork = $(this).val() == 1;

	$("input[name='spouse_office_address_1']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_office_address_2']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_office_address_3']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_position']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_salary']").prop('disabled', (i, v) => !isSpouseWork);

	$("input[name='spouse_office_address_1']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_office_address_2']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_office_address_3']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_position']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_salary']").prop('required', (i, v) => isSpouseWork);

	$("span.spouse-work").toggle(isSpouseWork);

	$("form#app-form").parsley().reset(); // reset form validation config
});

jQuery(function(){
	let isSpouseWork = $("input[name='is_spouse_work']:checked").val() == 1;

	$("input[name='spouse_office_address_1']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_office_address_2']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_office_address_3']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_position']").prop('disabled', (i, v) => !isSpouseWork);
	$("input[name='spouse_salary']").prop('disabled', (i, v) => !isSpouseWork);

	$("input[name='spouse_office_address_1']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_office_address_2']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_office_address_3']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_position']").prop('required', (i, v) => isSpouseWork);
	$("input[name='spouse_salary']").prop('required', (i, v) => isSpouseWork);

	$("span.spouse-work").toggle(isSpouseWork);

	$("form#app-form").parsley().reset(); // reset form validation config
});

//Checkbox Pasangan Kerja
// function disableSpouseWork() {
//     if (document.getElementById("tidakBekerja").checked) {
//         document.getElementById("spouse_office_address_1").disabled = true;
//         document.getElementById("spouse_office_address_2").disabled = true;
//         document.getElementById("spouse_office_address_3").disabled = true;
//         document.getElementById("spouse_position").disabled = true;
//         document.getElementById("spouse_salary").disabled = true;
        

//     } else {
//         document.getElementById("spouse_office_address_1").disabled = false;
//         document.getElementById("spouse_office_address_2").disabled = false;
//         document.getElementById("spouse_office_address_3").disabled = false;
//         document.getElementById("spouse_position").disabled = false;
//         document.getElementById("spouse_salary").disabled = false;

//     }
// }

// function enableSpouseWork() {
//     if (document.getElementById("yaBekerja").checked) {
//         document.getElementById("spouse_office_address_1").disabled = false;
//         document.getElementById("spouse_office_address_2").disabled = false;
//         document.getElementById("spouse_office_address_3").disabled = false;
//         document.getElementById("spouse_position").disabled = false;
//         document.getElementById("spouse_salary").disabled = false;
        
//     } else {
//         document.getElementById("spouse_office_address_1").disabled = true;
//         document.getElementById("spouse_office_address_2").disabled = true;
//         document.getElementById("spouse_office_address_3").disabled = true;
//         document.getElementById("spouse_position").disabled = true;
//         document.getElementById("spouse_salary").disabled = true;
        
//     }
// }

// current house distance
jQuery(function(){
	let app_id = $("input[name='id']").val();
	let current_address1 = $("input[name='current_address1']").val();
	let current_address2 = $("input[name='current_address2']").val();
	let current_address3 = $("input[name='current_address3']").val();

	let getDistance = getDistanceByLatLong(app_id, current_address1, current_address2, current_address3);
	
	console.log('Hello');
	getDistance.done(function(response, textStatus, jqXHR){
		let distanceValue = ( response.distance !== null) ? response.distance.toFixed(2) : 0;
		$('#current_house_distance').val(distanceValue);
	})

});

$(function () {

	$(document).on("focusout", "input[name*='user_epnj_address']", function(){

		let app_id = $("input[name='id']").val();
		let current_address1 = $("input[name='user_epnj_address_1']").val();
		let current_address2 = $("input[name='user_epnj_address_2']").val();
		let current_address3 = $("input[name='user_epnj_address_3']").val();

		let getDistance = getDistanceByLatLong(app_id, current_address1, current_address2, current_address3);
		
		getDistance.done(function(response, textStatus, jqXHR){
			let distanceValue = ( response.distance !== null) ? response.distance.toFixed(2) : 0;
			$('#epnj_distance').val(distanceValue);
			console.log(response);
		})
	});

	$(document).on("focusout", "input[name*='spouse_epnj_address']", function(){

		let app_id = $("input[name='id']").val();
		let current_address1 = $("input[name='spouse_epnj_address_1']").val();
		let current_address2 = $("input[name='spouse_epnj_address_2']").val();
		let current_address3 = $("input[name='spouse_epnj_address_3']").val();

		let getDistance = getDistanceByLatLong(app_id, current_address1, current_address2, current_address3);

		getDistance.done(function(response, textStatus, jqXHR){
			let distanceValue = ( response.distance !== null) ? response.distance.toFixed(2) : 0;
			$('#spouse_epnj_distance').val(distanceValue);
		})
	});
	
});

function getDistanceByLatLong(app_id, address1, address2, address3){

	let url = $("input[name='user_epnj_address_1']").data('url');

	let ajaxGetDistance = $.ajax({
		    url: url,
		    type:"GET",
			data:{
				app_id	 : app_id,
				address1 : address1,
				address2 : address2,
				address3 : address3,
			}
		})

	return ajaxGetDistance;
}


