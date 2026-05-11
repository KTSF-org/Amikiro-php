$(document).ready(function () {
	$('#listBat').DataTable();
});



 $('#listBat').DataTable({
  order: [[2, 'asc']],    // Tri initial : colonne 1 croissant
  searching: true,         // Activer/désactiver la recherche
  paging: true,            // Activer/désactiver la pagination
  info: true,              // Afficher "Showing X of Y entries"
  language: {              // Traduction en français
    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
  },
  columnDefs: [
    { orderable: false, targets: [0, 3] },  // Désactiver le tri sur la colonne 3
	{ width : "30px", targets: [0, 1] },
	{ width : "120px", targets: [3] }
]
});
