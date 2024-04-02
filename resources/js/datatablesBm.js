$(document).ready(function () {

    //GLOBAL SETTING DATATABLE
    let table = $("#datatable").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ]
    });

    let table1 = $("#datatable1").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ]
    });

    let table2 = $("#datatable2").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ]
    });

    let table3 = $("#datatable3").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ]
    });

    let table4 = $("#datatable4").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] },
            { "orderable": false, "targets": [ -1 ] }
        ]
    });

    let table_report = $("#datatable-report").DataTable({
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
        },
        "searching": true,
        "columnDefs": [
            { "searchable": false, "targets": [ 0 ] }
        ]
    });

    //------------------------------------------------------------------
    // SET DATATABLE BY CURRENT PAGE
    //------------------------------------------------------------------
    $(document).on("click", ".btn-menu", function(e){
        localStorage.setItem('dt-page',0);
    });
    $(document).on("click", ".btn-submenu", function(e){
        localStorage.setItem('dt-page',0);
    });
    $(document).on("click", ".btn-menu", function(e){
        localStorage.setItem('dt-page4',0);
    });
    $(document).on("click", ".btn-submenu", function(e){
        localStorage.setItem('dt-page4',0);
    });


    table.on ('draw', function () {
        let page = table.page();
        localStorage.setItem('dt-page',page);

    });
    if(localStorage.getItem('dt-page')){
        let page = Number(localStorage.getItem('dt-page'));
        table.page(page).draw(false);;
    }

    table1.on ('draw', function () {
        let page = table1.page();
        localStorage.setItem('dt-page',page);

    });
    if(localStorage.getItem('dt-page')){
        let page = Number(localStorage.getItem('dt-page'));
        table1.page(page).draw(false);;
    }

    table2.on ('draw', function () {
        let page = table2.page();
        localStorage.setItem('dt-page',page);

    });
    if(localStorage.getItem('dt-page')){
        let page = Number(localStorage.getItem('dt-page'));
        table2.page(page).draw(false);;
    }

    table3.on ('draw', function () {
        let page = table3.page();
        localStorage.setItem('dt-page',page);

    });
    if(localStorage.getItem('dt-page')){
        let page = Number(localStorage.getItem('dt-page'));
        table3.page(page).draw(false);;
    }

    table4.on ('draw', function () {
        let page = table4.page();
        localStorage.setItem('dt-page4',page)

    });
    if(localStorage.getItem('dt-page4')){
        let page = Number(localStorage.getItem('dt-page4'));
        table4.page(page).draw(false);;
    }
    //------------------------------------------------------------------
    // END SET DATATABLE BY CURRENT PAGE
    //------------------------------------------------------------------
    //END GLOBAL SETTING DATATABLE

});
