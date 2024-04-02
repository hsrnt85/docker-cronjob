//Checkbox Pasangan Kerja
function disable_conformation_status() {
  if (document.getElementById("rejected").checked) {
      document.getElementById("remarks").disabled = true;
      document.getElementById("monitoring_file").disabled = true;
       
  } else {
      document.getElementById("remarks").disabled = false;
      document.getElementById("monitoring_file").disabled = false;
  }
}

function enable_conformation_status_finish() {
   if (document.getElementById("finish").checked) {
       document.getElementById("remarks").disabled = false;
       document.getElementById("monitoring_file").disabled = false;
      
  } else {
       document.getElementById("remarks").disabled = true;
       document.getElementById("monitoring_file").disabled = true;
  }
}

function enable_conformation_status_repeat() {
  if (document.getElementById("repeat").checked) {
      document.getElementById("remarks").disabled = false;
      document.getElementById("monitoring_file").disabled = false;
     
 } else {
      document.getElementById("remarks").disabled = true;
      document.getElementById("monitoring_file").disabled = true;
 }
}

function enable_conformation_status_maintenance() {
  if (document.getElementById("maintenance").checked) {
      document.getElementById("remarks").disabled = false;
      document.getElementById("monitoring_file").disabled = false;
     
 } else {
      document.getElementById("remarks").disabled = true;
      document.getElementById("monitoring_file").disabled = true;
 }
}

function myFunction(imgs, attachment_id) {
  // alert( attachment_id);
  $('#attachment_id').val(attachment_id);
  var expandImg = document.getElementById("expandedImg");
  var clickingText = document.getElementById("clickingText");
  expandImg.src = imgs.src;
  expandImg.parentElement.style.display = "block";
  clickingText.style.display = "none";
  expandImg.style.width = '100%';
  expandImg.style.height = '300px';
  expandImg.style.border = "1px solid black";

 }

 function myFunction2(imgs,complaint_attachment) {

  $('#complaint_attachment').val(complaint_attachment);
  var expandImg = document.getElementById("expandedImg2");
  var clickingText = document.getElementById("clickingText2");
      expandImg.src = imgs.src;
      expandImg.parentElement.style.display = "block";
      clickingText.style.display = "none";
      expandImg.style.width = '100%';
      expandImg.style.height = '300px';
      expandImg.style.border = "1px solid black";
}

function myFunction3(imgs, attachment_id) {
  // alert( attachment_id);
  $('#attachment_id').val(attachment_id);
  var expandImg = document.getElementById("expandedImg3");
  var clickingText = document.getElementById("clickingText3");
  expandImg.src = imgs.src;
  expandImg.parentElement.style.display = "block";
  clickingText.style.display = "none";
  expandImg.style.width = '100%';
  expandImg.style.height = '300px';
  expandImg.style.border = "1px solid black";

 }