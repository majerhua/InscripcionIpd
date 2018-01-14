<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Distrito
 *
 * @ORM\Table(name="dbo.grubigeo")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\DistritoRepository")
 */
class Distrito
{

    /**
     * @ORM\OneToMany(targetEntity="ComplejoDeportivo", mappedBy="distrito")
     */
    private $complejosDeportivo;


    /**
     * @ORM\OneToMany(targetEntity="Apoderado", mappedBy="distrito")
     */
    private $apoderados;

    /**
     * @var int
     *
     * @ORM\Column(name="ubicodigo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ubinombre", type="string", length=255)
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
     * @return Distrito
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
        $this->complejosDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add complejosDeportivo
     *
     * @param \AkademiaBundle\Entity\ComplejoDeportivo $complejosDeportivo
     *
     * @return Distrito
     */
    public function addComplejosDeportivo(\AkademiaBundle\Entity\ComplejoDeportivo $complejosDeportivo)
    {
        $this->complejosDeportivo[] = $complejosDeportivo;

        return $this;
    }

    /**
     * Remove complejosDeportivo
     *
     * @param \AkademiaBundle\Entity\ComplejoDeportivo $complejosDeportivo
     */
    public function removeComplejosDeportivo(\AkademiaBundle\Entity\ComplejoDeportivo $complejosDeportivo)
    {
        $this->complejosDeportivo->removeElement($complejosDeportivo);
    }

    /**
     * Get complejosDeportivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComplejosDeportivo()
    {
        return $this->complejosDeportivo;
    }

    /**
     * Add apoderado
     *
     * @param \AkademiaBundle\Entity\Apoderado $apoderado
     *
     * @return Distrito
     */
    public function addApoderado(\AkademiaBundle\Entity\Apoderado $apoderado)
    {
        $this->apoderados[] = $apoderado;

        return $this;
    }

    /**
     * Remove apoderado
     *
     * @param \AkademiaBundle\Entity\Apoderado $apoderado
     */
    public function removeApoderado(\AkademiaBundle\Entity\Apoderado $apoderado)
    {
        $this->apoderados->removeElement($apoderado);
    }

    /**
     * Get apoderados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApoderados()
    {
        return $this->apoderados;
    }
}
