$(document).ready(function () {

  // TABLEAU BAT
  $('#listBat').DataTable({
    dom: '<"m-1"lf>rt<"m-1"ip>',
    order: [[2, 'asc']],
    searching: true,
    paging: true,
    info: true,
    language: {
      url: ASSET_BASE + '/lib/datatables/i18n/fr-FR.json'
    },
    columnDefs: [
      { orderable: false, targets: [0, 3] },
      { width: "30px", targets: [0, 1] },
      { width: "120px", targets: [3] }
    ]
  });

  // TABLEAU SECTION
  $('#listSection').DataTable({
    dom: '<"m-1"lf>rt<"m-1"ip>',
    order: [[3, 'desc']],
    searching: true,
    paging: true,
    info: true,
    language: {
      url: ASSET_BASE + '/lib/datatables/i18n/fr-FR.json'
    },
    columnDefs: [
      { orderable: false, targets: [5] },
      { width: "30px", targets: [0] },
      { width: "100px", targets:[1]},
      { width: "200px", targets:[4]},
      { width: "180px", targets: [3] },
      { width: "120px", targets: [5]}
    ]
  });

});
