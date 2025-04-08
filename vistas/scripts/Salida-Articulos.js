var tabla;

function init() {
  listar();
}

function listar() {
  tabla = $('#tbllistado').dataTable(
    {
      "aProcessing": true,
      "aServerSide": true, 
      dom: 'Bfrtip', 
      buttons: [
      ],
      "ajax":
      {
        url: '../ajax/servicios-soporte.php?op=salidaArticulo',
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 15,
      "order": [[0, "desc"]]
    }
  ).DataTable();
}

init();