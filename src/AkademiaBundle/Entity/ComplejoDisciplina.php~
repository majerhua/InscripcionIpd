<?php

namespace AkademiaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComplejoDisciplina
 *
 * @ORM\Table(name="CATASTRO.edificacionDisciplina")
 * @ORM\Entity(repositoryClass="AkademiaBundle\Repository\ComplejoDisciplinaRepository")
 */
class ComplejoDisciplina
{


    /**
     * @ORM\OneToMany(targetEntity="Horario", mappedBy="complejoDisciplina")
     */
    private $horarios;
    
    /**
     * @ORM\ManyToOne(targetEntity="DisciplinaDeportiva", inversedBy="complejosDisciplinas")
     * @ORM\JoinColumn(name="dis_codigo", referencedColumnName="dis_codigo")
     */
    private $disciplinaDeportiva;    

    /**
     * @ORM\ManyToOne(targetEntity="ComplejoDeportivo", inversedBy="complejosDisciplinas")
     * @ORM\JoinColumn(name="ede_codigo", referencedColumnName="ede_codigo")
     */
    private $complejoDeportivo;

    /**
     * @var int
     *
     * @ORM\Column(name="edi_codigo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var int
     *
     * @ORM\Column(name="edi_estado", type="integer")
     */
    private $estado;

     /**
     * @var int
     *
     * @ORM\Column(name="edi_usucrea", type="integer")
     */
    private $usuario;



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
     * Set complejoDeportivo
     *
     * @param \AkademiaBundle\Entity\ComplejoDeportivo $complejoDeportivo
     *
     * @return ComplejoDisciplina
     */
    public function setComplejoDeportivo(\AkademiaBundle\Entity\ComplejoDeportivo $complejoDeportivo = null)
    {
        $this->complejoDeportivo = $complejoDeportivo;

        return $this;
    }

    /**
     * Get complejoDeportivo
     *
     * @return \AkademiaBundle\Entity\ComplejoDeportivo
     */
    public function getComplejoDeportivo()
    {
        return $this->complejoDeportivo;
    }

    /**
     * Set disciplinaDeportiva
     *
     * @param \AkademiaBundle\Entity\DisciplinaDeportiva $disciplinaDeportiva
     *
     * @return ComplejoDisciplina
     */
    public function setDisciplinaDeportiva(\AkademiaBundle\Entity\DisciplinaDeportiva $disciplinaDeportiva = null)
    {
        $this->disciplinaDeportiva = $disciplinaDeportiva;

        return $this;
    }

    /**
     * Get disciplinaDeportiva
     *
     * @return \AkademiaBundle\Entity\DisciplinaDeportiva
     */
    public function getDisciplinaDeportiva()
    {
        return $this->disciplinaDeportiva;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add horario
     *
     * @param \AkademiaBundle\Entity\Horario $horario
     *
     * @return ComplejoDisciplina
     */
    public function addHorario(\AkademiaBundle\Entity\Horario $horario)
    {
        $this->horarios[] = $horario;

        return $this;
    }

    /**
     * Remove horario
     *
     * @param \AkademiaBundle\Entity\Horario $horario
     */
    public function removeHorario(\AkademiaBundle\Entity\Horario $horario)
    {
        $this->horarios->removeElement($horario);
    }

    /**
     * Get horarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return ComplejoDisciplina
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
     * Set usuario
     *
     * @param integer $usuario
     *
     * @return ComplejoDisciplina
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return integer
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
