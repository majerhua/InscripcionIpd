<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComplejoDeportivo
 *
 * @ORM\Table(name="complejo_deportivo")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\ComplejoDeportivoRepository")
 */
class ComplejoDeportivo
{



    /**
     * @ORM\OneToMany(targetEntity="ComplejoDisciplina", mappedBy="complejoDeportivo")
     */
    private $complejosDisciplinas;


    /**
     * @ORM\ManyToOne(targetEntity="Distrito", inversedBy="complejosDeportivo")
     * @ORM\JoinColumn(name="distrito_id", referencedColumnName="id")
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;


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
     * @return ComplejoDeportivo
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return ComplejoDeportivo
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set distrito
     *
     * @param \AkademiaBundle\Entity\Distrito $distrito
     *
     * @return ComplejoDeportivo
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->complejosDisciplinas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add complejosDisciplina
     *
     * @param \AkademiaBundle\Entity\ComplejoDisciplina $complejosDisciplina
     *
     * @return ComplejoDeportivo
     */
    public function addComplejosDisciplina(\AkademiaBundle\Entity\ComplejoDisciplina $complejosDisciplina)
    {
        $this->complejosDisciplinas[] = $complejosDisciplina;

        return $this;
    }

    /**
     * Remove complejosDisciplina
     *
     * @param \AkademiaBundle\Entity\ComplejoDisciplina $complejosDisciplina
     */
    public function removeComplejosDisciplina(\AkademiaBundle\Entity\ComplejoDisciplina $complejosDisciplina)
    {
        $this->complejosDisciplinas->removeElement($complejosDisciplina);
    }

    /**
     * Get complejosDisciplinas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComplejosDisciplinas()
    {
        return $this->complejosDisciplinas;
    }
}
