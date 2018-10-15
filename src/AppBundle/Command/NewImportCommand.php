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

class NewImportCommand extends ContainerAwareCommand
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
            ->setName('import:csv:current')
            ->setDescription('Import Current Water levels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        // get the current data file link from data.public.lu API
        $dataUrl = file_get_contents('https://data.public.lu/api/1/datasets/measured-water-levels/');
        $data    = json_decode($dataUrl);

        foreach ($data->resources as $item) {
            if ($item->id == "91b0cf59-89a1-4aa2-aca7-6767f0f2e7cc") { //CSV File
                $currentUrl = $item->url;
            }
        }

        //open current File
        $currentFile = file_get_contents($currentUrl);

        $currentArray = explode("\n", $currentFile);

        //print_r($currentArray[0]);
        $titleArray = explode(";", $currentArray[0]);
        //print_r($titleArray);

        $progress = new ProgressBar($output, count($currentArray) - 1);
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all stations...");
        //iterate through array

        for ($i = 1; $i < count($currentArray) - 1; $i++) {
            $line      = utf8_encode($currentArray[$i]);
            $lineArray = explode(";", $line);

            $stationName   = $lineArray[0];
            $station       = $this->em->getRepository('AppBundle:Station')->findOneBy(['searchName' => $stationName]);
            $this->river   = $station->getRiver();
            $this->station = $station;
            echo $station->getCity() . "-" . $this->getName() . "\n";

            $progress->advance();
            //last line before measurements
            $this->progressM = new ProgressBar($output, count($lineArray) - 3);
            $this->progressM->start();
            $this->progressM->setFormat("normal");
            $this->progressM->setMessage("Importing all measurements...");

            for ($i = 3; $i < count($lineArray) - 1; $i++) {
                $timestamp   = DateTime::createFromFormat('d.m.Y H:i', $titleArray[$i]);
                $value       = $lineArray[$i];
                $measurement = $this->em->getRepository('AppBundle:Measurement')->findOneBy(['timestamp' => $timestamp, 'station' => $this->station]);

                if (empty($measurement)) {
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
            $this->progressM->finish();
        }


        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}
