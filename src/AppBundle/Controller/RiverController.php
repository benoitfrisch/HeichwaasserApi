<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class RiverController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays all rivers including stations in alphabetical order."
     * )
     * @Get("/api/v1/rivers", defaults={"_format"="json"})
     * @View(serializerGroups={"river","river_station", "station","alert","oneMeasurement"})
     */
    public
    function getRiverAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rivers = $em->getRepository('AppBundle:River')->findBy([], ['name' => 'ASC']);
        return $rivers;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="This displays a river with stations in alphabetical order."
     * )
     * @Get("/api/v1/rivers/{id}", defaults={"_format"="json"})
     * @View(serializerGroups={"river","river_station", "station","alert","oneMeasurement"})
     */
    public
    function getRiverDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $river = $em->getRepository('AppBundle:River')->find($id);
        return $river;
    }
}