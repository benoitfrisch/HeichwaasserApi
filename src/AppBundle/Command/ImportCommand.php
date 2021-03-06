<?php

namespace AppBundle\Command;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\River;
use AppBundle\Entity\Station;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Station
     */
    private $station;
    /**
     * @var River
     */
    private $river;
    /**
     * @var ProgressBar
     */
    private $progressM;


    protected function configure()
    {
        $this
            ->setName('import:current')
            ->setDescription('Import Current Water levels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        // get the current data file link from data.public.lu API
        $dataUrl = file_get_contents('https://data.public.lu/api/1/datasets/measured-water-levels/');
        $data    = json_decode($dataUrl);

        foreach ($data->resources as $item) {
            if ($item->id == "73a5afbd-f075-421d-821c-b23384bb179e") { //innondations.txt
                $currentUrl = $item->url;
            }
        }
        //$currentUrl = $data['resources'][3]['url'];

        //open current File
        $currentFile = file_get_contents($currentUrl);

        $currentArray = explode("\n", $currentFile);


        $progress = new ProgressBar($output, 37);
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all stations...");
        //iterate through array
        for ($i = 0; $i < count($currentArray) - 1; $i++) {
            $line = utf8_encode($currentArray[$i]);
            //echo $line . "\n";
            if (strpos($line, "SNAME") == 1) {
                $nameArray = explode(";", $line);
                if (count($nameArray) > 5) {
                    $stationName = substr($nameArray[0], strlen("#SNAME"));
                    $riverName   = substr($nameArray[4], strlen("SWATER"));

                    //check if river and station exist
                    $river   = $this->em->getRepository('AppBundle:River')->findOneBy(['shortname' => $riverName]);
                    $station = $this->em->getRepository('AppBundle:Station')->findOneBy(['shortname' => $stationName]);

                    if (empty($river)) {
                        if (empty($station)) {
                            $river = new River();
                            $river->setName($riverName);
                            $river->setShortname($riverName);
                            $this->em->persist($river);
                            $this->em->flush();
                        } else {
                            $river = $station->getRiver();
                        }
                    }
                    if (empty($station)) {
                        $station = new Station();
                        $station->setCity($stationName);
                        $station->setShortname($stationName);
                        $station->setRiver($river);
                        $station->setTrend(Station::TREND_REST);
                        $this->em->persist($station);
                        $this->em->flush();
                    }
                }
                $this->river   = $river;
                $this->station = $station;


                echo $station->getCity() . "-" . $river->getName() . "\n";

                $progress->advance();
            } else {
                if (substr($line, 0, strlen("#RSTATEW6;*;")) == "#RSTATEW6;*;") {
                    //last line before measurements
                    $this->progressM = new ProgressBar($output);
                    $this->progressM->start();
                    $this->progressM->setFormat("normal");
                    $this->progressM->setMessage("Importing all measurements...");
                } else {
                    if (substr($line, 0, 1) != "#") {
                        $measureArray = explode(" ", $line);
                        if (count($measureArray) >= 2) {
                            $timestamp   = DateTime::createFromFormat('YmdHis', $measureArray[0]);
                            $value       = $measureArray[1];

                            if ($this->station->getCurrent() && $this->station->getCurrent()->getTimestamp() < $timestamp) {
                                $measurement = new Measurement();
                                $measurement->setStation($this->station);
                                $measurement->setUnit("cm");
                                $measurement->setValue($value);
                                $measurement->setTimestamp($timestamp);

                                $this->em->persist($measurement);
                                $this->em->flush();

                                if ($this->station->getCurrent()) {
                                    if ($measurement->getValue() < $this->station->getCurrent()->getValue()) {
                                        $this->station->setTrend(Station::TREND_DOWN);
                                    } else {
                                        if ($measurement->getValue() > $this->station->getCurrent()->getValue()) {
                                            $this->station->setTrend(Station::TREND_UP);
                                        } else {
                                            $this->station->setTrend(Station::TREND_REST);
                                        }
                                    }
                                }


                                $this->station->updateStats($measurement);

                                //$output->writeln($this->station->getCurrent() . $this->station->getMinimum() . $this->station->getMaximum());
                                $this->em->persist($this->station);
                                $this->em->flush();

                                $output->writeln($timestamp->format("d.m.Y H:i:s") . " " . $value . " cm");
                                $this->progressM->advance();
                            }
                        }
                    } else {
                        if (substr($line, 0, strlen("## Exported")) == "## Exported") {
                            //first line of station block, stop progress bar for measurements
                            if ($this->progressM != null) {
                                $this->progressM->finish();
                            }
                        }
                    }
                }
            }
        }
        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}
