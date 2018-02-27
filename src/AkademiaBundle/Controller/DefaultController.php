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

class DefaultController extends Controller
{

    public function entradasAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT e FROM BlogBundle:Entry e";
        $query = $em->createQuery($dql);
 
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, 
                $request->query->getInt('page', 1),
                5
        );
 
        return $this->render('AppBundle:pruebas:listado.html.twig',
                array('pagination' => $pagination));
    }

    public function contadorAction(Request $request){
      
             return $this->render('AkademiaBundle:Default:contador.html.twig' );
    }

    public function indexAction(Request $request)
    {

		if($request->isXmlHttpRequest())
        {
            if($request->request->get('persona') == "apoderado"){
                $dni = $request->request->get('dni');

                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query = "select dni,apellidoPaterno,apellidoMaterno,nombre,sexo,fechaNacimiento,(cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from ACADEMIA.apoderado where dni='$dni';";
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $po = $stmt->fetchAll();
                    
                    if($po){

                        $encoders = array(new JsonEncoder());
                        $normalizer = new ObjectNormalizer();
                        $normalizers = array($normalizer);
                        $serializer = new Serializer($normalizers, $encoders);
                        $jsonContent = $serializer->serialize($po,'json');
                        return new JsonResponse($jsonContent);

                    }else{

                        $em = $this->getDoctrine()->getEntityManager();
                        $db = $em->getConnection();
                        $query = "select perdni as dni , perapepaterno as apellidoPaterno, perapematerno as apellidoMaterno, pernombres as nombre, persexo as sexo,perfecnacimiento as fechaNacimiento, (cast(datediff(dd,perfecnacimiento,GETDATE()) / 365.25 as int)) as edad from dbo.grpersona where perdni='$dni';";
                        $stmt = $db->prepare($query);
                        $params = array();
                        $stmt->execute($params);
                        $po = $stmt->fetchAll();
                        $encoders = array(new JsonEncoder());
                        $normalizer = new ObjectNormalizer();
                        $normalizers = array($normalizer);
                        $serializer = new Serializer($normalizers, $encoders);
                        $jsonContent = $serializer->serialize($po,'json');

                        return new JsonResponse($jsonContent);  
                    }  

            }
             
            if($request->request->get('persona') == "hijo"){

                $dni = $request->request->get('dni');

                
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "select dni,apellidoPaterno,apellidoMaterno,nombre,sexo,fechaNacimiento,(cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from ACADEMIA.participante where dni='$dni'";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $po = $stmt->fetchAll();

                if($po){

                    $encoders = array(new JsonEncoder());
                    $normalizer = new ObjectNormalizer();
                    $normalizers = array($normalizer);
                    $serializer = new Serializer($normalizers, $encoders);
                    $jsonContent = $serializer->serialize($po,'json');
                    
                    return new JsonResponse($jsonContent);

                }else{

                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query = "select perdni as dni , perapepaterno as apellidoPaterno, perapematerno as apellidoMaterno, pernombres as nombre, persexo as sexo,perfecnacimiento as fechaNacimiento, (cast(datediff(dd,perfecnacimiento,GETDATE()) / 365.25 as int)) as edad from grpersona where perdni='$dni'";
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $po = $stmt->fetchAll();
                    $encoders = array(new JsonEncoder());
                    $normalizer = new ObjectNormalizer();
                    $normalizers = array($normalizer);
                    $serializer = new Serializer($normalizers, $encoders);
                    $jsonContent = $serializer->serialize($po,'json');
                    return new JsonResponse($jsonContent);  
                }            
            }
        }     

        $em2 = $this->getDoctrine()->getManager();

        $mdlDitritoCD = $em2->getRepository('AkademiaBundle:Distrito')->getDitritosCD();
        $mdlProvinciasCD = $em2->getRepository('AkademiaBundle:Distrito')->getProvinciasCD();
        $mdlDepartamentosCD = $em2->getRepository('AkademiaBundle:Distrito')->getDepartamentosCD();
        $mdlDepartamento = $em2->getRepository('AkademiaBundle:Distrito')->getDepartamentos();
        $mdlProvincia = $em2->getRepository('AkademiaBundle:Distrito')->getProvincias();
        $mdlDistrito = $em2->getRepository('AkademiaBundle:Distrito')->getDistritos();
        $mdlComplejoDeportivo = $em2->getRepository('AkademiaBundle:ComplejoDeportivo')->getComplejosDeportivos();
        $mdlComplejoDisciplina = $em2->getRepository('AkademiaBundle:ComplejoDisciplina')->getComplejoDisciplinas();

        $session = $this->isGranted('IS_AUTHENTICATED_ANONYMOUSLY');
        $Role = $this->getUser();

        if($Role == null){
            $mdlHorario = $em2->getRepository('AkademiaBundle:Horario')->getHorarios();
        }else{
            $mdlHorario = $em2->getRepository('AkademiaBundle:Horario')->getHorariosPromotores();   
        }
        
        return $this->render('AkademiaBundle:Default:index.html.twig' , array("complejosDeportivo" => $mdlComplejoDeportivo , "complejosDisciplinas" => $mdlComplejoDisciplina , "horarios" => $mdlHorario,"departamentos" => $mdlDepartamento,"provincias" => $mdlProvincia ,"distritos" => $mdlDistrito ,'ditritosCD' => $mdlDitritoCD , "departamentosCD" => $mdlDepartamentosCD ,'provinciasCD' => $mdlProvinciasCD )); 	
    }

    public function consultaAction(Request $request)
    {
        return $this->render('AkademiaBundle:Default:cuestions.html.twig');
    }


   public function registrarAction(Request $request)
   {

        if($request->isXmlHttpRequest()){

            $idHorario = $request->request->get('idHorario');

            $em = $this->getDoctrine()->getManager();
            $vacantesHorario = $em->getRepository('AkademiaBundle:Horario')->getHorariosVacantes($idHorario);
            $cantVacantes = $vacantesHorario[0]['vacantes'];
           

           if($cantVacantes > 0 ){

                //DATOS APODERADO
                $dni = $request->request->get('dni');
                $apellidoPaterno = $request->request->get('apellidoPaterno');
                $apellidoMaterno = $request->request->get('apellidoMaterno');
                $nombre = $request->request->get('nombre'); 
                $fechaNacimiento = $request->request->get('fechaNacimiento');
                $sexo = $request->request->get('sexo');
                $distrito = $request->request->get('distrito');
                $direccion = $request->request->get('direccion');
                $telefono = $request->request->get('telefono');
                $correo = $request->request->get('correo');
                $estado = $request->request->get('estado');

                //DATOS PARTICIPANTE
                $dniParticipante = $request->request->get('dniParticipante');
                $apellidoPaternoParticipante = $request->request->get('apellidoPaternoParticipante');
                $apellidoMaternoParticipante = $request->request->get('apellidoMaternoParticipante');
                $nombreParticipante = $request->request->get('nombreParticipante'); 
                $fechaNacimientoParticipante = $request->request->get('fechaNacimientoParticipante');
                $sexoParticipante = $request->request->get('sexoParticipante');
                $parentesco = $request->request->get('parentesco');
                $tipoSeguro = $request->request->get('tipoSeguro');
                $estado = 1;
                $discapacidad = $request->request->get('discapacidad');

                //REGISTRAR APODERADO

                $em = $this->getDoctrine()->getManager();
                $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dni);
            

                if(!empty($IDApoderado)){
                    
                    $idApod = $IDApoderado[0]['id'];

                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                    $apoderado = $em->find($idApod);
                    $apoderado->setDireccion($direccion);
                    $apoderado->setTelefono($telefono);
                    $apoderado->setCorreo($correo);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $idApod = $apoderado->getId();

                }else{

                    $apoderado = new Apoderado();
                    $apoderado->setDni($dni);
                    $apoderado->setApellidoPaterno($apellidoPaterno);
                    $apoderado->setApellidoMaterno($apellidoMaterno);
                    $apoderado->setNombre($nombre);
                    $apoderado->setFechaNacimiento(new \DateTime($fechaNacimiento));
                    $apoderado->setSexo($sexo);
                
                    $em = $this->getDoctrine()->getRepository(Distrito::class);
                    $buscarDistrito = $em->find($distrito);
                    $apoderado->setDistrito($buscarDistrito);
                    $apoderado->setDireccion($direccion);
                    $apoderado->setTelefono($telefono);
                    $apoderado->setCorreo($correo);    
                    $apoderado->setEstado($estado);     
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($apoderado);
                    $em->flush();

                    $idApod = $apoderado->getId();                 

                }

                $em = $this->getDoctrine()->getManager();
                $IDParticipante = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipante($dniParticipante);
        
                if(!empty($IDParticipante)){      
                   
                    $idParticipanteN = $IDParticipante[0]['id'];

                    $em = $this->getDoctrine()->getManager();
                    $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApod,$dniParticipante);
                    $em->flush(); 
                   
                }else{
                    
                    $participante = new Participante();
                    $participante->setDni($dniParticipante);
                    $participante->setApellidoPaterno($apellidoPaternoParticipante);
                    $participante->setApellidoMaterno($apellidoMaternoParticipante);
                    $participante->setNombre($nombreParticipante);
                    $participante->setFechaNacimiento(new \DateTime($fechaNacimientoParticipante));
                    $participante->setSexo($sexoParticipante);
                    $participante->setParentesco($parentesco);
                    $participante->setTipoDeSeguro($tipoSeguro);
                    $participante->setEstado($estado);
                    $participante->setDiscapacitado($discapacidad);

                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                    $buscarApoderadoInscripcion = $em->find($idApod);
                    $participante->setApoderado($buscarApoderadoInscripcion);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($participante);
                    $em->flush();

                    $idParticipanteN= $participante->getId();      

                } 

                    $idHorario = $request->request->get('idHorario');
                    $fechaInscripcion = $hoy = date("Y-m-d");
                    $inscripcion = new Inscribete();

                    $estado=1;
                    $inscripcion->setFechaInscripcion(new \DateTime($fechaInscripcion));

                    $inscripcion->setEstado($estado);
                    $em = $this->getDoctrine()->getRepository(Participante::class);
                    $buscarParticipante = $em->find($idParticipanteN);
                    $inscripcion->setParticipante($buscarParticipante);

                    $em = $this->getDoctrine()->getRepository(Horario::class);
                    $buscarHorario = $em->find($idHorario);
                    $inscripcion->setHorario($buscarHorario);            
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscripcion);
                    $em->flush();
                    $em2 = $this->getDoctrine()->getManager();
                    $mdlFicha = $em2->getRepository('AkademiaBundle:Inscribete')->getFicha($inscripcion->getId());
                                   
                    $encoders = array(new JsonEncoder());
                    $normalizer = new ObjectNormalizer();
                    $normalizer->setCircularReferenceLimit(1);
                    $normalizer->setCircularReferenceHandler(function ($object) {
                        return $object->getId();
                    });

                    $normalizers = array($normalizer);
                    $serializer = new Serializer($normalizers, $encoders);
                    $jsonContent = $serializer->serialize($mdlFicha,'json');
                    return new JsonResponse($jsonContent);  
           }else{

                $mensaje = 1;
                return new JsonResponse($mensaje);
           }
                                   
        }
    }
    
    public function pruebaAction(Request $request){
    
        $em = $this->getDoctrine()->getManager();
        $IDParticipante = $em->getRepository('AkademiaBundle:Participante')->getApoderadoBusqueda('08161415');
        return new JsonResponse($IDParticipante);
    }


    public function mostrarfichaAction(Request $request){
        if($request->isXmlHttpRequest()){

            $idFicha = $request->request->get('id');

            $em2 = $this->getDoctrine()->getManager();
            $ficha = $em2->getRepository('AkademiaBundle:Inscribete')->getFicha($idFicha);

            if(!empty($ficha)){

                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();

                $normalizer->setCircularReferenceLimit(1);
                   
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($ficha,'json');
                return new JsonResponse($jsonContent);

            }else{

                $mensaje = 1;
                return new JsonResponse($mensaje);
            }

        }

    }

    public function cambiarestadoAction(Request $request){

        
        if($request-> isXmlHttpRequest()){

            $idFicha = $request->request->get('id');
            $usuario = $this->getUser()->getId();
           
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository('AkademiaBundle:Inscribete')->cargaDatos($idFicha);

            $estadoFicha = $data[0]['estadoFicha'];
            $idParticipante = $data[0]['idParticipante'];
            $dniParticipante = $data[0]['dniParticipante'];
            $estadoParticipante = $data[0]['estadoParticipante'];
            $idApoderado = $data[0]['idApoderado'];
            $dniApoderado = $data[0]['dniApoderado'];
            $estadoApoderado = $data[0]['estadoApoderado'];
            $idHorario = $data[0]['idHorario'];

            
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository('AkademiaBundle:Inscribete')->getDobleInscripcion($idHorario,$idParticipante);


            if(!empty($data)){

                $mensaje = 3;
                return new JsonResponse($mensaje);
            
            }else {

                $em = $this->getDoctrine()->getManager();
                $data = $em->getRepository('AkademiaBundle:Inscribete')->getCantInscripciones($idParticipante);
                $cantRegistros = $data[0]['cantidadRegistros'];

                if((Int)$cantRegistros > 2){
                    
                    $mensaje = 4;
                    return new JsonResponse($mensaje);

                }else{

                    if( $estadoFicha == 0){

                        $em = $this->getDoctrine()->getManager();
                        $vacantesHorario = $em->getRepository('AkademiaBundle:Horario')->getHorariosVacantes($idHorario);
                        $cantVacantes = $vacantesHorario[0]['vacantes'];

                        if($cantVacantes > 0 ){

                            $em = $this->getDoctrine()->getManager();
                            $IDParticipante = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipante($dniParticipante);

                            if(!empty($IDParticipante)){   

                                $idParticipante = $IDParticipante[0]['id'];

                                $em = $this->getDoctrine()->getRepository(Participante::class);
                                $participante = $em->find($idParticipante);
                                $participante->setUsuarioValida($usuario);
                                $em = $this->getDoctrine()->getManager();
                                $em->flush();
                               
                                $em = $this->getDoctrine()->getManager();
                                $idApoderadoB = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dniApoderado);          

                                if(!empty($idApoderadoB)){

                                    $idApoderado = $idApoderadoB[0]['id'];

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApoderado,$idParticipante);
                                    $em->flush();

                                   
                                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                    $apoderado = $em->find($idApoderado);
                                    $apoderado->setUsuarioValida($usuario);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->flush();
                                   

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarParticipanteFicha($idParticipante,$idFicha);
                                    $em->flush(); 
                                
                                }else{

                                    
                                    $em = $this->getDoctrine()->getManager();
                                    $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getApoderadoBusqueda($dniApoderado);

                                    $idApoderado = $IDApoderado[0]['id'];

                                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                    $apoderado = $em->find($idApoderado);
                                    $apoderado->setEstado(1);
                                    $apoderado->setUsuarioValida($usuario);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->flush();

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApoderado,$idParticipante);
                                    $em->flush(); 

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarParticipanteFicha($idParticipante,$idFicha);
                                    $em->flush(); 
                                }

                            }else{

                                $em = $this->getDoctrine()->getManager();
                                $IDParticipanteExistente = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipanteApoderado($dniParticipante);
                                
                                $idParticipante = $IDParticipanteExistente[0]['id'];

                                $em = $this->getDoctrine()->getRepository(Participante::class);
                                $participante = $em->find($idParticipante);
                                $participante->setEstado(1);
                                $participante->setUsuarioValida($usuario);
                                $em = $this->getDoctrine()->getManager();
                                $em->flush();

                                $em = $this->getDoctrine()->getManager();
                                $em->getRepository('AkademiaBundle:Participante')->getActualizarParticipanteFicha($idParticipante,$idFicha);
                                $em->flush(); 

                                $em = $this->getDoctrine()->getManager();
                                $idApoderadoB = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dniApoderado);
                                

                                if(!empty($idApoderadoB)){

                                    $idApoderado = $idApoderadoB[0]['id'];

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApoderado,$idParticipante);
                                    $em->flush(); 

                                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                    $apoderado = $em->find($idApoderado);
                                    $apoderado->setUsuarioValida($usuario);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->flush();
                                   
                                }else{

                                    $em = $this->getDoctrine()->getManager();
                                    $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getApoderadoBusqueda($dniApoderado);

                                    $idApoderado = $IDApoderado[0]['id'];

                                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                    $apoderado = $em->find($idApoderado);
                                    $apoderado->setEstado(1);
                                    $apoderado->setUsuarioValida($usuario);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->flush();

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApoderado,$idParticipante);
                                    $em->flush(); 

                                    $em = $this->getDoctrine()->getManager();
                                    $em->getRepository('AkademiaBundle:Participante')->getActualizarParticipanteFicha($idParticipante,$idFicha);
                                    $em->flush(); 
                                }                  

                            }

                            $em2 = $this->getDoctrine()->getManager();
                            $ficha = $em2->getRepository('AkademiaBundle:Inscribete');
                            $estadoFicha = $ficha->find($idFicha);
                            $estadoFicha->setEstado(2);
                            $estadoFicha->setUsuarioValida($usuario);
                            $em2->persist($estadoFicha);
                            $em2->flush();

                            $em= $this->getDoctrine()->getManager();
                            $em->getRepository('AkademiaBundle:Horario')->getActualizarVacantesHorarios($idHorario);
                            $em->flush();

                            $em2 = $this->getDoctrine()->getManager();
                            $em2->getRepository('AkademiaBundle:Horario')->getAcumularInscritos($idHorario);
                            $em2->flush();

                            $em3 = $this->getDoctrine()->getManager();
                            $em3->getRepository('AkademiaBundle:Movimientos')->RegistrarMovInicial($idFicha);
                            $em3->flush();

                            $mensaje = 1;
                            return new JsonResponse($mensaje);
                            
                        }else{

                            $mensaje = 2;
                            return new JsonResponse($mensaje);
                            
                        }

                    }else if( $estadoFicha == 1){

                        $em = $this->getDoctrine()->getManager();
                        $vacantesHorario = $em->getRepository('AkademiaBundle:Horario')->getHorariosVacantes($idHorario);

                        $cantVacantes = $vacantesHorario[0]['vacantes'];

                        if($cantVacantes > 0 ){
                       
                            if ( $estadoParticipante == 1 && $estadoApoderado == 1){

                                $em = $this->getDoctrine()->getRepository(Participante::class);
                                $participante = $em->find($idParticipante);
                                $participante->setUsuarioValida($usuario);
                                $em = $this->getDoctrine()->getManager();
                                $em->flush();

                                $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                $apoderado = $em->find($idApoderado);
                                $apoderado->setUsuarioValida($usuario);
                                $em = $this->getDoctrine()->getManager();
                                $em->flush();

                                $em2 = $this->getDoctrine()->getManager();
                                $ficha = $em2->getRepository('AkademiaBundle:Inscribete');
                                $estadoFicha = $ficha->find($idFicha);
                                $estadoFicha->setEstado(2);
                                $estadoFicha->setUsuarioValida($usuario);
                                $em2->persist($estadoFicha);
                                $em2->flush();

                                $em= $this->getDoctrine()->getManager();
                                $em->getRepository('AkademiaBundle:Horario')->getActualizarVacantesHorarios($idHorario);
                                $em->flush();

                                $em= $this->getDoctrine()->getManager();
                                $em->getRepository('AkademiaBundle:Horario')->getAcumularInscritos($idHorario);
                                $em->flush();

                                $em3= $this->getDoctrine()->getManager();
                                $em3->getRepository('AkademiaBundle:Movimientos')->RegistrarMovInicial($idFicha);
                                $em3->flush();

                                $mensaje = 1;
                                return new JsonResponse($mensaje);
                            }

                        }else{

                            $mensaje = 2;
                            return new JsonResponse($mensaje);
                            
                        }
                    
                    }  

                }

            }       
        }
    }

    public function generarPdfInscripcionAction(Request $request , $id)
    {   
      
        $em2 = $this->getDoctrine()->getManager();
        $mdlFicha = $em2->getRepository('AkademiaBundle:Inscribete')->getFicha($id);
        $html = $this->renderView('AkademiaBundle:Pdf:inscripcionPdf.html.twig', ["inscripcion" => $mdlFicha]);
        $pdf = $this->container->get("white_october.tcpdf")->create();
        $pdf->SetAuthor('IPD');
        $pdf->setPrintHeader(false);
        $pdf->SetTitle('Ficha de Inscripcion');
        $pdf->SetSubject('Mecenazgo Deportivo');
        $pdf->SetKeywords('TCPDF, PDF, Mecenazgo Deportivo, IPD, Sistemas IPD, Deportistas');       
        $pdf->AddPage();
        $pdf->setCellPaddings(0, 0, 0, 0);                
        $pdf->writeHTMLCell(
                    $w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true
            );         
        $pdf->writeHTML($html);
        $pdf->Output("compromisoIPD.pdf", 'I');
    }

    public function generarPdfDeclaracionJuradaAction(Request $request , $id)
    {

        $em2 = $this->getDoctrine()->getManager();
        $mdlFicha = $em2->getRepository('AkademiaBundle:Inscribete')->getFicha($id);

        $html = $this->renderView('AkademiaBundle:Pdf:declaracionJuradaPdf.html.twig', ["inscripcion" => $mdlFicha]);
     
        $pdf = $this->container->get("white_october.tcpdf")->create();
        $pdf->SetAuthor('IPD');
		$pdf->setPrintHeader(false);
        $pdf->SetTitle('Declaracion Jurada');
        $pdf->SetSubject('Mecenazgo Deportivo');
        $pdf->SetKeywords('TCPDF, PDF, Mecenazgo Deportivo, IPD, Sistemas IPD, Deportistas');       
        $pdf->AddPage();
        $pdf->setCellPaddings(0, 0, 0, 0);                
        $pdf->writeHTMLCell(
                    $w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true
            );         
        $pdf->writeHTML($html);
        $pdf->Output("compromisoIPD.pdf", 'I');
        
    }


    public function postAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getRepository(Post::class);
            $posts = $em->findAll();

            $encoders = array(new JsonEncoder());
            $normalizer = new ObjectNormalizer();
            $normalizers = array($normalizer);
            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize($posts, 'json');

            return new JsonResponse($jsonContent);  
        }
    }

    public function sendemailAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {

            $nombre = $request->request->get('nombre');
            $email= $request->request->get('email');
            $mensaje=$request->request->get('message');
            $correo = 'coordinaciondpt@gmail.com';
            $subject = 'La Academia - Comentarios de '.$nombre;
            $message = 'Hemos recibido un nuevo comentario y/o sugerencia de la web LA ACADEMIA'. "\r\n" ."\r\n".'NOMBRE: '.$nombre. "\r\n" ."\r\n".'CORREO ELECTRÓNICO: '.$email ."\r\n"."\r\n".'COMENTARIO: '."\r\n"."\r\n". $mensaje ;
            $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($correo, $subject, $message, $headers);
            return new JsonResponse("Enviado");
        }
    }

     public function sendemailapoderadoAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $correoApoderado = $request->request->get('correo');
            $nombre = $request->request->get('nombre');
            $id = $request->request->get('id');

            $subject =  'PRE INSCRIPCION CONFIRMADA PARA '.$nombre.'';
            $message =  '<html>'.
                        '<head><title>IPD</title></head>'.
                        '<body><h2>Hola! '.$nombre.' </h2>'.
                        '<hr>'.
                        'Aquí puedes descargar tu ficha de inscripción y la declaración jurada, haz click en estos enlaces:'.
                        '<br>'.
                        '<a href="http://appweb.ipd.gob.pe/academia/web/ajax/pdf/inscripcion/'.$id.'"> Ficha de Inscripción </a>'.
                        '<br>'.
                        '<a href="http://appweb.ipd.gob.pe/academia/web/ajax/pdf/declaracion-jurada/'.$id.'"> Declaración Jurada </a>'.
                        '<br>'.
                        '<p>Acércate al complejo que eligiste para finalizar tu inscripción.</p>'.
                        '<br><br>'.
                        '<p>NO SE RESERVAN VACANTES</p>'.
                        '<br><br>'.
                        '<h3><OBLIGATORIO</h3>'.
                        '<ol><li>Presentar ficha de inscripción y declaración jurada firmada y con la huella dactilar del apoderado</li><li>DNI del menor de edad y del apoderado (original y copia).</li><li>Presentar ficha de seguro activo (SIS, EsSalud, o privado).</li><li>Foto tamaño carnet del menor de edad (actual).</li></ol>'.
                        '</body>'.
                        '</html>'
                    ;
            $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .'MIME-Version: 1.0'. "\r\n" .'Content-Type: text/html; charset=ISO-8859-1'. "\r\n";

            mail($correoApoderado,$subject,$message,$headers);
            return new JsonResponse("Enviado");

        }
    }

    public function loginAction(Request $request){


        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(

            'AkademiaBundle:Default:login.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,
            
            ));
    }

    public function inscritosAction(Request $request){
        return $this->render('AkademiaBundle:Default:inscritos.html.twig');
    }

    public function horariosAction(Request $request){

        $idComplejo = $this->getUser()->getIdComplejo();

        $em2 = $this->getDoctrine()->getManager();
        $ComplejoDisciplinas = $em2->getRepository('AkademiaBundle:ComplejoDisciplina')->getComplejosDisciplinasHorarios($idComplejo);
        $Horarios = $em2->getRepository('AkademiaBundle:Horario')->getHorariosComplejos($idComplejo);
        $Disciplinas = $em2->getRepository('AkademiaBundle:DisciplinaDeportiva')->getDisciplinasDiferentes($idComplejo);

        /*$events = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->findAll();

        if (!$events) {
            throw $this->createNotFoundException(
                'No event found'
            );
        }

        return $this->render(
            'AppBundle:Event:index.html.twig',
            array('events' => $events)
        );*/

        //$em = $this->getDoctrine()->getEntityManager();
        //$dql = "SELECT e FROM BlogBundle:Entry e";
        //$query = $em->createQuery($dql);
 
       /* $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $Horarios, 
                $request->query->getInt('page', 1),
                5
        );*/

        return $this->render('AkademiaBundle:Default:horarios.html.twig', array("complejosDisciplinas" => $ComplejoDisciplinas , "horarios" => $Horarios, "disciplinas" => $Disciplinas)); 
    }

    public function actualizarHorarioAction(Request $request){
       
        if($request->isXmlHttpRequest())
        {
            $idHorario = $request->request->get('idHorario');
            $vacantes = $request->request->get('vacantes');
            $convocatoria = $request->request->get('convocatoria');


            $em = $this->getDoctrine()->getManager();
            $em->getRepository('AkademiaBundle:Horario')->getActualizarHorarios($idHorario, $vacantes, $convocatoria);
            $em->flush();

            $em = $this->getDoctrine()->getManager();
            $dataActualizada = $em->getRepository('AkademiaBundle:Horario')->getMostrarCambios($idHorario);


            if(!empty($dataActualizada)){

                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });

                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($dataActualizada,'json');

                return new JsonResponse($jsonContent);   

            }else{

                $mensaje = 1;
                return new JsonResponse($mensaje);

            }
        }
    }

    public function ocultarHorarioAction(Request $request){
        
        if($request->isXmlHttpRequest()){
            $idHorario = $request->request->get('idHorario');

            $em = $this->getDoctrine()->getManager();
            $em ->getRepository('AkademiaBundle:Horario')->getOcultarHorario($idHorario);
            $em->flush();

            $mensaje = 1;
            return new JsonResponse($mensaje);
        }

    }

    public function mostrarHorarioIndividualAction(Request $request){
      
        if($request->isXmlHttpRequest()){

            $idHorario=$request->request->get('idHorario');
            $idDisciplina= $request->request->get('idDisciplina');

            $em = $this->getDoctrine()->getManager();
            $datosHorario = $em->getRepository('AkademiaBundle:Horario')->getHorariosIndividual($idHorario, $idDisciplina);


            if(!empty($datosHorario)){

                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });

                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($datosHorario,'json');
                return new JsonResponse($jsonContent);   

            }else{

                $mensaje = 1;
                return new JsonResponse($mensaje);
            }
        }
    }

    public function crearHorarioAction(Request $request){
        if($request->isXmlHttpRequest()){

            $horaInicio =$request->request->get('horarioInicio');
            $horaFin = $request->request->get('horarioFin');
            $edadMinima = $request->request->get('edadMinima');
            $edadMaxima = $request->request->get('edadMaxima');
            $vacantes = $request->request->get('vacantes');
            $discapacitados = $request->request->get('discapacidad');
            $turno = $request->request->get('turno');

            $idDisciplina = $request->request->get('idDisciplina');           
            $idComplejo = $this->getUser()->getIdComplejo();
            $em = $this->getDoctrine()->getManager();
            $ediCodigo = $em->getRepository('AkademiaBundle:Horario')->getCapturarEdiCodigo($idComplejo, $idDisciplina);         
            $codigoEdi = $ediCodigo[0]['edi_codigo'];
            

            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository('AkademiaBundle:Horario')->getDiferenciarHorarios($turno,$edadMinima,$edadMaxima,$horaInicio,$horaFin,$discapacitados);

            if(!empty($data)){

                $mensaje = 1;
                return new JsonResponse($mensaje);

            }else{

                $horario = new Horario();
                $horario->setVacantes($vacantes);
                $horario->setHoraInicio($horaInicio);
                $horario->setHoraFin($horaFin);
                $horario->setEdadMinima($edadMinima);
                $horario->setEdadMaxima($edadMaxima);
                $horario->setDiscapacitados($discapacitados);
                $horario->setTurno($turno);
                $horario->setConvocatoria(0);
                $horario->setEstado(1);
                $horario->setInscritos(0);

                $em = $this->getDoctrine()->getRepository(complejoDisciplina::class);
                $codigoDisciplina = $em->find($codigoEdi);
                $horario->setComplejoDisciplina($codigoDisciplina);
         
                $em = $this->getDoctrine()->getManager();
                $em->persist($horario);
                $em->flush();

                $idHorarioNuevo = $horario->getId(); 
                   
                $em = $this->getDoctrine()->getManager();
                $dataActualizada = $em->getRepository('AkademiaBundle:Horario')->getMostrarCambios($idHorarioNuevo);         

                if(!empty($dataActualizada)){
                        
                    $encoders = array(new JsonEncoder());
                    $normalizer = new ObjectNormalizer();
                    $normalizer->setCircularReferenceLimit(1);
                    $normalizer->setCircularReferenceHandler(function ($object) {
                        return $object->getId();
                    });

                    $normalizers = array($normalizer);
                    $serializer = new Serializer($normalizers, $encoders);
                    $jsonContent = $serializer->serialize($dataActualizada,'json');

                    return new JsonResponse($jsonContent);   
                
                }else{
                    $mensaje = 2;
                    return new JsonResponse($mensaje);
                }
            } 
        }
    }

    public function beneficiariosAction(Request $request, $idHorario){

        $em = $this->getDoctrine()->getManager();
        $Horarios = $em->getRepository('AkademiaBundle:Horario')->getHorarioBeneficiario($idHorario);
        $Beneficiarios = $em->getRepository('AkademiaBundle:Horario')->getBeneficiarios($idHorario);
        $Asistencias = $em->getRepository('AkademiaBundle:Asistencia')->getMostrarAsistencia();
        $Categorias = $em->getRepository('AkademiaBundle:Categoria')->getMostrarCategoria();
        $movAsis = $em->getRepository('AkademiaBundle:Movimientos')->getCantAsistencias(5,$idHorario);
        $movRet = $em->getRepository('AkademiaBundle:Movimientos')->getCantRetirados(6,$idHorario);
        $movEva = $em->getRepository('AkademiaBundle:Movimientos')->getCantEvaluados(6,$idHorario);

        return $this->render('AkademiaBundle:Default:beneficiarios.html.twig', array("horarios" => $Horarios, "beneficiarios" => $Beneficiarios, "asistencias" => $Asistencias, "categorias" => $Categorias, "asistentes" => $movAsis, "retirados" => $movRet, "seleccionados" => $movEva, "id" =>$idHorario));
    }



    public function estadoBeneficiarioAction(Request $request){
       
        if($request->isXmlHttpRequest()){

            $idFicha = $request->request->get('idFicha');
            $idAsistencia = $request->request->get('idAsistencia');
            $idCategoria = $request->request->get('idCategoria');

            $usuario = $this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $nuevoMovimiento = $em->getRepository('AkademiaBundle:Movimientos')->nuevoMovimiento($idCategoria, $idAsistencia, $idFicha,$usuario);
           
            if($idAsistencia == 6){

                $em = $this->getDoctrine()->getManager();
                $nuevoMovimiento = $em->getRepository('AkademiaBundle:Inscribete')->getBeneficiarioRetirado($idFicha);
            }


           if(empty($nuevoMovimiento)){
                $mensaje = 1;
                return new JsonResponse($mensaje);

           }else{
                $mensaje = 2;

           }    

           /* $movimiento = new Movimientos();
            $movimiento->setCategoriaId($idCategoria);
            $movimiento->setAsistenciaId($idAsistencia);
            $movimiento->setInscribeteId($idFicha);
            $movimiento->setUsuarioValida($usuario);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimiento);
            $em->flush(); */
        }
    }

    public function crearDisciplinaAction(Request $request){
        if($request->isXmlHttpRequest()){

            $idDisciplina = $request->request->get('idDisciplina');           
            $idComplejo = $this->getUser()->getIdComplejo();
            

            $disciplina = new ComplejoDisciplina();

            $em = $this->getDoctrine()->getRepository(DisciplinaDeportiva::class);
            $codigoDisciplina = $em->find($idDisciplina);
            $disciplina->setDisciplinaDeportiva($codigoDisciplina);
           
            $em = $this->getDoctrine()->getRepository(ComplejoDeportivo::class);
            $codigoComplejo = $em->find($idComplejo);
            $disciplina->setComplejoDeportivo($codigoComplejo);
     
            $em = $this->getDoctrine()->getManager();
            $em->persist($disciplina);
            $em->flush();

            $idDisciplinaNueva = $disciplina->getId(); 
               
            $em = $this->getDoctrine()->getManager();
            $dataActualizada = $em->getRepository('AkademiaBundle:DisciplinaDeportiva')->getMostrarCambios($idDisciplinaNueva);         

            if(!empty($dataActualizada)){
                    
                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });

                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($dataActualizada,'json');

                return new JsonResponse($jsonContent);   

            }else{
                $mensaje = 1;
                return new JsonResponse($mensaje);
            }
           
        }

    }



    
}
