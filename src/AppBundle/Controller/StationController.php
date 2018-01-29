<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Measurement;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;


class StationController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all stations in alphabetical order."
     * )
     * @Get("/api/v1/stations", defaults={"_format"="json"})
     * @View(serializerGroups={"station","river","station_river","alert","oneMeasurement"})
     */
    public
    function getStationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stations = $em->getRepository('AppBundle:Station')->findBy([], ['city' => 'ASC']);
        return $stations;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all a station with measurements."
     * )
     * @Get("/api/v1/stations/{id}", defaults={"_format"="json"})
     * @View(serializerGroups={"station","river","station_river","measurement", "alert","oneMeasurement"})
     */
    public
    function getRiverDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $station = $em->getRepository('AppBundle:Station')->find($id);
        return $station;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all a station with measurements."
     * )
     * @Get("/api/v1/stations/{id}/start/{start}/end/{end}", defaults={"_format"="json"})
     * @View(serializerGroups={"station","river","station_river","measurement", "alert","oneMeasurement"})
     */
    public
    function getRiverDetailLimitAction($id, $start, $end)
    {
        $startDate = DateTime::createFromFormat('Ymd', $start);
        $endDate = DateTime::createFromFormat('Ymd', $end);

        if ($startDate >= $endDate) {
            throw new HttpException(400, "Start date has to be before end date.");
        }

        $em = $this->getDoctrine()->getManager();
        $station = $em->getRepository('AppBundle:Station')->find($id);

        $measurements = $em
            ->getRepository(Measurement::class)->createQueryBuilder('a')
            ->where('a.station = :station')
            ->andWhere('a.timestamp > :start')
            ->andWhere('a.timestamp < :end')
            ->orderBy('a.timestamp', 'ASC')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->setParameter('station', $station)
            ->getQuery()
            ->getResult();

        $station->setMeasurements(new ArrayCollection($measurements));

        return $station;
    }
}