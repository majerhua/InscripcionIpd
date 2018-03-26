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
     * @Rest\Get("/disciplina")
     */
    public function getDisciplinaAllAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager(); 
              
      $restresult = $em->getRepository('ApiRestFullAcademiaBundle:PersonaApi')->disciplinaAll();
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

}

