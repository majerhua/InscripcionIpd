$('.cargarDatosApoderado').hide();	

function buscarDniApoderado(refDni){

	datoDniPersona = {

		'dni' : refDni.value,
		"persona": 'apoderado'
	};

	$.ajax({

		type:'POST',
		dataType: 'json',
		url: "/",
		data: datoDniPersona,

		beforeSend: function(){
			$('.cargarDatosApoderado').show();
		},

		success: function(data){
			console.log(data)
			
		if(data !== ""){
			var dataParticiapnte = JSON.parse(data);

			console.log("Busqueda DNI ==> ",dataParticiapnte);

				$(dataParticiapnte).each(function(index,valor){

					if(valor.edad >=18){

						$("#apellidoPaterno").val(valor.apellidoPaterno);
						$("#apellidoMaterno").val(valor.apellidoMaterno);
						$("#nombre").val(valor.nombre);
						$("#fechaNacimiento").val(valor.fechaNacimiento);

						if(valor.sexo == 1){
							$("#sexo").val('masculino');	
						}else{
							$("#sexo").val('femenino');
						}	
					}
					else{

						$(".mensajeEdad").empty();
						$(".mensajeEdad").append("Usted Es menor de edad");
					}
				});					
			}
				$('.cargarDatosApoderado').hide();
			
		},

		error: function(error){
			console.log(error);
		}

	});
}
