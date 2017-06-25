<?php

namespace AppBundle\Controller;

use AppBundle\Service\Manager\Statistics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @param Statistics $statistics
     *
     * @return Response
     *
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function indexAction(Statistics $statistics): Response
    {
        return $this->render('home/index.html.twig', ['stats' => $statistics->execute()]);
    }
}
