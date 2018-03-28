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
            
            $fc->getRepository('AkademiaBundle:Participante')->nuevoControlIndicador($peso,$talla,$ind50mt,$flexTronco,$equilibrio,$flexBrazo,$saltoH,$lanzamiento,$saltoV,$abdominales,$milmt,$idNewControl,$usuario);

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

      $fc = $this->getDoctrine()->getManager();
      
      $idParticipante = $request->get('cod-participante');
      $link = $request->get('link');
      $comentarios = $request->get('comentarios');  


      //SUBIDA DE FICHA

      $file = $request->files;
      $fileFicha = $file->get('imagen-ficha');
   
      $nombreFicha = date('YmdHis');
      $fileFichaName = $nombreFicha.'.'.$fileFicha->guessExtension();
      $rutaFicha = "assets/images/imagesFicha/";
      $fileFicha->move($rutaFicha, $fileFichaName);

      
      //SUBIDA DE FOTO

      $fileFoto = $file->get('imagen-foto');

      $nombreFoto = date('YmdHis');
      $fileFotoName = $nombreFoto.'.'.$fileFoto->guessExtension();
      $rutaFoto = "assets/images/imagesFoto/";
      $fileFoto->move($rutaFoto, $fileFotoName);


      if(!empty($fileFicha)){
       
        //GUARDAR FICHA
        $fc->getRepository('AkademiaBundle:Participante')->guardarTalento($idParticipante, $link, $fileFichaName,$fileFotoName, $comentarios);

        $mensaje = 1;
        return new JsonResponse($mensaje);

      }else{

        $mensaje = 2;
        return new JsonResponse($mensaje);

      }

  }

  public function mostrarDetalleTalentoAction (Request $request, $idParticipante){

      $fc = $this->getDoctrine()->getManager();
      $talento = $fc->getRepository('AkademiaBundle:Participante')->getMostrarTalento($idParticipante);
      $controles = $fc->getRepository('AkademiaBundle:Participante')->getMostrarControles($idParticipante);
      $numControl = $fc->getRepository('AkademiaBundle:Participante')->getNumeroControl($idParticipante);

      return $this->render('AkademiaBundle:Default:mostrarDetalleTalento.html.twig',array('talento' => $talento, 'controles' => $controles, 'numeros' =>$numControl));

  }


  public function visibilidadAppAction(Request $request){

      $fc = $this->getDoctrine()->getManager();
      $idParticipante = $request->get('idParticipante');
      $visibilidad = $request->get('visibilidad');

      $fc->getRepository('AkademiaBundle:Participante')->actualizarVisibilidad($idParticipante, $visibilidad);
      $mensaje = 1;
      return new JsonResponse($mensaje);


  }
}