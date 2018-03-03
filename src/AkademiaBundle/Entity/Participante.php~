<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participante
 *
 * @ORM\Table(name="ACADEMIA.participante")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\ParticipanteRepository")
 */
class Participante
{

    /**
     * @ORM\OneToMany(targetEntity="Inscribete", mappedBy="participante")
     */
    private $inscripciones;    

    /**
     * @ORM\ManyToOne(targetEntity="Apoderado", inversedBy="participantes")
     * @ORM\JoinColumn(name="apoderado_id", referencedColumnName="id")
     */
    private $apoderado;

    /**
     * @var int
     
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255, unique=true)
     */
    private $dni;

        /**
     * @var integer
     *
     * @ORM\Column(name="percodigo", type="integer", unique=true)
     */
    private $percodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="parentesco", type="string", length=255)
     */
    private $parentesco;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoDeSeguro", type="string", length=255)
     */
    private $tipoDeSeguro;

    /**
     * @var int
     *
     * @ORM\Column(name="discapacitado", type="integer")
     */
    private $discapacitado; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscripciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dni
     *
     * @param string $dni
     *
     * @return Participante
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set percodigo
     *
     * @param integer $percodigo
     *
     * @return Participante
     */
    public function setPercodigo($percodigo)
    {
        $this->percodigo = $percodigo;

        return $this;
    }

    /**
     * Get percodigo
     *
     * @return integer
     */
    public function getPercodigo()
    {
        return $this->percodigo;
    }

    /**
     * Set parentesco
     *
     * @param string $parentesco
     *
     * @return Participante
     */
    public function setParentesco($parentesco)
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    /**
     * Get parentesco
     *
     * @return string
     */
    public function getParentesco()
    {
        return $this->parentesco;
    }

    /**
     * Set tipoDeSeguro
     *
     * @param string $tipoDeSeguro
     *
     * @return Participante
     */
    public function setTipoDeSeguro($tipoDeSeguro)
    {
        $this->tipoDeSeguro = $tipoDeSeguro;

        return $this;
    }

    /**
     * Get tipoDeSeguro
     *
     * @return string
     */
    public function getTipoDeSeguro()
    {
        return $this->tipoDeSeguro;
    }

    /**
     * Set discapacitado
     *
     * @param integer $discapacitado
     *
     * @return Participante
     */
    public function setDiscapacitado($discapacitado)
    {
        $this->discapacitado = $discapacitado;

        return $this;
    }

    /**
     * Get discapacitado
     *
     * @return integer
     */
    public function getDiscapacitado()
    {
        return $this->discapacitado;
    }

    /**
     * Add inscripcione
     *
     * @param \AkademiaBundle\Entity\Inscribete $inscripcione
     *
     * @return Participante
     */
    public function addInscripcione(\AkademiaBundle\Entity\Inscribete $inscripcione)
    {
        $this->inscripciones[] = $inscripcione;

        return $this;
    }

    /**
     * Remove inscripcione
     *
     * @param \AkademiaBundle\Entity\Inscribete $inscripcione
     */
    public function removeInscripcione(\AkademiaBundle\Entity\Inscribete $inscripcione)
    {
        $this->inscripciones->removeElement($inscripcione);
    }

    /**
     * Get inscripciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInscripciones()
    {
        return $this->inscripciones;
    }

    /**
     * Set apoderado
     *
     * @param \AkademiaBundle\Entity\Apoderado $apoderado
     *
     * @return Participante
     */
    public function setApoderado(\AkademiaBundle\Entity\Apoderado $apoderado = null)
    {
        $this->apoderado = $apoderado;

        return $this;
    }

    /**
     * Get apoderado
     *
     * @return \AkademiaBundle\Entity\Apoderado
     */
    public function getApoderado()
    {
        return $this->apoderado;
    }
}
