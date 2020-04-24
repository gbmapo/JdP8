jQuery(document).ready(function ($) {

  $('#listofdistributions').DataTable({

    "info": false,
    "ordering": false,
    "paging": false,
    "searching": false,
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Distribution du ' + data[0];
          }
        }),
        // renderer: $.fn.dataTable.Responsive.renderer.tableAll()
        'renderer': function (api, rowIdx, columns) {
          var data = $.map(columns, function (col, i) {

            if (col.data) {
              if (col.title != 'Date') {
                return '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' + '<td><b>' + col.title + '</b></td></tr>';
              }
            }
            else {
              return '';
            }

          }).join('');

          if (data) {
            return $('<table/>').append(data);
          }
          else {
            return false;
          }
        }

      }
    }
  });

});
