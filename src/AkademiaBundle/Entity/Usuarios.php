<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;



/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\UsuariosRepository")
 */
class Usuarios implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=255)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=255)
     */
    private $contrasena;

    /**
     * @var int
     *
     * @ORM\Column(name="id_perfil", type="integer")
     */
    private $idPerfil;

    /**
     * @var int
     *
     * @ORM\Column(name="id_complejo", type="integer")
     */
    private $idComplejo;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;


    /*Implementación para el logueo*/

    public function getUsername(){
        return $this->usuario;
    }

    public function getSalt(){
        return null;
    }

    public function getRoles(){
        return array($this->getRole());
    }

    public function eraseCredentials(){
        ;
    }

    public function __toString(){
        return $this->usuario;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Usuarios
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Usuarios
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set idPerfil
     *
     * @param integer $idPerfil
     *
     * @return Usuarios
     */
    public function setIdPerfil($idPerfil)
    {
        $this->idPerfil = $idPerfil;

        return $this;
    }

    /**
     * Get idPerfil
     *
     * @return int
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * Set idComplejo
     *
     * @param integer $idComplejo
     *
     * @return Usuarios
     */
    public function setIdComplejo($idComplejo)
    {
        $this->idComplejo = $idComplejo;

        return $this;
    }

    /**
     * Get idComplejo
     *
     * @return int
     */
    public function getIdComplejo()
    {
        return $this->idComplejo;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Usuarios
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }
}

