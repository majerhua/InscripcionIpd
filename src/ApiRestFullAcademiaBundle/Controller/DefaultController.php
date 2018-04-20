<?php

namespace ApiRestFullAcademiaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use ApiRestFullAcademiaBundle\Entity\PersonaApi;

class DefaultController extends FOSRestController
{

  /**
   * @Rest\Get("/beneficiario")
   */
  public function getBeneficiarioAllFindAction(Request $request)
  {
    $inicio = $request->get('inicio');
    $fin = $request->get('fin');
    $idDisciplina = $request->get('disciplina');

    $em = $this->getDoctrine()->getManager(); 
   
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->beneficiarioAllFind($inicio,$fin,$idDisciplina);
    if ( $restresult === null ) {
      return new View("No existen Beneficiarios", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/disciplinaGeneral")
   */
  public function disciplinaAllGeneralAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->disciplinaAllGeneral();
    if ($restresult === null) {
      return new View("No existen Disciplinas", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/departamento")
   */
  public function getDepartamentoAllAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->departamentoAll();
    if ($restresult === null) {
      return new View("No existen Departamentos", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/provincia")
   */
  public function getProvinciaAllAction(Request $request)
  {
    $departamentoId = $request->get('departamentoId');

    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->provinciaAll($departamentoId);
    if ($restresult === null) {
      return new View("No existen Provincias", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }


  /**
   * @Rest\Get("/distrito")
   */
  public function getDistritoAllAction(Request $request)
  {
    $departamentoId = $request->get('departamentoId');
    $provinciaId = $request->get('provinciaId');

    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->distritoAll($departamentoId,$provinciaId);
    if ($restresult === null) {
      return new View("No existen Provincias", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/complejo")
   */
  public function complejoDeportivoAllAction(Request $request)
  {
    $ubicodigo = $request->get('ubicodigo');

    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->complejoDeportivoAll($ubicodigo);
    if ($restresult === null) {
      return new View("No existen Provincias", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/disciplina")
   */
  public function disciplinaAllAction(Request $request)
  {
    $complejoId = $request->get('complejoId');
      
    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->disciplinaAll($complejoId);
    if ($restresult === null) {
      return new View("No existen Provincias", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Get("/indicadores")
   */
  public function indicadoresTalentoAction(Request $request)
  {
      $idParticipante = $request->get('participanteId');

      $fc = $this->getDoctrine()->getManager();
      $indicadores = $fc->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->indicadoresTalento($idParticipante);

      if($indicadores === null){
        return new View("Error al mostrar los datos", Response::HTTP_NOT_FOUND);
      }

      return $indicadores;
  }

  /**
   * @Rest\Get("/evolucion")
   */
  public function evolucionTalentoAction(Request $request)
  {
    $participanteId = $request->get('participanteId');
    $indicadorId = $request->get('indicadorId');

    $fc = $this->getDoctrine()->getManager();
    $evolucion = $fc->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->controlesTalento($participanteId,$indicadorId);

    if( $evolucion === null){
      return new View("Se produjo un error en la petición", Response::HTTP_NOT_FOUND);
    }
    return $evolucion;

  }

  /**
   * @Rest\Get("/beneficiario-all-filter")
   */
  public function beneficiarioAllFilterAction(Request $request)
  {

    $inicio = $request->get('inicio');
    $fin = $request->get('fin');
    $anio = $request->get('anio');
    $departamentoId = $request->get('departamentoId');
    $provinciaId = $request->get('provinciaId');
    $distritoId = $request->get('distritoId');
    $complejoId = $request->get('complejoId');
    $disciplinaId = $request->get('disciplinaId');
     
    $em = $this->getDoctrine()->getManager(); 
            
    $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->beneficiarioAllFilter($anio ,$departamentoId,$provinciaId,$distritoId,$complejoId,$disciplinaId,$inicio,$fin);

    if ($restresult === null) {
      return new View("No exite Beneficiario", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }

  /**
   * @Rest\Post("/envio-mail")
  */
  public function envioEmail(Request $request)
  {
    
    $idUser = $request->get('userId');
    $idParticipante = $request->get('participanteId');
    $comentario = $request->get('comentario');

    if(empty($idUser) || empty($idParticipante) || empty($comentario)){
      
      return new view("LOS DATOS DE ENVIO NO ESTAN COMPLETOS", Response::HTTP_NOT_ACCEPTABLE);

    }else{

      //DATOS PARTICIPANTE
      $fc = $this->getDoctrine()->getManager();
      $datosParticipante = $fc->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->dataParticipante($idParticipante);


      $id = $datosParticipante[0]['id'];
      $dniParticipante = $datosParticipante[0]['dni'];
      $nombreParticipante = $datosParticipante[0]['nombre'];
      $disciplinaParticipante = $datosParticipante[0]['disciplina'];
      $complejo = $datosParticipante[0]['nombreComplejo'];
      $departamento = $datosParticipante[0]['departamento'];

      //DATOS DEL INTERESADOO
      $em = $this->getDoctrine()->getManager();
      $datosUsuario = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->dataUsuario($idUser);

      $nombreUsuario = $datosUsuario[0]['nombre'];
      $correoUsuario = $datosUsuario[0]['correo'];
      $dniUsuario = $datosUsuario[0]['dni'];
      $organizacionUsuario = $datosUsuario[0]['organizacion'];
      $telefonoUsuario = $datosUsuario[0]['telefono'];


      $correo = 'isabel1625.luna@gmail.com';
      
      $subject = 'Reclutamiento de talento por '. $nombreUsuario.'';

      $message =  '<html>'.
                  '<head><title>Talento</title></head>'.
                  '<body><h3> ¡ Talento detectado ! </h3>'.
                  $nombreUsuario.' con DNI: '.$dniUsuario .', de la organización <strong>'. $organizacionUsuario. '</strong>, desea contactar al talento deportivo: <br> Código de matrícula:  '.$id.'<br> Nombre: '.$nombreParticipante.' <br> DNI : '. $dniParticipante .' ,<br> Disciplina: '. $disciplinaParticipante.' <br> Complejo deportivo: '.$complejo .' <br> Departamento: '. $departamento .'. <br><br><h4> Descripción del mensaje </h4> <br>'. $comentario.
                  '<br><br>'.
                  ' Para responder al mensaje, puede contactarse con el solicitante al: <br> Celular/Teléfono: '.$telefonoUsuario.
                  '<br> Correo: '.$correoUsuario . 
                  '</body>'.
                  '</html>';
      
      $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .'MIME-Version: 1.0'. "\r\n" .'Content-Type: text/html; charset=UTF-8'. "\r\n";
      mail($correo,$subject,$message,$headers); 
           
      return new view("El mensaje ha sido enviado satisfactoriamente", Response::HTTP_OK);

    }

  }

  /**
   * @Rest\Post("/registrarUsuario")
   */
  public function registrarUsuarioAction(Request $request)
  {

    $nombre = $request->get('nombre');
    $paterno = $request->get('paterno');
    $materno = $request->get('materno');
    $numeroDoc = $request->get('numeroDoc');
    $telefono = $request->get('telefono');
    $correo = $request->get('correo');
    $organizacion = $request->get('organizacion');
    $password = $request->get('password');
    $fechaNacimiento = $request->get('fechaNacimiento');
    $sexo = $request->get('sexo');
    $tipoDoc = $request->get('tipoDoc');
    $estado = 0;

    $em = $this->getDoctrine()->getManager(); 
    
    if( !empty($nombre) &&  !empty($paterno) && !empty($materno)  &&  !empty($numeroDoc)  &&  !empty($telefono) && !empty($correo)   && !empty($password) ){

      $token = md5( uniqid() );

      $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->registrarUsuario($nombre ,$paterno,$materno,$numeroDoc,$telefono,$correo,$organizacion,$estado,$password,$token,$fechaNacimiento,$sexo,$tipoDoc);
      //if($restresult == 1){
        $subject =  'La Academia App Ipd';
        $message =  '<html>'.
                    '<head><title>La Academia App</title></head>'.
                    '<body><h2>Binvenido a la Academia app estimado(a)! '.$nombre.' </h2>'.
                    '<a href="http://10.10.118.10/Akademia/web/activacion/cuenta/'.$token.'">Activa tu cuenta aquí </a>'.
                    '</body>'.
                    '</html>';
                
        $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .'MIME-Version: 1.0'. "\r\n" .'Content-Type: text/html; charset=UTF-8'. "\r\n";
        mail($correo,$subject,$message,$headers);
      //}
    }else{
      
      return new View("No se pudo registrar", Response::HTTP_NOT_FOUND);
    }

    return $restresult;
  }

  /**
   * @Rest\Get("/loginApp")
   */
  public function loginUsuarioAppAction(Request $request)
  {

    $correo = $request->get('correo');
    $password = $request->get('password');

    $em = $this->getDoctrine()->getManager(); 
    
    if( !empty($correo)   && !empty($password) ){
      
      $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->loginUsuarioApp($correo,$password);
      
    }else{
      
      return new View("No se pudo registrar", Response::HTTP_NOT_FOUND);
    }

    return $restresult;
  }

}

