<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Horario
 *
 * @ORM\Table(name="ACADEMIA.horario")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\HorarioRepository")
 */
class Horario
{
    
    /**
     * @ORM\OneToMany(targetEntity="Inscribete", mappedBy="horario")
     */
    private $inscripciones;

    /**
     * @ORM\ManyToOne(targetEntity="ComplejoDisciplina", inversedBy="horarios")
     * @ORM\JoinColumn(name="edi_codigo", referencedColumnName="edi_codigo")
     */
    private $complejoDisciplina;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="vacantes", type="integer")
     */
    private $vacantes;

    /**
     * @var int
     *
     * @ORM\Column(name="inscritos", type="integer")
     */
    private $inscritos;

    /**
     * @var string
     *
     * @ORM\Column(name="horaInicio", type="string", length=255)
     */
    private $horaInicio;

    /**
     * @var string
     *
     * @ORM\Column(name="horaFin", type="string", length=255)
     */
    private $horaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="edadMinima", type="string", length=255)
     */
    private $edadMinima;

    /**
     * @var string
     *
     * @ORM\Column(name="edadMaxima", type="string", length=255)
     */
    private $edadMaxima;

    /**
     * @var string
     *
     * @ORM\Column(name="discapacitados", type="string", length=1)
     */
    private $discapacitados;

    /**
     * @var string
     *
     * @ORM\Column(name="turno", type="string", length=100)
     */
    private $turno;

    /**
     * @var string
     *
     * @ORM\Column(name="convocatoria", type="string", length=1)
     */
    private $convocatoria;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_eliminacion", type="datetime")
     */
    private $fechaEliminacion;

    /**
     * @var int
     *
     * @ORM\Column(name="usuario_modif", type="integer")
     */
    private $usuarioModifica;

    /**
     * @var int
     *
     * @ORM\Column(name="usuario_elimina", type="integer")
     */
    private $usuarioElimina;

    /**
     * @var int
     *
     * @ORM\Column(name="usuario_crea", type="integer")
     */
    private $usuarioCrea;

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
     * Set horaInicio
     *
     * @param string $horaInicio
     *
     * @return Horario
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return string
     */
    public function getHoraInicio()
    {
        return $this->horaInicio->format('H:i');
    }

    /**
     * Set horaFin
     *
     * @param string $horaFin
     *
     * @return Horario
     */
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    /**
     * Get horaFin
     *
     * @return string
     */
    public function getHoraFin()
    {
        return $this->horaFin->format('H:i');
    }

    /**
     * Set edadMinima
     *
     * @param string $edadMinima
     *
     * @return Horario
     */
    public function setEdadMinima($edadMinima)
    {
        $this->edadMinima = $edadMinima;

        return $this;
    }

    /**
     * Get edadMinima
     *
     * @return string
     */
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    /**
     * Set edadMaxima
     *
     * @param string $edadMaxima
     *
     * @return Horario
     */
    public function setEdadMaxima($edadMaxima)
    {
        $this->edadMaxima = $edadMaxima;

        return $this;
    }

    /**
     * Get edadMaxima
     *
     * @return string
     */
    public function getEdadMaxima()
    {
        return $this->edadMaxima;
    }

    /**
     * Set complejoDisciplina
     *
     * @param \AkademiaBundle\Entity\ComplejoDisciplina $complejoDisciplina
     *
     * @return Horario
     */
    public function setComplejoDisciplina(\AkademiaBundle\Entity\ComplejoDisciplina $complejoDisciplina = null)
    {
        $this->complejoDisciplina = $complejoDisciplina;

        return $this;
    }

    /**
     * Get complejoDisciplina
     *
     * @return \AkademiaBundle\Entity\ComplejoDisciplina
     */
    public function getComplejoDisciplina()
    {
        return $this->complejoDisciplina;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscripciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add inscripcione
     *
     * @param \AkademiaBundle\Entity\Inscribete $inscripcione
     *
     * @return Horario
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
     * Set discapacitados
     *
     * @param string $discapacitados
     *
     * @return Horario
     */
    public function setDiscapacitados($discapacitados)
    {
        $this->discapacitados = $discapacitados;

        return $this;
    }

    /**
     * Get discapacitados
     *
     * @return string
     */
    public function getDiscapacitados()
    {
        return $this->discapacitados;
    }

    /**
     * Set convocatoria
     *
     * @param string $convocatoria
     *
     * @return Horario
     */
    public function setConvocatoria($convocatoria)
    {
        $this->convocatoria = $convocatoria;

        return $this;
    }

    /**
     * Get convocatoria
     *
     * @return string
     */
    public function getConvocatoria()
    {
        return $this->convocatoria;
    }

    /**
     * Set turno
     *
     * @param string $turno
     *
     * @return Horario
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return string
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set vacantes
     *
     * @param integer $vacantes
     *
     * @return Horario
     */
    public function setVacantes($vacantes)
    {
        $this->vacantes = $vacantes;

        return $this;
    }

    /**
     * Get vacantes
     *
     * @return integer
     */
    public function getVacantes()
    {
        return $this->vacantes;
    }

    /**
     * Set inscritos
     *
     * @param integer $inscritos
     *
     * @return Horario
     */
    public function setInscritos($inscritos)
    {
        $this->inscritos = $inscritos;

        return $this;
    }

    /**
     * Get inscritos
     *
     * @return integer
     */
    public function getInscritos()
    {
        return $this->inscritos;
    }


    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Horario
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set usuarioModifica
     *
     * @param integer $usuarioModifica
     *
     * @return Horario
     */
    public function setUsuarioModifica($usuarioModifica)
    {
        $this->usuarioModifica = $usuarioModifica;

        return $this;
    }

    /**
     * Get usuarioModifica
     *
     * @return integer
     */
    public function getUsuarioModifica()
    {
        return $this->usuarioModifica;
    }

    /**
     * Set usuarioElimina
     *
     * @param integer $usuarioElimina
     *
     * @return Horario
     */
    public function setUsuarioElimina($usuarioElimina)
    {
        $this->usuarioElimina = $usuarioElimina;

        return $this;
    }

    /**
     * Get usuarioElimina
     *
     * @return integer
     */
    public function getUsuarioElimina()
    {
        return $this->usuarioElimina;
    }

    /**
     * Set usuarioCrea
     *
     * @param integer $usuarioCrea
     *
     * @return Horario
     */
    public function setUsuarioCrea($usuarioCrea)
    {
        $this->usuarioCrea = $usuarioCrea;

        return $this;
    }

    /**
     * Get usuarioCrea
     *
     * @return integer
     */
    public function getUsuarioCrea()
    {
        return $this->usuarioCrea;
    }

    /**
     * Set fechaEliminacion
     *
     * @param \DateTime $fechaEliminacion
     *
     * @return Horario
     */
    public function setFechaEliminacion($fechaEliminacion)
    {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }

    /**
     * Get fechaEliminacion
     *
     * @return \DateTime
     */
    public function getFechaEliminacion()
    {
        return $this->fechaEliminacion;
    }
}
