<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/Index/index.html.twig');
    }
}
