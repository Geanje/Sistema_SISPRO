
function agregarIgv(e){
    //alert("si llama")
    e.preventDefault();
    var formData = new FormData($("#idigv")[0]);
    $.ajax({
        url: "../ajax/igv.php?op=agregarIgv",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false
        
    })
    swal({
		title: "IGV Actualizado",
		//text: "¡"+datos+"!",
		type:"success",
		confirmButtonText: "Cerrar",
		closeOnConfirm: true
		
	  });
}
//var verifica=document.getElementById("porcentaje").value;
function evitandoVacio(e){
  if($("#porcentaje").val()===""){
    console.log("Necesita llenar el campo")
    return false;
  }else{
    agregarIgv(e);
  }
}
/*function union(e){
  evitandoVacio();
  agregarIgv(e);

}*/