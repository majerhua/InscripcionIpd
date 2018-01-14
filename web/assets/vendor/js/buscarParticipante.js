$('.cargarDatosParticipante').hide();

function buscarDniHijo(refDni){

	var datoDniPersona = {

		'dni' : refDni.value,
		"persona": 'hijo'
	};

	$.ajax({

		type:'POST',
		dataType: 'json',
		url: "/",
		data: datoDniPersona,
		beforeSend: function(){
			$('.cargarDatosParticipante').show();
		},
		success: function(data){	
			var dataHijo = JSON.parse(data);
			console.log("Busqueda DNI ==> ",dataHijo);
				$(dataHijo).each(function(index,valor){

					if(valor.edad <18){
						$("#apellidoPaterno-hijo").val(valor.apellidoPaterno);
						$("#apellidoMaterno-hijo").val(valor.apellidoMaterno);
						$("#nombre-hijo").val(valor.nombre);
						$("#fechaNacimiento-hijo").val(valor.fechaNacimiento);
						$("#sexo-hijo").val(valor.sexo);
					}

					else{
						$(".mensajeEdadParticpante").empty();
						$(".mensajeEdadParticipante").append("Usted Es mayor de edad");
					}
				});					
			
				$('.cargarDatosParticipante').hide();
			
		},
		error: function(error){
			console.log(error);
		}
	});
}