/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/libs/datatables.init.js ***!
  \**********************************************/
$(document).ready(function () {
  $("#datatable").DataTable(), $("#datatable-buttons").DataTable({
    lengthChange: !1,
    buttons: ["copy", "excel", "pdf", "colvis"]
  }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm");
});

$(document).ready(function () {
  $("#datatable_1").DataTable(), $("#datatable-buttons").DataTable({
    lengthChange: !1,
    buttons: ["copy", "excel", "pdf", "colvis"]
  }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm");
});

$(document).ready(function () {
  $("#datatable_2").DataTable(), $("#datatable-buttons").DataTable({
    lengthChange: !1,
    buttons: ["copy", "excel", "pdf", "colvis"]
  }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm");
});

$(document).ready(function () {
  $("#datatable_3").DataTable(), $("#datatable-buttons").DataTable({
    lengthChange: !1,
    buttons: ["copy", "excel", "pdf", "colvis"]
  }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm");
});

$(document).ready(function () {
  $("#datatable_no_page").DataTable({
    "paging": true,
    "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"] ],
    "columnDefs": [ {
      "targets": 4,
      "orderable": false
      }],
      "language": {
        "lengthMenu": "Papar _MENU_ rekod per mukasurat",
        "search": "Carian:",
        "zeroRecords": "Tiada rekod ditemui",
        "info": "Papar mukasurat _PAGE_ dari _PAGES_",
        "infoEmpty": "Tiada rekod ditemui",
        "infoFiltered": "(Carian dari _MAX_ jumlah rekod)",
        "emptyTable": "Tiada rekod",
        "paginate": {
            "first": "Mula",
            "last": "Tamat",
            "next": ">",
            "previous": "<"
        },
    }
  });
});
/******/ })()
;
