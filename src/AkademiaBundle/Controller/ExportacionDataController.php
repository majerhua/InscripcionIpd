<?php

namespace AkademiaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\JsonResponse; 
use AkademiaBundle\Entity\Usuarios;
use AkademiaBundle\Component\Security\Authentication\authenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ExportacionDataController extends Controller
{

  public function exportAction(Request $request)
  {

  	$ano = $request->query->get('ano');
  	$numMes = $request->query->get('mes');
  	$departamento = $request->query->get('departamento');
  	$complejo = $request->query->get('complejo');
  	$disciplina = $request->query->get('disciplina');

  	$conn = $this->get('database_connection');
    $response = new StreamedResponse(function() use($conn,$ano,$numMes,$departamento,$complejo,$disciplina) {
    	
    	$query2='';
    	//MONTH(mov.fecha_modificacion)='$numMes'
    	if( empty($numMes) && empty($departamento) )
    		$query2 = " YEAR(mov.fecha_modificacion)='$ano' ";	
    	

    	else if(!empty($numMes) && !empty($departamento) ){

            if(!empty($complejo)){

                if(!empty($disciplina))
                    $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' AND MONTH(mov.fecha_modificacion)='$numMes' AND ede.ede_codigo='$complejo' AND dis.dis_codigo='$disciplina' ";     
                else
                    $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' AND MONTH(mov.fecha_modificacion)='$numMes' AND ede.ede_codigo='$complejo' "; 
                 
            }

    		else
               $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' AND MONTH(mov.fecha_modificacion)='$numMes' ";   	
    	}

        else if(!empty($numMes) && empty($departamento) )
            $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND MONTH(mov.fecha_modificacion)='$numMes' "; 
        

    	else if(empty($numMes) && !empty($departamento)  ){

            if(!empty($complejo)){
                
                if(!empty($disciplina))
                    $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' AND ede.ede_codigo='$complejo' AND dis.dis_codigo='$disciplina' ";                        
                else
                    $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' AND ede.ede_codigo='$complejo' ";         
                
            }

            else
                $query2 = "  YEAR(mov.fecha_modificacion)='$ano' AND ubiDpto.ubidpto='$departamento' ";     
            
    	}

    	$handle = fopen('php://output','w+');
    					fputcsv($handle, ['Departamento', 'Complejo', 'Disciplina','DNI','ApellidoPaterno','ApellidoMaterno','Nombres','F.Nacimiento','Edad','Sexo','FechaMovimiento','Mes','Categoria','Asistencia','Horario','Discapacidad'],';');

    	$query1 = "SELECT ubiDpto.ubinombre Departamento, ede.ede_nombre as Complejo,dis.dis_descripcion as Disciplina,
 							grPar.perdni DNI,grPar.perapepaterno ApellidoPaterno, grPar.perapematerno ApellidoMaterno ,
 							grPar.pernombres,CONVERT(varchar, grPar.perfecnacimiento, 103) FechaNacimiento,(cast(datediff(dd,grPar.perfecnacimiento,GETDATE()) / 365.25 as int)) as edad,
						  CASE grPar.persexo
						  WHEN 0 THEN 'Masculino'
						  WHEN 1 THEN 'Femenino'
						  ELSE 'Otro' END
						  AS sexo,
							CONVERT(varchar, mov.fecha_modificacion, 103) FechaMovimiento,

                            

                          CASE MONTH(mov.fecha_modificacion) 
                          WHEN 1 THEN 'Enero'
                          WHEN 2 THEN 'Febrero'
                          WHEN 3 THEN 'Marzo'
                          WHEN 4 THEN 'Abril'
                          WHEN 5 THEN 'Mayo'
                          WHEN 6 THEN 'Junio'
                          WHEN 7 THEN 'Julio'
                          WHEN 8 THEN 'Agosto'
                          WHEN 9 THEN 'Septiembre'
                          WHEN 10 THEN 'Octubre'
                          WHEN 11 THEN 'Noviembre'
                          WHEN 12 THEN 'Diciembre'
                          ELSE 'No existe Mes' END
                          AS Mes 
                             ,
							cat.descripcion Categoria, asis.descripcion
 							Asistencia,CONVERT(varchar(100),hor.turno)+' '+CONVERT( varchar(100),CONVERT(VARCHAR(5), hor.horaInicio , 108))+' - '+CONVERT(varchar(100),CONVERT(VARCHAR(5), hor.horaFin , 108) )+' ,'+ CONVERT(varchar(40),hor.edadMinima)+' a '+ CONVERT(varchar(40),edadMaxima)+' anos' AS Horario,
                            CASE par.discapacitado
                            WHEN 0 THEN 'No'
                            WHEN 1 THEN 'Si'
                            ELSE 'No se sabe' END
                            AS Discapacidad

                 FROM ACADEMIA.inscribete AS ins
                inner join (SELECT m.inscribete_id as mov_ins_id, MAX(m.id) mov_id FROM ACADEMIA.movimientos m
                GROUP BY m.inscribete_id) ids ON ins.id = ids.mov_ins_id 
                inner join ACADEMIA.participante par on par.id = ins.participante_id
                inner join grpersona grPar on grPar.percodigo = par.percodigo
                inner join ACADEMIA.horario hor on hor.id=ins.horario_id
                inner join ACADEMIA.movimientos mov on mov.id = ids.mov_id
                inner join ACADEMIA.categoria cat on cat.id=mov.categoria_id
                inner join ACADEMIA.asistencia asis on asis.id = mov.asistencia_id
                inner join CATASTRO.edificacionDisciplina edi on edi.edi_codigo = hor.edi_codigo
                inner join CATASTRO.edificacionesdeportivas ede on ede.ede_codigo = edi.ede_codigo
                inner join CATASTRO.disciplina dis on dis.dis_codigo = edi.dis_codigo
                inner join ACADEMIA.apoderado apod on apod.id = par.apoderado_id
                inner join grpersona grApod on grApod.percodigo = apod.percodigo
                inner join grubigeo ubi on ubi.ubicodigo = grApod.perubigeo
								inner join grubigeo ubiDpto on ubiDpto.ubidpto = ubi.ubidpto
								WHERE ubiDpto.ubidistrito=0 AND ubiDpto.ubiprovincia=0 AND ubiDpto.ubidpto!=0 AND ".$query2;
			
  						
  						$query = $query1+' '+$query2;


    	$results = $conn->query($query1);
    	while($row = $results->fetch()) {
      	fputcsv($handle, array( $row['Departamento'], $row['Complejo'], $row['Disciplina'],$row['DNI'],$row['ApellidoPaterno'],$row['ApellidoMaterno'],$row['pernombres'],$row['FechaNacimiento'],$row['edad'],$row['sexo'],$row['FechaMovimiento'],$row['Mes'],$row['Categoria'],$row['Asistencia'],$row['Horario'],$row['Discapacidad'] ), ';');
    	}
    	fclose($handle);
  	});
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
    return $response;
	}

    public function exportDataAction(Request $request){

    	//$perfil = $this->getUser->getIdPerfil();
    	//$idComplejo = $this->getUser->getIdComplejo();
      
    	$perfil = $this->getUser()->getIdPerfil();
    	$idComplejo = $this->getUser()->getIdComplejo();
    	
      $em = $this->getDoctrine()->getManager();

    	if($perfil == 2){
    		
        $mdlDepartamentosExport = $em->getRepository('AkademiaBundle:Departamento')->departamentosExport();		
    		$mdlComplejoDeportivoExport = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejoDeportivoExport();
    
    	}else if($perfil == 1) {

    		$data = $em->getRepository('AkademiaBundle:Departamento')->departamentosExportFind($idComplejo);

        if(!empty($data)){
            $mdlDepartamentosExport = $em->getRepository('AkademiaBundle:Departamento')->departamentosExportFind($idComplejo);
        }else{
            $mdlDepartamentosExport = $em->getRepository('AkademiaBundle:Departamento')->departamentosExportFind2($idComplejo);
        }

        $data2 = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejoDeportivoExportFind($idComplejo);

        if(!empty($data2)){
          $mdlComplejoDeportivoExport = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejoDeportivoExportFind($idComplejo);
        }else{
          $mdlComplejoDeportivoExport = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejoDeportivoExportFind2($idComplejo);
        }





    	//	$mdlComplejoDeportivoExport = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejoDeportivoExportFind2($idComplejo);
    	}

    	$mdlDepartamentos = $em->getRepository('AkademiaBundle:Departamento')->departamentosAll();

    	$mdlDisciplinasDeportivasExport = $em->getRepository('AkademiaBundle:DisciplinaDeportiva')->disciplinaDeportivaExport();
    	
      return $this->render('AkademiaBundle:Export:export.html.twig',array('departamentosExport' => $mdlDepartamentosExport,'departamentosAll' => $mdlDepartamentos,'ComplejoDeportivoExport' => $mdlComplejoDeportivoExport,'DisciplinaDeportivaExport' => $mdlDisciplinasDeportivasExport)); 
    
    }
}
