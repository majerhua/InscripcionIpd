<?php

namespace AkademiaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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


use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

		if($request->isXmlHttpRequest()){

            if($request->request->get('persona') == "apoderado"){

                $dni = $request->request->get('dni');

                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "select dni, apellidoPaterno, apellidoMaterno, nombre, sexo, fechaNacimiento, (cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from persona where dni='$dni';";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $po = $stmt->fetchAll();

                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                //$normalizer->setCircularReferenceLimit(1);
                // Add Circular reference handler
                //$normalizer->setCircularReferenceHandler(function ($object) {
                  //  return $object->getId();
                //});
                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($po,'json');

                return new JsonResponse($jsonContent); 

            }
             
            if($request->request->get('persona') == "hijo"){

                $dni = $request->request->get('dni');

                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "select dni, apellidoPaterno, apellidoMaterno, nombre, sexo, fechaNacimiento, (cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from persona where dni='$dni';";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $po = $stmt->fetchAll();

                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                //$normalizer->setCircularReferenceLimit(1);
                // Add Circular reference handler
                //$normalizer->setCircularReferenceHandler(function ($object) {
                  //  return $object->getId();
                //});
                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($po,'json');

                return new JsonResponse($jsonContent); 

            }
                
		}

        $em =  $this->getDoctrine()->getRepository(Departamento::class);
    	$departamentos = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(Provincia::class);
    	$provincias = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(Distrito::class);
    	$distritos = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(ComplejoDeportivo::class);
        $complejosDeportivo = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(ComplejoDisciplina::class);
        $complejosDisciplinas = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(DisciplinaDeportiva::class);
        $disciplinasDeportivas = $em->findAll();

        $em =  $this->getDoctrine()->getRepository(Horario::class);
        $horarios = $em->findAll();

        return $this->render('AkademiaBundle:Default:index.html.twig' , array("departamentos" => $departamentos, "provincias" => $provincias, "distritos" => $distritos, "complejosDeportivo" => $complejosDeportivo , "complejosDisciplinas" => $complejosDisciplinas , "disciplinasDeportivas" => $disciplinasDeportivas , "horarios" => $horarios ) );
    }

    public function registrarAction(Request $request){

        if($request->isXmlHttpRequest()){


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


            //DATOS PARTICIPANTE

            $dniParticipante = $request->request->get('dniParticipante');
            $apellidoPaternoParticipante = $request->request->get('apellidoPaternoParticipante');
            $apellidoMaternoParticipante = $request->request->get('apellidoMaternoParticipante');
            $nombreParticipante = $request->request->get('nombreParticipante'); 
            $fechaNacimientoParticipante = $request->request->get('fechaNacimientoParticipante');
            $sexoParticipante = $request->request->get('sexoParticipante');
            $parentesco = $request->request->get('parentesco');
            $tipoSeguro = $request->request->get('tipoSeguro');


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

            $em = $this->getDoctrine()->getManager();
            $em->persist($apoderado);
            $em->flush();

            $idApoderado = $apoderado->getId();

            $participante = new Participante();

            $participante->setDni($dniParticipante);
            $participante->setApellidoPaterno($apellidoPaternoParticipante);
            $participante->setApellidoMaterno($apellidoMaternoParticipante);
            $participante->setNombre($nombreParticipante);
            $participante->setFechaNacimiento(new \DateTime($fechaNacimientoParticipante));
            $participante->setSexo($sexoParticipante);
            $participante->setParentesco($parentesco);
            $participante->setTipoDeSeguro($tipoSeguro);

            $em = $this->getDoctrine()->getRepository(Apoderado::class);
            $buscarApoderadoInscripcion = $em->find($idApoderado);
            $participante->setApoderado($buscarApoderadoInscripcion);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participante);
            $em->flush();

            $idParticipante  = $participante->getId();

            $em = $this->getDoctrine()->getEntityManager();
            $db = $em->getConnection();
            $query = "select id,(cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from participante where id='$idParticipante';";
            $stmt = $db->prepare($query);
            $params = array();
            $stmt->execute($params);
            $edadAndId = $stmt->fetchAll();

            if(!empty($edadAndId)){
                return new JsonResponse($edadAndId);    
            }else{
                return new JsonResponse(null);
            }
            
        }

        return new JsonResponse("No es ajax");

    }

    public function registerFinalAction(Request $request){

        if($request->isXmlHttpRequest()){

            $idParticipante = $request->request->get('idParticipante');
            $idHorario = $request->request->get('idHorario');
            $fechaInscripcion = $hoy = date("Y-m-d");
            $estado ="activo";

            $inscripcion = new Inscribete();

            $inscripcion->setEstado($estado);
            $inscripcion->setFechaInscripcion(new \DateTime($fechaInscripcion));

            $em = $this->getDoctrine()->getRepository(Participante::class);
            $buscarParticipante = $em->find($idParticipante);
            $inscripcion->setParticipante($buscarParticipante);

            $em = $this->getDoctrine()->getRepository(Horario::class);
            $buscarHorario = $em->find($idHorario);
            $inscripcion->setHorario($buscarHorario);            

            $em = $this->getDoctrine()->getManager();
            $em->persist($inscripcion);
            $em->flush();          

            return new JsonResponse("Inscrito");
        }

    }


    public function postAction(Request $request){

        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getRepository(Post::class);
            $posts = $em->findAll();

            $encoders = array(new JsonEncoder());
            $normalizer = new ObjectNormalizer();
            //$normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            //$normalizer->setCircularReferenceHandler(function ($object) {
              //  return $object->getId();
            //});
            $normalizers = array($normalizer);
            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize($posts, 'json');

            return new JsonResponse($jsonContent);  
        }


    }

}
