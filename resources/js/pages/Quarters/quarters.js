
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