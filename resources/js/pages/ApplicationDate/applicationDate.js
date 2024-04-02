jQuery(document).ready(function ($) {
  $("input[name='date_open']").datepicker({

  });

  $('#year').on("change", function(e) {
    if(!this.value) return;

    let setDate = new Date (this.value);

    //this prevent maxdate if going ahead  for date
    $("input[name='date_open']").datepicker( "option", "maxDate", null );
    $("input[name='date_open']").datepicker("setDate", setDate);
    $("input[name='date_open']").datepicker( "option", "maxDate", setDate );
    $("input[name='date_open']").datepicker( "option", "changeMonth", false );

  });

});

jQuery(document).ready(function ($) {
  $("input[name='date_close']").datepicker({

  });

  $('#year').on("change", function(e) {
    if(!this.value) return;

    let setDate = new Date (this.value);

    //this prevent maxdate if going ahead  for date
    $("input[name='date_close']").datepicker( "option", "maxDate", null );
    $("input[name='date_close']").datepicker("setDate", setDate);
    $("input[name='date_close']").datepicker( "option", "maxDate", setDate );

  });

});

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

