<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscribete
 *
 * @ORM\Table(name="ACADEMIA.inscribete")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\InscribeteRepository")
 */
class Inscribete
{

    /**
     * @ORM\ManyToOne(targetEntity="Participante", inversedBy="inscripciones")
     * @ORM\JoinColumn(name="participante_id", referencedColumnName="id")
     */
    private $participante;

    /**
     * @ORM\ManyToOne(targetEntity="Horario", inversedBy="inscripciones")
     * @ORM\JoinColumn(name="horario_id", referencedColumnName="id")
     */
    private $horario;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInscripcion", type="datetime")
     */
    private $fechaInscripcion;

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
     * Set fechaInscripcion
     *
     * @param \DateTime $fechaInscripcion
     *
     * @return Inscribete
     */
    public function setFechaInscripcion($fechaInscripcion)
    {
        $this->fechaInscripcion = $fechaInscripcion;

        return $this;
    }

    /**
     * Get fechaInscripcion
     *
     * @return \DateTime
     */
    public function getFechaInscripcion()
    {
        return $this->fechaInscripcion;
    }

    /**
     * Set estado
     *
     * @param int $estado
     *
     * @return Inscribete
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set participante
     *
     * @param \AkademiaBundle\Entity\Participante $participante
     *
     * @return Inscribete
     */
    public function setParticipante(\AkademiaBundle\Entity\Participante $participante = null)
    {
        $this->participante = $participante;

        return $this;
    }

    /**
     * Get participante
     *
     * @return \AkademiaBundle\Entity\Participante
     */
    public function getParticipante()
    {
        return $this->participante;
    }

    /**
     * Set horario
     *
     * @param \AkademiaBundle\Entity\Horario $horario
     *
     * @return Inscribete
     */
    public function setHorario(\AkademiaBundle\Entity\Horario $horario = null)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return \AkademiaBundle\Entity\Horario
     */
    public function getHorario()
    {
        return $this->horario;
    }
}
