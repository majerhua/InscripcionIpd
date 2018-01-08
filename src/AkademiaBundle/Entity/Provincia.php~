<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provincia
 *
 * @ORM\Table(name="provincia")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\ProvinciaRepository")
 */
class Provincia
{

    /**
     * @ORM\OneToMany(targetEntity="Distrito", mappedBy="provincia")
     */
    private $distritos;


    /**
     * @ORM\ManyToOne(targetEntity="Departamento", inversedBy="provincias")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     */
    private $departamento;

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
     * @return Provincia
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
     * Set departamento
     *
     * @param \AkademiaBundle\Entity\Departamento $departamento
     *
     * @return Provincia
     */
    public function setDepartamento(\AkademiaBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \AkademiaBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->distritos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add distrito
     *
     * @param \AkademiaBundle\Entity\Distrito $distrito
     *
     * @return Provincia
     */
    public function addDistrito(\AkademiaBundle\Entity\Distrito $distrito)
    {
        $this->distritos[] = $distrito;

        return $this;
    }

    /**
     * Remove distrito
     *
     * @param \AkademiaBundle\Entity\Distrito $distrito
     */
    public function removeDistrito(\AkademiaBundle\Entity\Distrito $distrito)
    {
        $this->distritos->removeElement($distrito);
    }

    /**
     * Get distritos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistritos()
    {
        return $this->distritos;
    }
}
