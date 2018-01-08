<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departamento
 *
 * @ORM\Table(name="departamento")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\DepartamentoRepository")
 */
class Departamento
{


    /**
     * @ORM\OneToMany(targetEntity="Provincia", mappedBy="departamento")
     */
    private $provincias;

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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Departamento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provincias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add provincia
     *
     * @param \AkademiaBundle\Entity\Provincia $provincia
     *
     * @return Departamento
     */
    public function addProvincia(\AkademiaBundle\Entity\Provincia $provincia)
    {
        $this->provincias[] = $provincia;

        return $this;
    }

    /**
     * Remove provincia
     *
     * @param \AkademiaBundle\Entity\Provincia $provincia
     */
    public function removeProvincia(\AkademiaBundle\Entity\Provincia $provincia)
    {
        $this->provincias->removeElement($provincia);
    }

    /**
     * Get provincias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvincias()
    {
        return $this->provincias;
    }
}
