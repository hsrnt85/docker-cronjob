jQuery(function(){$(".input-mask").inputmask()});

$(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/[^\d\.]/g, '');
});

var validateHargaInventory = function(e) 
{
    var t = e.value;
    e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
}