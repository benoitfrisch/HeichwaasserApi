<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="stations")
 * @Serializer\ExclusionPolicy("none")
 */
class Station
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"station"})
     */
    private $id;

    /**
     * Many Measurements have One Station.
     * @ORM\ManyToOne(targetEntity="River", inversedBy="stations")
     * @ORM\JoinColumn(name="river_id", referencedColumnName="id")
     * @Serializer\Groups({"station_river"})
     */
    private $river;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $shortname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"station","station_measurement"})
     */
    private $city;

    /**
     * @ORM\Column(type="string",  nullable=true)
     * @Serializer\Groups({"station","station_measurement"})
     */
    private $supplement;

    /**
     * @ORM\Column(type="float",  nullable=true)
     * @Serializer\Groups({"station","station_measurement"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float",  nullable=true)
     * @Serializer\Groups({"station","station_measurement"})
     */
    private $longitude;

    /**
     * One Station has Many Measurements.
     * @ORM\OneToMany(targetEntity="Measurement", mappedBy="station")
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @Serializer\Groups({"measurement"})
     */
    private $measurements;

    public function __toString()
    {
        return $this->city;
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
     * @return Station
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRiver()
    {
        return $this->river;
    }

    /**
     * @param mixed $river
     * @return Station
     */
    public function setRiver($river)
    {
        $this->river = $river;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * @param mixed $shortname
     * @return Station
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Station
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Station
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Station
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeasurements()
    {
        return $this->measurements;
    }

    /**
     * @param mixed $measurements
     * @return Station
     */
    public function setMeasurements($measurements)
    {
        $this->measurements = $measurements;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSupplement()
    {
        return $this->supplement;
    }

    /**
     * @param mixed $supplement
     * @return Station
     */
    public function setSupplement($supplement)
    {
        $this->supplement = $supplement;
        return $this;
    }

}