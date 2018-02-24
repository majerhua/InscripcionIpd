<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asistencia
 *
 * @ORM\Table(name="ACADEMIA.asistencia")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\AsistenciaRepository")
 */
class Asistencia
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
     * @ORM\OneToMany(targetEntity="Movimientos", mappedBy="asistencias")
     */
    private $movimientos;  
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;


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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Asistencia
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
     * Set estado
     *
     * @param integer $estado
     *
     * @return Asistencia
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movimiento
     *
     * @param \AkademiaBundle\Entity\Movimientos $movimiento
     *
     * @return Asistencia
     */
    public function addMovimiento(\AkademiaBundle\Entity\Movimientos $movimiento)
    {
        $this->movimientos[] = $movimiento;

        return $this;
    }

    /**
     * Remove movimiento
     *
     * @param \AkademiaBundle\Entity\Movimientos $movimiento
     */
    public function removeMovimiento(\AkademiaBundle\Entity\Movimientos $movimiento)
    {
        $this->movimientos->removeElement($movimiento);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientos()
    {
        return $this->movimientos;
    }
}
