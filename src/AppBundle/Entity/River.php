<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rivers")
 * @Serializer\ExclusionPolicy("none")
 */
class River
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"river"})
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"river"})
     */
    private $shortname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"river"})
     */
    private $name;

    /**
     * One Station has Many Measurements.
     * @ORM\OneToMany(targetEntity="Station", mappedBy="river")
     * @ORM\OrderBy({"city" = "ASC"})
     * @Serializer\Groups({"river","station"})
     */
    private $stations;

    public function __toString()
    {
        return $this->name;
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
     * @return River
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return River
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return River
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $stations
     * @return River
     */
    public function updateStations($stations)
    {
        if (!empty($this->getStations())) {
            $new = array_merge($stations, $this->getStations()->getValues());
        } else {
            $new = $stations;
        }
        $this->stations = $new;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStations()
    {
        return $this->stations;
    }

    /**
     * @param mixed $stations
     * @return River
     */
    public function setStations($stations)
    {
        $this->stations = $stations;
        return $this;
    }
}