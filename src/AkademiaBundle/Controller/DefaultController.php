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
use AkademiaBundle\Entity\Post;
use AkademiaBundle\Entity\ComplejoDisciplina;


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


        return $this->render('AkademiaBundle:Default:index.html.twig' , array("departamentos" => $departamentos, "provincias" => $provincias, "distritos" => $distritos, "complejosDeportivo" => $complejosDeportivo , "complejosDisciplinas" => $complejosDisciplinas , "disciplinasDeportivas" => $disciplinasDeportivas) );
    }

    public function registrarAction(Request $request){

        if($request->isXmlHttpRequest()){

            $estado = $request->request->get('persona');

            if($estado == "apoderado"){

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

                //REALIZAR CONSULTAS SQL
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "select (cast(datediff(dd,fechaNacimiento,GETDATE()) / 365.25 as int)) as edad from apoderado where id='$idApoderado';";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $po=$stmt->fetchAll();
                
                return new JsonResponse($po);

            }else{

                return new JsonResponse("No es apoderado");
            }


        }

        return new JsonResponse("No es ajax");

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


    public function pruebaGetAction(Request  $request){

 


    }

}
