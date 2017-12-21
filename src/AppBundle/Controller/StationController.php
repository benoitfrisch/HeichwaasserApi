<?php


namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class StationController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all stations in alphabetical order."
     * )
     * @Get("/api/v1/stations", defaults={"_format"="json"})
     * @View(serializerGroups={"station","river","station_river","alert"})
     */
    public
    function getStationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('AppBundle:Station')->findBy([], ['city' => 'ASC']);
        return $sections;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all a station with measurements."
     * )
     * @Get("/api/v1/stations/{id}", defaults={"_format"="json"})
     * @View(serializerGroups={"station","river","station_river","measurement", "alert"})
     */
    public
    function getRiverDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('AppBundle:Station')->find($id);
        return $sections;
    }
}