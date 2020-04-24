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
      {responsivePriority: 3, targets: 11},
      {responsivePriority: 4, targets: 5},
      {responsivePriority: 4, targets: 6},
      {responsivePriority: 4, targets: 7},
      {responsivePriority: 5, targets: 8},
      {responsivePriority: 10001, targets: 1},
    ],

  });

});
