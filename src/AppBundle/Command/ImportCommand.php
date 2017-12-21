<?php

namespace AppBundle\Command;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\River;
use AppBundle\Entity\Station;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;
    private $station;
    private $river;


    protected function configure()
    {
        $this
            ->setName('import:current')
            ->setDescription('Import Current Water levels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        // go to data.public.lu and fetch the current link
        $dataUrl = file_get_contents('https://data.public.lu/en/datasets/measured-water-levels/');
        $urlPosition = strpos($dataUrl, '<meta itemprop="contentUrl" content="');
        $urlPositionEnd = strpos(substr($dataUrl, $urlPosition + strlen('<meta itemprop="contentUrl" content="')), '"');
        // get the current link
        $currentUrl = substr($dataUrl, $urlPosition + strlen('<meta itemprop="contentUrl" content="'), $urlPositionEnd);

        //open current File

        $currentFile = file_get_contents($currentUrl);

        $currentArray = explode("\n", $currentFile);

        echo array_search("## Exported", $currentArray);

        //iterate through array
        for ($i = 0; $i < count($currentArray) - 1; $i++) {
            $line = utf8_encode($currentArray[$i]);
            echo $line . "\n";
            if (strpos($line, "SNAME") == 1) {
                $nameArray = explode(";", $line);
                print_r($nameArray);
                if (count($nameArray) > 5) {
                    $stationName = substr($nameArray[0], strlen("#SNAME"));
                    $riverName = substr($nameArray[4], strlen("SWATER"));

                    echo $stationName . "-" . $riverName . "\n";

                    //check if river and station exist
                    $river = $this->em->getRepository('AppBundle:River')->findOneBy(['shortname' => $riverName]);
                    $station = $this->em->getRepository('AppBundle:Station')->findOneBy(['shortname' => $stationName]);

                    if (empty($river)) {
                        $river = new River();
                        $river->setName($riverName);
                        $river->setShortname($riverName);
                        $this->em->persist($river);
                        $this->em->flush();
                    }
                    if (empty($station)) {
                        $station = new Station();
                        $station->setCity($stationName);
                        $station->setShortname($stationName);
                        $station->setRiver($river);
                        $this->em->persist($station);
                        $this->em->flush();
                    }


                }
                $this->river = $river;
                $this->station = $station;

            } else if (substr($line, 0, 1) != "#") {
                $measureArray = explode(" ", $line);
                if (count($measureArray) >= 2) {
                    $timestamp = DateTime::createFromFormat('YmdHis', $measureArray[0]);
                    $value = $measureArray[1];
                    echo $this->station;

                    $measurement = $this->em->getRepository('AppBundle:Measurement')->findOneBy(['timestamp' => $timestamp, 'station' => $this->station]);

                    if (empty($measurement)) {
                        $measurement = new Measurement();
                        $measurement->setStation($this->station);
                        $measurement->setUnit("cm");
                        $measurement->setValue($value);
                        $measurement->setTimestamp($timestamp);
                        $this->em->persist($measurement);
                        $this->em->flush();
                    }

                }
            }

        }
    }
}