<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="measurements")
 * @Serializer\ExclusionPolicy("none")
 */
class Measurement
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many Measurements have One Station.
     * @ORM\ManyToOne(targetEntity="Station", inversedBy="measurements")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id")
     */
    private $station;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(type="datetime",  nullable=true)
     * @Serializer\Groups({"measurement","oneMeasurement"})
     */
    private $timestamp;

    /**
     * Value in cm
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="float",  nullable=false)
     * @Serializer\Groups({"measurement","oneMeasurement"})
     */
    private $value;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"measurement","oneMeasurement"})
     */
    private $unit;

    public function __toString()
    {
        return $this->value . " " . $this->unit;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Measurement
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * @param mixed $station
     * @return Measurement
     */
    public function setStation($station)
    {
        $this->station = $station;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     * @return Measurement
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Measurement
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     * @return Measurement
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }
}