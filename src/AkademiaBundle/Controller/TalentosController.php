<?php
namespace AkademiaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AkademiaBundle\Entity\Apoderado;
use AkademiaBundle\Entity\ComplejoDeportivo;
use AkademiaBundle\Entity\DisciplinaDeportiva;
use AkademiaBundle\Entity\Persona;
use AkademiaBundle\Entity\Participante;
use AkademiaBundle\Entity\ComplejoDisciplina;
use AkademiaBundle\Entity\Usuarios;
use AkademiaBundle\Component\Security\Authentication\authenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
//use Symfony\Component\HttpFoundation\FileBag; 
//use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
    	$controles = $fc->getRepository('AkademiaBundle:Participante')->getMostrarControles($idParticipante);
    	$numControl = $fc->getRepository('AkademiaBundle:Participante')->getNumeroControl($idParticipante);
    	return $this->render('AkademiaBundle:Default:talento.html.twig',array('talento' => $talento, 'controles' => $controles, 'numeros' =>$numControl));
    }


    public function mostrarTalentosAction(Request $request){

        $fc = $this->getDoctrine()->getManager();
        $talentos = $fc->getRepository('AkademiaBundle:Participante')->listarTalentos();

        return $this->render('AkademiaBundle:Default:mostrarTalentos.html.twig', array("talentos" => $talentos));
    }



    public function nuevoControlAction(Request $request){
    	if($request->isXmlHttpRequest()){

    		$fc = $this->getDoctrine()->getManager();

    		$usuario = $this->getUser()->getId();
	   
    		$peso = $request->get('peso');
  			$talla = $request->get('talla');
  			$ind50mt = $request->get('ind50mt');
  			$flexTronco = $request->get('flexTronco');
  			$equilibrio = $request->get('equilibrio');
  			$flexBrazo = $request->get('flexBrazo');
  			$saltoH = $request->get('saltoH');
  			$saltoV = $request->get('saltoV');
  			$lanzamiento = $request->get('lanzamiento');
  			$abdominales = $request->get('abdominales');
  			$milmt = $request->get('milmt');
  			$idParticipante = $request->get('idParticipante');
        $idInscribete = $request->get('idInscribete');
  			$fechaDato = $request->get('fechaDato');
        //$fechaDato=  $request->get(new \DateTime('fechaDato'));

        //$fechita = new \DateTime($fechaDato);
  			//$fechaHoy = date('Y-m-d');
  	
  
  			$nuevoControl = $fc->getRepository('AkademiaBundle:Participante')->nuevoControl($fechaDato, $idParticipante, $usuario);

  			if($nuevoControl == 1){

          $cantidadControl = $fc->getRepository('AkademiaBundle:Participante')->cantidadControl($idParticipante);
          $num = $cantidadControl[0]['cantidad'];
       

          if($num == 1 ){
              /*CREAR UN MOVIMIENTO DE EVALUADO AUTOMATICAMENTE DEL PARTICIPANTE*/
              $fc->getRepository('AkademiaBundle:Participante')->registrarMovEva($idInscribete,$usuario);
          }

  				$idControl = $fc->getRepository('AkademiaBundle:Participante')->retornoIdControl($idParticipante);
  				$idNuevoControl = $idControl[0]['id'];
          $idNewControl = intval($idNuevoControl);

    			if(!empty($idNuevoControl)){
  				  
            //echo "entro a la creacion de nuevos indicadores";
  					$fc->getRepository('AkademiaBundle:Participante')->nuevoControlIndicador($peso,$talla,$ind50mt,$flexTronco,$equilibrio,$flexBrazo,$saltoH,$lanzamiento,$saltoV,$abdominales,$milmt,$idNewControl,$usuario);
            
           // echo "seguimos aquii";
            //var_dump($nuevoControl);

  					$mensaje = 1;
  					return new JsonResponse($mensaje);
  
  				}else{
  					$mensaje = 3;
  			  	return new JsonResponse($mensaje);
  				}
  
  			}else{
  				$mensaje = 2;
          return new JsonResponse($mensaje);
  			}
  
    	}	
    }

    //NUEVO TALENTO
    public function nuevoTalentoAction(Request $request){

      $fc = $this->getDoctrine()->getManager();
      $usuario = $this->getUser()->getId();

      $idInscribete = $request->request->get('idInscribete');
      $idParticipante = $request->request->get('idParticipante');

      $cantidadControl = $fc->getRepository('AkademiaBundle:Participante')->cantidadControl($idParticipante);
      $num = $cantidadControl[0]['cantidad'];

      if($num != 0 ){
        
        $fc-> getRepository('AkademiaBundle:Participante')->registrarMovTal($idInscribete,$usuario);             
        $mensaje = 1;
        return new JsonResponse($mensaje);
        
      }else{
      
        $mensaje = 2;
        return new JsonResponse($mensaje);
      }
    }


    public function mostrarControlesIndAction(Request $request){

    	if($request->isXmlHttpRequest()){

    		$fc = $this->getDoctrine()->getManager();
    		$idParticipante = $request->get('idParticipante');
    		$idControl = $request->get('idControl');

    		$datos = $fc->getRepository('AkademiaBundle:Participante')->listarControlInd($idParticipante, $idControl);

			if(!empty($datos)){
            
                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($datos,'json');
                return new JsonResponse($jsonContent);   
            
            }else{

                $mensaje = 1;
                return new JsonResponse($mensaje);
            }

    	}

    }

  public function actualizarControlAction(Request $request){
    
    if($request->isXmlHttpRequest()){

        $fc = $this->getDoctrine()->getManager();
        $usuario = $this->getUser()->getId();
     
        $peso = $request->get('peso');
        $talla = $request->get('talla');
        $ind50mt = $request->get('ind50mt');
        $flexTronco = $request->get('flexTronco');
        $equilibrio = $request->get('equilibrio');
        $flexBrazo = $request->get('flexBrazo');
        $saltoH = $request->get('saltoH');
        $saltoV = $request->get('saltoV');
        $lanzamiento = $request->get('lanzamiento');
        $abdominales = $request->get('abdominales');
        $milmt = $request->get('milmt');
        $idControl = $request->get('idControl');
        $fechaDato = $request->get('fechaDato');
      

        $controlActualizado = $fc->getRepository('AkademiaBundle:Participante')->actualizarControlIndicador($fechaDato,$peso,$talla,$ind50mt,$flexTronco,$equilibrio,$flexBrazo,$saltoH,$lanzamiento,$saltoV,$abdominales,$milmt,$idControl);

        if($controlActualizado == 1){

           $mensaje = 1;
           return new JsonResponse($mensaje);
        
        }else{
           
           $mensaje = 2;
           return new JsonResponse($mensaje);
        }
    
    }
  }

  public function guardarTalentoAction(Request $request){

      $em = $this->getDoctrine()->getManager();
      
      $link = $request->get('link');
      $comentarios = $request->get('comentarios');     
      $file = $request->files;
      $filefoto = $file->get("imagen");

      var_dump($file);

      exit;

    /*  if (!empty($fileResolucion)){
          
          $nombreFicha = date('YmdHis').'.png';
          $fileNombre = $nombreFicha . '.' . $fileResolucion->guessExtension();
          $ruta = "images/upload/";
          $fileResolucion->move($ruta, $nombreFicha);
          //  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
           // var_dump($fileResolucion);
        //}else{

          //  $fileNombre="";
           // var_dump($fileNombre);
      }*/

    




  }

	public function GuardarResolucionAction(Request $request){
	    

       
	    //$session = new Session();-
	    //$User = $session->get('usuario');
	    //if (empty($User)) {
	      //  return $this->redirectToRoute('login_usuario');
	    //}
	    
	    /*$coduser = $User['usucodigo'];
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
	    exit;*/
	}

  public function mostrarDetalleTalentoAction (Request $request, $idParticipante){

      $fc = $this->getDoctrine()->getManager();
      $talento = $fc->getRepository('AkademiaBundle:Participante')->getMostrarTalento($idParticipante);
      $controles = $fc->getRepository('AkademiaBundle:Participante')->getMostrarControles($idParticipante);
      $numControl = $fc->getRepository('AkademiaBundle:Participante')->getNumeroControl($idParticipante);

      return $this->render('AkademiaBundle:Default:mostrarDetalleTalento.html.twig',array('talento' => $talento, 'controles' => $controles, 'numeros' =>$numControl));

  }


}