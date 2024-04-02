jQuery(document).ready(function ($) {
  $("input[name='date_open']").datepicker({
      startDate: new Date(),
  });

  $("#year").datepicker( {
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    startDate: new Date(),
    changeYear: true,
  }).on('changeDate', function(e){
    if(!this.value) return;
      
    let setDate = new Date (this.value);
    
    console.log(setDate);
    //this prevent maxdate if going ahead  for date
    $("input[name='date_open']").datepicker( "option", "maxDate", null );
    $("input[name='date_open']").datepicker("setDate", setDate);
    $("input[name='date_open']").datepicker( "option", "maxDate", setDate );
    $("input[name='date_open']").datepicker( "option", "changeMonth", false );
  });

  // $('#year').on("change", function(e) {
  //   if(!this.value) return;
    
  //   let setDate = new Date (this.value);
    
  //   console.log(setDate);
  //   //this prevent maxdate if going ahead  for date
  //   $("input[name='date_open']").datepicker( "option", "maxDate", null );
  //   $("input[name='date_open']").datepicker("setDate", setDate);
  //   $("input[name='date_open']").datepicker( "option", "maxDate", setDate );
  //   $("input[name='date_open']").datepicker( "option", "changeMonth", false );
    
  // });
  
});

function setStartDateDatePicker(datePickerInputId,startDateValue,addDay){
  //datePickerInputId = id for date input, not the datepicker div
  //startDateValue = dd/mm/yyyy   

  if(startDateValue){
      const [day, month, year] = startDateValue.split('/');
      const newDate = new Date(+year, +month - 1, +day + addDay);//month should minus one since in month number start from 0
  
      $('#'+datePickerInputId).datepicker('setStartDate', newDate);
  }
}

jQuery(document).ready(function ($) {
  $("input[name='date_close']").datepicker({
    
  });
  
  $('#year').on("change", function(e) {
    // const startdate = $('#date_open').val();

    if(!this.value) return;
    
    let setDate = new Date (this.value);

    console.log(setDate);
    //this prevent maxdate if going ahead  for date
    $("input[name='date_close']").datepicker( "option", "maxDate", null );
    $("input[name='date_close']").datepicker("setDate", setDate);
    $("input[name='date_close']").datepicker( "option", "maxDate", setDate );
    

    $("input[name='date_close']").datepicker( "option", "maxDate", setDate );

    // setStartDateDatePicker($('#date_open'),startdate,0);
  });
  
});

//validate min date for each table row
// function validateEndDate(dateValue){

//   var tarikh_dari = dateValue;
//   // alert(tarikh_dari);
//   var startdate = tarikh_dari.split("/").reverse().join("-");

//   $('#date_open').val(startdate);

//   var tarikh_hingga = document.getElementById("date_close").value;
//   tarikh_hingga = tarikh_hingga.split("/").reverse().join("-");

//   $('#date_close').val(tarikh_hingga);
    
//   if(tarikh_hingga <= startdate)
//   {
//     $('#date_close').val('').datepicker('update');
//     document.getElementById("date_close").value = '';
//   }
// }

function validateEndDate(startDateId, endDateid){
  const startdate = $('#'+startDateId+'').val();
  const endDate = $('#'+endDateid+'').val();

  var tarikh_mula = startdate.split("/").reverse().join("-");
  var tarikh_tamat = endDate.split("/").reverse().join("-");

  if(endDate)
  {
      if(tarikh_tamat < tarikh_mula)
      {
          $('#'+endDateid+'').val('');
          var addDay = 0;
          setStartDateDatePicker(endDateid,startdate,addDay);
          
      }else{
          var addDay = 0;
          setStartDateDatePicker(endDateid,startdate,addDay);
      }
  
  }else{
          var addDay = 0;
          setStartDateDatePicker(endDateid,startdate,addDay);
      }
  

}

function validateStartDate(dateValue,msg){
  
  var startdate =document.getElementById("date_open").value;
  startdate = startdate.split("/").reverse().join("-");

  var tarikh_hingga = dateValue;
  tarikh_hingga = tarikh_hingga.split("/").reverse().join("-");

  $('#date_close').val(tarikh_hingga);

  if(tarikh_hingga < startdate)
  {
    $('#date_close').val('').datepicker('update');
    document.getElementById("date_close").value = '';
    $('#errorContainer2').html('<span class="text-danger">'+ msg +'</span>');
  }else{
    $('#errorContainer2').html('');
  }
}
// $('#year').on('change',function(){
//   var selectedYear;
//   selectedYear = $('#year :selected').text();
//   var start = new Date();
//   start.setFullYear(selectedYear);
//   $("input[name='date_open']").datepicker("destroy");
//   $( "input[name='date_open']" ).datepicker({
//     	changeMonth: true,
//       changeYear: false,
//       yearRange: start.getFullYear() + ':' + start.getFullYear()
// });
// });

