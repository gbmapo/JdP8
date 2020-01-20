jQuery(document).ready(function ($) {

    $.fn.dataTable.moment('MM/YYYY');

    $('#listofservices').DataTable({
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
        "info": true,
        "order": [],
        "paging": false,
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            "orderable": false
        }],
        orderCellsTop: true,
        "dom": '<"top"if>',
        fixedHeader: {
            header: true,
            footer: false
        },
        initComplete: function () {
            this.api().columns([0, 1, 8]).every(function (i) {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo($("#listofservices thead tr:eq(1) th").eq(column.index()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                switch (i) {
                    case 0:
                        column.data().unique().sort().each(function (d, j) {
                            d2 = d.substring(0, 1);
                            select.append('<option value="' + d + '">' + d2 + '</option>')
                        });
                        break;
                    case 1:
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                        break;
                    case 8:
                        column.data().unique().sort().each(function (d, j) {
                            d2 = d.match(/<a[^>]*>(.*?)<\/a>/);
                            select.append('<option value="' + d2[1] + '">' + d2[1] + '</option>')
                        });
                        break;
                    default:
                }

            });
        }
    });

});
