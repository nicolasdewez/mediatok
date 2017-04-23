<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
