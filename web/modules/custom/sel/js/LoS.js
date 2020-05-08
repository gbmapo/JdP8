jQuery(document).ready(function ($) {

  $('#listofservices').DataTable({

    "info": false,
    "language": {
      "search": "Rechercher",
    },
    "ordering": false,
    "paging": false,
    "responsive": true,
    columnDefs: [
      {responsivePriority: 1, targets: 0},
      {responsivePriority: 1, targets: 2},
      {responsivePriority: 1, targets: 3},
      {responsivePriority: 2, targets: 8},
      {responsivePriority: 3, targets: 5},
      {responsivePriority: 3, targets: 6},
      {responsivePriority: 3, targets: 7},
      {responsivePriority: 4, targets: 1},
    ],
    initComplete: function () {
//    this.api().columns([1, 8]).every(function (i) {
      this.api().columns([8]).every(function (i) {
        var column = this;
        var select = $('<select><option value=""></option></select>')
          .appendTo($(column.header()))
          .on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );
            column
              .search(val ? '^' + val + '$' : '', true, false)
              .draw();
          });

        switch (i) {
/*
          case 1:
            column.data().unique().sort().each(function (d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
            break;
 */
          case 8:
            var temp = {};
            column.data().unique().each(function (d, j) {
              var sText = $('<a/>').html(d).text();
              temp[sText] = '<option value="' + sText + '">' + sText + '</option>';
            });
            Object.keys(temp).sort().forEach(function (key) {
              select.append(temp[key]);
            });
            break;
          default:
        }
      });
    }

  });

});
