<?php
namespace AkademiaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AkademiaBundle\Entity\Apoderado;
use AkademiaBundle\Entity\Departamento;
use AkademiaBundle\Entity\Provincia;
use AkademiaBundle\Entity\ComplejoDeportivo;
use AkademiaBundle\Entity\DisciplinaDeportiva;
use AkademiaBundle\Entity\Distrito;
use AkademiaBundle\Entity\Persona;
use AkademiaBundle\Entity\Participante;
use AkademiaBundle\Entity\Post;
use AkademiaBundle\Entity\Inscribete;
use AkademiaBundle\Entity\ComplejoDisciplina;
use AkademiaBundle\Entity\Horario;
use AkademiaBundle\Entity\Usuarios;
use AkademiaBundle\Component\Security\Authentication\authenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\FileBag; 
use Symfony\Component\HttpFoundation\File\UploadedFile;



class TalentosController extends controller
{
	public function evaluadosAction(Request $request){

	 	$fc = $this->getDoctrine()->getManager();
        $Seleccionados = $fc->getRepository('AkademiaBundle:Participante')->getMostrarSeleccionados();

    	return $this->render('AkademiaBundle:Default:evaluados.html.twig', array("seleccionados" => $Seleccionados ));
   
    }

    public function talentosAction(Request $request, $idParticipante){

    	$fc = $this->getDoctrine()->getManager();
    	$talento = $fc->getRepository('AkademiaBundle:Participante')->getMostrarTalento($idParticipante);
    	return $this->render('AkademiaBundle:Default:talento.html.twig',array('talento' => $talento ));
    }

    public function guardarTalentoAction(Request $request){

    	if($request->isXmlHttpRequest()){
   			
   			$idParticipante = $request->request->get('idPart');
           	$link = $request->request->get('link');
           	$imgficha = $request->request->get('archivo');
            $comentarios = $request->request->get('comentarios');
            var_dump($idParticipante);
            var_dump($link);
            var_dump($comentarios);
            var_dump($imgficha);

          	
			//$nombre = $_FILES[$imgficha]['name'];
			//$tipo = $_FILES['archivo']['type'];
			//$tamanio = $_FILES['archivo']['size'];
			

            $fileFicha = $request->files->get('archivo');
            var_dump($fileFicha);

	        if (!empty($fileFicha)){
		        $nombreFicha = date('YmdHis');
		        $fileNombre = $nombreFicha . '.' . $fileFicha->guessExtension();
		        $ruta = "bundles/upload/";
		        $fileResolucion->move($ruta, $fileNombre);
		        var_dump($fileNombre);
		    }else{

		        $fileNombre="";
		        var_dump($fileNombre);
		    }

		    var_dump($request->files->all());
            //$file = $request->files();
	   		//$fileResolucion = $file->get("ficha");
	   		

		/*	$nombre = $_FILES['archivo']['name'];
			$tipo = $_FILES['archivo']['type'];
			$tamanio = $_FILES['archivo']['size'];
			$ruta = $_FILES['archivo']['tmp_name'];
			$destino = "archivos/" . $nombre;*/


            $mensaje = 1;
            return new JsonResponse($mensaje);

          //  $fc = $this->getDoctrine()->getManager();
            
           // $registro = $fc->getRepository('AkademiaBundle:Participante')->guardarTalento($idParticipante, $link, $imgficha, $comentarios);
	        
	      /*  if(empty($registro)){

	        	$mensaje = 1;
                return new JsonResponse($mensaje);
	        
	        }else{
	        
	        	$mensaje = 2;
                return new JsonResponse($mensaje);
	        }
	        */
    	}

    }

	public function GuardarResolucionAction(Request $request){
	    
	    //$session = new Session();
	    //$User = $session->get('usuario');
	    //if (empty($User)) {
	      //  return $this->redirectToRoute('login_usuario');
	    //}
	    
	    $coduser = $User['usucodigo'];
	    $em = $this->getDoctrine()->getManager();
	   
	    $codigoresol=$request->get('txtcodigoresol');
	    $expediente = $request->get('txtexpediente');
	    $numero = $request->get('txtnumero');
	    $fecha = $request->get('txtfecha');
	    $tipo = $request->get('cbotipo');
	    $resuelve = $request->get('txtresuelve');
	    $sancion = $request->get('cbosancion');
	    
	    $file = $request->files();
	    $fileResolucion = $file->get("file");
	   
	    if (!empty($fileResolucion)){
	        $nombrefile = date('YmdHis');
	        $fileName = $nombrefile . '.' . $fileResolucion->guessExtension();
	        $ruta = "bundles/upload/$coduser/";
	        $fileResolucion->move($ruta, $fileName);
	    }else{
	        $fileName="";
	    }
	    $GuardarResolucion= $em->getRepository('ResolucionBundle:query')
	                     ->GuardarResolucion($codigoresol,$expediente ,$numero,$fecha,$tipo,$resuelve,$sancion,$fileName,$coduser);
	    echo $GuardarResolucion['msj'];
	    exit;
	}


}