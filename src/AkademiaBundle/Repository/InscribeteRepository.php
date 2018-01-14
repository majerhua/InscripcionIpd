<?php

namespace AkademiaBundle\Repository;

/**
 * InscribeteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InscribeteRepository extends \Doctrine\ORM\EntityRepository
{


	public function getFicha($idInscripcion){

   	$query = "  select inscribete.id as id, apoderado.apellidoMaterno as apellidoMaternoApoderado ,
  apoderado.dni as dniApoderado,
  apoderado.apellidoPaterno as apellidoPaternoApoderado ,
   (cast(datediff(dd,participante.fechaNacimiento,GETDATE()) / 365.25 as int)) as edad , 
   apoderado.nombre as nombrePadre,horario.horaInicio, horario.horaFin, participante.nombre
   , participante.apellidoPaterno, participante.apellidoMaterno, participante.dni, apoderado.direccion,
   apoderado.correo, apoderado.telefono, grubigeo.ubinombre as distrito, participante.fechaNacimiento,
   disciplina.dis_descripcion as nombreDisciplina, edificacionDeportiva.ede_nombre as nombreComplejo
   from ACADEMIA.inscribete as inscribete, ACADEMIA.horario as horario, ACADEMIA.participante
   as participante, ACADEMIA.apoderado as apoderado, grubigeo, CATASTRO.edificacionDisciplina 
   as edificacionDisciplina, CATASTRO.disciplina as disciplina, CATASTRO.edificacionesdeportivas as
   edificacionDeportiva
     where inscribete.horario_id = horario.id and 
   participante.id = inscribete.participante_id and apoderado.id = participante.apoderado_id 
   and grubigeo.ubicodigo = apoderado.ubicodigo  and edificacionDisciplina.edi_codigo = 
   horario.edi_codigo and edificacionDisciplina.dis_codigo = disciplina.dis_codigo and
   edificacionDeportiva.ede_codigo = edificacionDisciplina.ede_codigo;";
    	$stmt = $this->getEntityManager()->getConnection()->prepare($query);
    	$stmt->execute();
    	$ficha = $stmt->fetchAll();

    return $ficha;
	
	}
}
