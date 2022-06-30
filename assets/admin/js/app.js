// Vendors -------------------------------------------------------------------------------------------------------------
window.$ = window.jQuery = require('jquery/dist/jquery');
require('bootstrap');
require('startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.js');
require('startbootstrap-sb-admin-2/js/sb-admin-2');
window.$.fn.DataTable = require('startbootstrap-sb-admin-2/vendor/datatables/jquery.dataTables.min.js');
window.$.fn.DataTable = require('startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js');

require('./util/bootstrap_file_fix.js');

$(document).ready(function () {
    $('.data-table').DataTable({
        order: {1: "desc"},
        language: {
            "sEmptyTable":     "Tabulka neobsahuje žádná data",
            "sInfo":           "Zobrazuji _START_ až _END_ z celkem _TOTAL_ záznamů",
            "sInfoEmpty":      "Zobrazuji 0 až 0 z 0 záznamů",
            "sInfoFiltered":   "(filtrováno z celkem _MAX_ záznamů)",
            "sInfoPostFix":    "",
            "sInfoThousands":  " ",
            "sLengthMenu":     "Zobraz záznamů _MENU_",
            "sLoadingRecords": "Načítám...",
            "sProcessing":     "Provádím...",
            "sSearch":         "Hledat:",
            "sZeroRecords":    "Žádné záznamy nebyly nalezeny",
            "oPaginate": {
                "sFirst":    "První",
                "sLast":     "Poslední",
                "sNext":     "Další",
                "sPrevious": "Předchozí"
            },
            "oAria": {
                "sSortAscending":  ": aktivujte pro řazení sloupce vzestupně",
                "sSortDescending": ": aktivujte pro řazení sloupce sestupně"
            }
        }
    })
});