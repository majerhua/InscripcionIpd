<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apoderado
 *
 * @ORM\Table(name="ACADEMIA.apoderado")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\ApoderadoRepository")
 */
class Apoderado
{
    

    /**
     * @ORM\OneToMany(targetEntity="Participante", mappedBy="apoderado")
     */
    private $participantes;

    /**
     * @ORM\ManyToOne(targetEntity="Distrito", inversedBy="apoderados")
     * @ORM\JoinColumn(name="ubicodigo", referencedColumnName="ubicodigo")
     */

    private $distrito;

    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var integer
     *
     * @ORM\Column(name="percodigo", type="integer", unique=true)
     */
    private $percodigo;


    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=8, unique=true)
     */
    private $dni; 
  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participantes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set percodigo
     *
     * @param integer $percodigo
     *
     * @return Apoderado
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
     * Set dni
     *
     * @param string $dni
     *
     * @return Apoderado
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
     * Add participante
     *
     * @param \AkademiaBundle\Entity\Participante $participante
     *
     * @return Apoderado
     */
    public function addParticipante(\AkademiaBundle\Entity\Participante $participante)
    {
        $this->participantes[] = $participante;

        return $this;
    }

    /**
     * Remove participante
     *
     * @param \AkademiaBundle\Entity\Participante $participante
     */
    public function removeParticipante(\AkademiaBundle\Entity\Participante $participante)
    {
        $this->participantes->removeElement($participante);
    }

    /**
     * Get participantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }

    /**
     * Set distrito
     *
     * @param \AkademiaBundle\Entity\Distrito $distrito
     *
     * @return Apoderado
     */
    public function setDistrito(\AkademiaBundle\Entity\Distrito $distrito = null)
    {
        $this->distrito = $distrito;

        return $this;
    }

    /**
     * Get distrito
     *
     * @return \AkademiaBundle\Entity\Distrito
     */
    public function getDistrito()
    {
        return $this->distrito;
    }
}
