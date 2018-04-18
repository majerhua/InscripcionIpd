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
   * @Rest\Post("/envio-mail/")
  */
  public function postAction(Request $request)
  {
    
    /*$idUser = $request->get('userId');
    $idParticipante = $request->get('participanteId');
    $comentario = $request->get('comentario');
    $organizacion = $Request->get('organizacion');
    
    $fc = $this->getDoctrine()->getManager();
    $datosParticipante = $fc->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->dataParticipante($idParticipante);

    $nombreParticipante = $datosParticipante[0]['nombre'];
    $disciplinaParticipante = $datosParticipante[0]['disciplina']; */


   /* $datosUsuario = $fc->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->dataUsuario($idUser);

    $nombre = $request->request->get('nombre');
    $email= $request->request->get('email');
    $mensaje=$request->request->get('message');
    $correo = 'consultasacademiaipd@gmail.com';
    $subject = 'La Academia - Comentarios de '.$nombre;
    $message = 'Hemos recibido un nuevo comentario y/o sugerencia de la web LA ACADEMIA'. "\r\n" ."\r\n".'NOMBRE: '.$nombre. "\r\n" ."\r\n".'CORREO ELECTRÓNICO: '.$email ."\r\n"."\r\n".'COMENTARIO: '."\r\n"."\r\n". $mensaje ;
    $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($correo, $subject, $message, $headers);
    return new JsonResponse("Enviado"); */

    /*$data = new User;
    $name = $request->get('name');
    $role = $request->get('role');
    if(empty($name) || empty($role))
    {
      return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE); 
    } 
    
    $data->setName($name);
    $data->setRole($role);
    $em = $this->getDoctrine()->getManager();
    $em->persist($data);
    $em->flush();
    return new View("User Added Successfully", Response::HTTP_OK);*/
  }

}

