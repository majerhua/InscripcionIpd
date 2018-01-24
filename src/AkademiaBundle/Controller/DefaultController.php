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
                $encoders = array(new JsonEncoder());
                $normalizer = new ObjectNormalizer();
                $normalizers = array($normalizer);
                $serializer = new Serializer($normalizers, $encoders);
                $jsonContent = $serializer->serialize($po,'json');
                

                 if(!empty($jsonContent)){
                        
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
        $mdlHorario = $em2->getRepository('AkademiaBundle:Horario')->getHorarios();


        return $this->render('AkademiaBundle:Default:index.html.twig' , array( "complejosDeportivo" => $mdlComplejoDeportivo , "complejosDisciplinas" => $mdlComplejoDisciplina , "horarios" => $mdlHorario, "departamentos" => $mdlDepartamento,"provincias" => $mdlProvincia ,"distritos" => $mdlDistrito ,'ditritosCD' => $mdlDitritoCD ,
            "departamentosCD" => $mdlDepartamentosCD ,'provinciasCD' => $mdlProvinciasCD ));
    }


    public function consultaAction(Request $request){
         return $this->render('AkademiaBundle:Default:cuestions.html.twig');
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
            $estado = 1;



            //REGISTRAR APODERADO

            $em = $this->getDoctrine()->getManager();
            $IDApoderado = $em->getRepository('AkademiaBundle:Apoderado')->getbuscarApoderado($dni);

                if(!empty($IDApoderado)){
                   
                    $idApod = $IDApoderado[0]['id'];
                
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
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($apoderado);
                    $em->flush();

                    $idApod = $apoderado->getId();

                }


            
           // REGISTRAR PARTICIPANTE

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

                    $em = $this->getDoctrine()->getRepository(Apoderado::class);
                    $buscarApoderadoInscripcion = $em->find($idApod);
                    $participante->setApoderado($buscarApoderadoInscripcion);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($participante);
                    $em->flush();

                    $idParticipanteN= $participante->getId();
                } 

                    //REGISTRO FINAL

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
            return new JsonResponse("No es ajax");
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

    public function generarPdfDeclaracionJuradaAction(Request $request , $id){



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


    public function postAction(Request $request){

        if($request->isXmlHttpRequest()){

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

    public function sendemailAction(Request $request){

        if($request->isXmlHttpRequest()){

            $nombre = $request->request->get('nombre');
            $email= $request->request->get('email');
            $mensaje=$request->request->get('message');

            $correo = 'coordinaciondpt@gmail.com';
            $subject = 'La Academia - Comentarios de '.$nombre;
            $message = 'Hemos recibido un nuevo comentario y/o sugerencia de la web LA ACADEMIA'. "\r\n" ."\r\n".'NOMBRE: '.$nombre. "\r\n" ."\r\n".'CORREO ELECTRÓNICO: '.$email ."\r\n"."\r\n".'COMENTARIO: '."\r\n"."\r\n". $mensaje ;
            $headers = 'From: soporte@ipd.gob.pe' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($correo, $subject, $message, $headers);
            return new JsonResponse("enviado");
        }
    }


}
