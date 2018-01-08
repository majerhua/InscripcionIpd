<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisciplinaDeportiva
 *
 * @ORM\Table(name="disciplina_deportiva")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\DisciplinaDeportivaRepository")
 */
class DisciplinaDeportiva
{

    /**
     * @ORM\OneToMany(targetEntity="ComplejoDisciplina", mappedBy="disciplinaDeportiva")
     */
    private $complejosDisciplinas;

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
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;


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
     * @return DisciplinaDeportiva
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return DisciplinaDeportiva
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
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
     * @return DisciplinaDeportiva
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
