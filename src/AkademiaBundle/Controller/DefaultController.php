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
    public function contadorAction(Request $request){
      
        return $this->render('AkademiaBundle:Default:contador.html.twig' );
    }


    public function registroFinalAction(Request $request,$estado){

        $Role = $this->getUser();

        $edadBeneficiario =$request->request->get('edadBeneficiario');
        $em = $this->getDoctrine()->getManager();

        if($Role != NULL){
            
            $mdlDepartamentosFlag = $em->getRepository('AkademiaBundle:Departamento')->departamentosPromotor($estado);
            $mdlProvinciasFlag = $em->getRepository('AkademiaBundle:Provincia')->provinciasPromotor($estado);
            $mdlDistritosFlag = $em->getRepository('AkademiaBundle:Distrito')->distritosPromotor($estado);
            $mdlDepartamentos = $em->getRepository('AkademiaBundle:Departamento')->departamentosAll();
            $mdlProvincias = $em->getRepository('AkademiaBundle:Provincia')->provinciasAll();
            $mdlDistritos = $em->getRepository('AkademiaBundle:Distrito')->distritosAll();
            $mdlComplejosDeportivosFlag = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejosDeportivosPromotor($estado);
            $mdlDisciplinasDeportivasFlag = $em->getRepository('AkademiaBundle:DisciplinaDeportiva')->disciplinasDeportivasPromotor($estado);
            $mdlhorariosFlag = $em->getRepository('AkademiaBundle:Horario')->getHorariosPromotores($estado);
            
        }else{
    
            $mdlDepartamentosFlag = $em->getRepository('AkademiaBundle:Departamento')->departamentosFlagAll($estado);
            $mdlProvinciasFlag = $em->getRepository('AkademiaBundle:Provincia')->provinciasFlagAll($estado);
            $mdlDistritosFlag = $em->getRepository('AkademiaBundle:Distrito')->distritosFlagAll($estado);
            $mdlDepartamentos = $em->getRepository('AkademiaBundle:Departamento')->departamentosAll();
            $mdlProvincias = $em->getRepository('AkademiaBundle:Provincia')->provinciasAll();
            $mdlDistritos = $em->getRepository('AkademiaBundle:Distrito')->distritosAll();
            $mdlComplejosDeportivosFlag = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->complejosDeportivosFlagAll($estado,$edadBeneficiario);
            $mdlDisciplinasDeportivasFlag = $em->getRepository('AkademiaBundle:DisciplinaDeportiva')->disciplinasDeportivasFlagAll($estado,$edadBeneficiario);
            $mdlhorariosFlag = $em->getRepository('AkademiaBundle:Horario')->horariosFlagAll($estado,$edadBeneficiario);
        }

        if($estado == 1){

            $mensaje = 'Sólo para participantes con discapacidad.';
        }else{
            $mensaje = '';
        }

        return $this->render('AkademiaBundle:Default:registroFinal.html.twig' , array('departamentosFlag' => $mdlDepartamentosFlag , "provinciasFlag" => $mdlProvinciasFlag ,'distritosFlag' => $mdlDistritosFlag,'departamentos'=>$mdlDepartamentos,'provincias'=>$mdlProvincias,'distritos'=>$mdlDistritos, 'complejosDeportivos' => $mdlComplejosDeportivosFlag, 'disciplinasDeportivas' => $mdlDisciplinasDeportivasFlag ,'horarios' => $mdlhorariosFlag, 'mensaje' => $mensaje ));     
    }
    
    public function indexAction(Request $request){
        /*
        $Role = $this->getUser()->getIdPerfil();
        echo $Role;
        var_dump($Role);
        if(empty($Role)){
            echo "1";
        }else{
            echo "2";
        }

        exit;
        */
        if($request->isXmlHttpRequest()){
            
            $parentesco = $request->request->get('persona');
            
            if($parentesco == "apoderado" || $parentesco == "hijo"){
                $dni = $request->request->get('dni');
                $refAp = $this->getDoctrine()->getManager();
                $datos = $refAp->getRepository('AkademiaBundle:Apoderado')->busquedaDni($dni);
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

        $em2 = $this->getDoctrine()->getManager();
        $mdlDitritoCD = $em2->getRepository('AkademiaBundle:Distrito')->getDitritosCD();
        $mdlProvinciasCD = $em2->getRepository('AkademiaBundle:Distrito')->getProvinciasCD();
        $mdlDepartamentosCD = $em2->getRepository('AkademiaBundle:Distrito')->getDepartamentosCD();
        $mdlDepartamento = $em2->getRepository('AkademiaBundle:Distrito')->getDepartamentos();
        $mdlProvincia = $em2->getRepository('AkademiaBundle:Distrito')->getProvincias();
        $mdlDistrito = $em2->getRepository('AkademiaBundle:Distrito')->getDistritos();
        $mdlComplejoDeportivo = $em2->getRepository('AkademiaBundle:ComplejoDeportivo')->getComplejosDeportivos();
        $mdlComplejoDisciplina = $em2->getRepository('AkademiaBundle:ComplejoDisciplina')->getComplejoDisciplinas();
    
        
        return $this->render('AkademiaBundle:Default:index.html.twig' , array("complejosDeportivo" => $mdlComplejoDeportivo , "complejosDisciplinas" => $mdlComplejoDisciplina , "departamentos" => $mdlDepartamento,"provincias" => $mdlProvincia ,"distritos" => $mdlDistrito ,'ditritosCD' => $mdlDitritoCD , "departamentosCD" => $mdlDepartamentosCD ,'provinciasCD' => $mdlProvinciasCD ));     
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
                 //var_dump($distrito);

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
                $percodigoApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderadoPersona($dni);
                if(!empty($percodigoApoderado)){
            
                    $em = $this->getDoctrine()->getManager();
                    $codigo = $em->getRepository('AkademiaBundle:Apoderado')->maxDniPersona($dni);
                    $percodigoApod = $codigo[0]['percodigo'];
                   
                    $em = $this->getDoctrine()->getManager();

                    $em->getRepository('AkademiaBundle:Apoderado')->actualizarPersona($apellidoPaterno, $apellidoMaterno, $nombre, $fechaNacimiento, $percodigoApod, $telefono, $correo, $direccion, intval($distrito),$sexo);

                    //Búsqueda en Academia.apoderado
                    $em = $this->getDoctrine()->getManager();
                    $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dni);
                
                    if(!empty($IDApoderado)){
                        $em = $this->getDoctrine()->getManager();
                        $codigo = $em->getRepository('AkademiaBundle:Apoderado')->maxDniAcademiaApod($dni);
                        $idApoderado = $codigo[0]['id'];
                        $em = $this->getDoctrine()->getRepository(Apoderado::class);
                        $apoderado = $em->find($idApoderado);
                        $apoderado->setPercodigo($percodigoApod);
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                        $idApod = $apoderado->getId();
                    }else{
                        $apoderado = new Apoderado();
                        $apoderado->setDni($dni);
                        $apoderado->setPercodigo($percodigoApod);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($apoderado);
                        $em->flush();
                        $idApod = $apoderado->getId();                 
                    }
                }else{
                    
                    //si no existe apoderado en grpersona, registramos al usuario
                    $em = $this->getDoctrine()->getManager();
                    $datosApoderado = $em->getRepository('AkademiaBundle:Apoderado')->guardarPersona($dni,$apellidoPaterno,$apellidoMaterno, $nombre,$fechaNacimiento,$sexo,$telefono, $correo, $direccion,intval($distrito));
                    //retornar el percodigo del nuevo registro
                    $em = $this->getDoctrine()->getManager();
                    $percodigoApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderadoPersona($dni);
                    $percodigoApod = $percodigoApoderado[0]['id'];
                    //Búsqueda en Academia.apoderado
                    $em = $this->getDoctrine()->getManager();
                    $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dni);
                
                    if(!empty($IDApoderado)){
                        $em = $this->getDoctrine()->getManager();
                        $codigo = $em->getRepository('AkademiaBundle:Apoderado')->maxDniAcademiaApod($dni);
                        $idApoderado = $codigo[0]['id'];
                        $em = $this->getDoctrine()->getRepository(Apoderado::class);
                        $apoderado = $em->find($idApoderado);
                        $apoderado->setPercodigo($percodigoApod);
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                        $idApod = $apoderado->getId();
                    }else{
                        $apoderado = new Apoderado();
                        $apoderado->setDni($dni);
                        $apoderado->setPercodigo($percodigoApod);
                                  
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($apoderado);
                        $em->flush();
                        $idApod = $apoderado->getId();                 
                    }
                } 
            
                //REGISTRAR PARTICIPANTE
                $em = $this->getDoctrine()->getManager();
                $percodigoParticipante = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipantePersona($dniParticipante);
            
                if(!empty($percodigoParticipante)){
            
                    $em = $this->getDoctrine()->getManager();
                    $codigo = $em->getRepository('AkademiaBundle:Apoderado')->maxDniPersona($dniParticipante);
                    $percodigoPart = $codigo[0]['percodigo'];
                    
                    $em = $this->getDoctrine()->getManager();

                    $em->getRepository('AkademiaBundle:Apoderado')->actualizarPersona($apellidoPaternoParticipante,$apellidoMaternoParticipante,$nombreParticipante,$fechaNacimientoParticipante, $percodigoPart, $telefono, $correo, $direccion, intval($distrito), $sexoParticipante);
                    
                    // Búsqueda en academia.participantes 
                    $em = $this->getDoctrine()->getManager();
                    $IDParticipante = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipante($dniParticipante);
        
                    if(!empty($IDParticipante)){      
                        $em = $this->getDoctrine()->getManager();
                        $codigo = $em->getRepository('AkademiaBundle:Participante')->maxDniAcademiaPart($dniParticipante);
                        $idParticipante = $codigo[0]['id'];
                        $em = $this->getDoctrine()->getRepository(Participante::class);
                        $participante = $em->find($idParticipante);
                        $participante->setPercodigo($percodigoPart);
                       
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                        $idParticipanteN = $participante->getId();

                        $em = $this->getDoctrine()->getManager();
                        $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApod, $idParticipanteN);  
                       
                    }else{
                        
                        $participante = new Participante();
                        $participante->setDni($dniParticipante);
                        $participante->setParentesco($parentesco);
                        $participante->setTipoDeSeguro($tipoSeguro);
                        $participante->setDiscapacitado($discapacidad);
                        $participante->setPercodigo($percodigoPart);
                        $em = $this->getDoctrine()->getRepository(Apoderado::class);
                        $buscarApoderadoInscripcion = $em->find($idApod);
                        $participante->setApoderado($buscarApoderadoInscripcion);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($participante);
                        $em->flush();
                        $idParticipanteN= $participante->getId();  

                    } 

                }else{
                    
                    //si no existe participante en grpersona, registramos al usuario
                    $em = $this->getDoctrine()->getManager();
                    $datosParticipante = $em->getRepository('AkademiaBundle:Apoderado')->guardarPersona($dniParticipante,$apellidoPaternoParticipante,$apellidoMaternoParticipante, $nombreParticipante,$fechaNacimientoParticipante,$sexoParticipante,$telefono, $correo, $direccion,intval($distrito));
                   
                    //retornar el percodigo del nuevo registro
                    $em = $this->getDoctrine()->getManager();
                    $percodigoParticipante = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderadoPersona($dniParticipante);
                    $percodigoPart = $percodigoParticipante[0]['id'];
                    
                    // Búsqueda en academia.participantes 
                    $em = $this->getDoctrine()->getManager();
                    $IDParticipante = $em->getRepository('AkademiaBundle:Participante')->getbuscarParticipante($dniParticipante);
            
                   
                    if(!empty($IDParticipante)){      
                        $em = $this->getDoctrine()->getManager();
                        $codigo = $em->getRepository('AkademiaBundle:Participante')->maxDniAcademiaPart($dniParticipante);
                        $idParticipante = $codigo[0]['id'];
                        $em = $this->getDoctrine()->getRepository(Participante::class);
                        $participante = $em->find($idParticipante);
                        $participante->setPercodigo($percodigoPart);
                                   
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                        $idParticipanteN = $participante->getId();

                        $em = $this->getDoctrine()->getManager();
                        $em->getRepository('AkademiaBundle:Participante')->getActualizarApoderado($idApod, $idParticipanteN);
                       
                    }else{
                        
                        //Participantes nuevos academia.participante
                        $participante = new Participante();
                        $participante->setDni($dniParticipante);
                        $participante->setParentesco($parentesco);
                        $participante->setTipoDeSeguro($tipoSeguro);
                        $participante->setDiscapacitado($discapacidad);
                        $participante->setPercodigo($percodigoPart);
                        $em = $this->getDoctrine()->getRepository(Apoderado::class);
                        $buscarApoderadoInscripcion = $em->find($idApod);
                        $participante->setApoderado($buscarApoderadoInscripcion);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($participante);
                        $em->flush();
                        $idParticipanteN= $participante->getId();      

                      
                    } 
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
            $idApoderado = $data[0]['idApoderado'];
            $dniApoderado = $data[0]['dniApoderado'];
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
                

                if(intval($cantRegistros) >= 1){
                
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
                            $em3->getRepository('AkademiaBundle:Movimientos')->RegistrarMovInicial($idFicha, $usuario);
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
                       
                       //     if ( $estadoParticipante == 1 && $estadoApoderado == 1){
                                $em = $this->getDoctrine()->getRepository(Participante::class);
                                $participante = $em->find($idParticipante);
                               // $participante->setUsuarioValida($usuario);
                                $em = $this->getDoctrine()->getManager();
                                $em->flush();
                                $em = $this->getDoctrine()->getRepository(Apoderado::class);
                                $apoderado = $em->find($idApoderado);
                                //$apoderado->setUsuarioValida($usuario);
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
                                $em3->getRepository('AkademiaBundle:Movimientos')->RegistrarMovInicial($idFicha, $usuario);
                                $em3->flush();
                                $mensaje = 1;
                                return new JsonResponse($mensaje);
                         //   }
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
        exit;
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
        exit;

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
                        '<br>'.
                        '<p>NO SE RESERVAN VACANTES</p>'.
                        '<br>'.
                        '<h2><OBLIGATORIO</h2>'.
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


    public function panelAction(Request $request){

        $idComplejo = $this->getUser()->getIdComplejo();
        $em = $this->getDoctrine()->getManager();
        $Nombre = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->nombreComplejo($idComplejo);

        return $this->render('AkademiaBundle:Default:menuprincipal.html.twig', array("nombre"=>$Nombre));
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
        $Nombre = $em2->getRepository('AkademiaBundle:ComplejoDeportivo')->nombreComplejo($idComplejo);
       
        return $this->render('AkademiaBundle:Default:horarios.html.twig', array("complejosDisciplinas" => $ComplejoDisciplinas , "horarios" => $Horarios, "disciplinas" => $Disciplinas, "nombre" => $Nombre)); 

    }
    
    public function actualizarHorarioAction(Request $request){
       
        if($request->isXmlHttpRequest())
        {
            $idHorario = $request->request->get('idHorario');
            $vacantes = $request->request->get('vacantes');
            $convocatoria = $request->request->get('convocatoria');
            $usuario = $this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('AkademiaBundle:Horario')->getActualizarHorarios($idHorario, $vacantes, $convocatoria, $usuario);
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
            $usuario = $this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $em ->getRepository('AkademiaBundle:Horario')->getOcultarHorario($idHorario, $usuario);
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
            $usuario = $this->getUser()->getId();
            $idDisciplina = $request->request->get('idDisciplina');           
            $idComplejo = $this->getUser()->getIdComplejo();
            $em = $this->getDoctrine()->getManager();
           
            $ediCodigo = $em->getRepository('AkademiaBundle:Horario')->getCapturarEdiCodigo($idComplejo, $idDisciplina);         
            $codigoEdi = $ediCodigo[0]['edi_codigo'];
            
            $em = $this->getDoctrine()->getManager();

            $data = $em->getRepository('AkademiaBundle:Horario')->getDiferenciarHorarios($turno,$edadMinima,$edadMaxima,$horaInicio,$horaFin,$discapacitados,$codigoEdi);

            if(!empty($data)){
                $mensaje = 1;
                return new JsonResponse($mensaje);
            }else{
                if($discapacitados == 1){
              
                    $em = $this->getDoctrine()->getManager();
                    $cambios = $em->getRepository('AkademiaBundle:ComplejoDeportivo')->getEditarDiscapacitado($idComplejo, $usuario);
                    $em2 = $this->getDoctrine()->getManager();
                    $cambiosDisciplina = $em2->getRepository('AkademiaBundle:DisciplinaDeportiva')->getEditarDiscapacitado($idDisciplina, $usuario);
                }
                $horario = new Horario();
                $horario->setVacantes($vacantes);
                $horario->setHoraInicio($horaInicio);
                $horario->setHoraFin($horaFin);
                $horario->setEdadMinima($edadMinima);
                $horario->setEdadMaxima($edadMaxima);
                $horario->setDiscapacitados($discapacitados);
                $horario->setTurno($turno);
                $horario->setConvocatoria(0);
                $horario->setUsuarioCrea($usuario);
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

        $movAsis = $em->getRepository('AkademiaBundle:Movimientos')->getCantAsistencias(2,$idHorario);
        $movRet = $em->getRepository('AkademiaBundle:Movimientos')->getCantRetirados(3,$idHorario);
        $movSel = $em->getRepository('AkademiaBundle:Movimientos')->getCantSeleccionados(2,$idHorario);
     
        return $this->render('AkademiaBundle:Default:beneficiarios.html.twig', array("horarios" => $Horarios, "beneficiarios" => $Beneficiarios, "asistencias" => $Asistencias, "categorias" => $Categorias, "asistentes" => $movAsis, "retirados" => $movRet, "seleccionados" => $movSel, "id" =>$idHorario));
    }
    
    public function estadoBeneficiarioAction(Request $request){
       
        if($request->isXmlHttpRequest()){

            $idFicha = $request->request->get('idFicha');
            $idAsistencia = $request->request->get('idAsistencia');
            $idCategoria = $request->request->get('idCategoria');
            $usuario = $this->getUser()->getId();
           
            $em = $this->getDoctrine()->getManager();
            $nuevoMovimiento = $em->getRepository('AkademiaBundle:Movimientos')->nuevoMovimiento($idCategoria, $idAsistencia, $idFicha,$usuario);

            if($idAsistencia == 3){

                $em = $this->getDoctrine()->getManager();
                $nuevoMovimiento = $em->getRepository('AkademiaBundle:Inscribete')->getBeneficiarioRetirado($idFicha);
            }
            if(empty($nuevoMovimiento)){
                $mensaje = 1;
                return new JsonResponse($mensaje);
            }else{
                $mensaje = 2;
                return new JsonResponse($mensaje);
            }       
        }
    }
    public function crearDisciplinaAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idDisciplina = $request->request->get('idDisciplina');           
            $idComplejo = $this->getUser()->getIdComplejo();
            $usuario = $this->getUser()->getId();
            
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository('AkademiaBundle:ComplejoDisciplina')->getCompararEstado($idComplejo, $idDisciplina);
        
            if(!empty($estado)){
                $em = $this->getDoctrine()->getManager();
                $estadoActual = $em->getRepository('AkademiaBundle:ComplejoDisciplina')->getCambiarEstado($idComplejo, $idDisciplina);
                $mensaje = 1;
                return new JsonResponse($mensaje);    
            
            }else{
                $disciplina = new ComplejoDisciplina();
                $em = $this->getDoctrine()->getRepository(DisciplinaDeportiva::class);
                $codigoDisciplina = $em->find($idDisciplina);
                $disciplina->setDisciplinaDeportiva($codigoDisciplina);
               
                $em = $this->getDoctrine()->getRepository(ComplejoDeportivo::class);
                $codigoComplejo = $em->find($idComplejo);
                $disciplina->setComplejoDeportivo($codigoComplejo);
                $disciplina->setEstado(1);
                $disciplina->setUsuario($usuario);
         
                $em = $this->getDoctrine()->getManager();
                $em->persist($disciplina);
                $em->flush();  
                $mensaje = 2;
                return new JsonResponse($mensaje);           
            }
          
        }
    }
}