<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\TextStorage;
use AppBundle\Entity\Text;

class TextController extends Controller
{
    /**
     * @var TextStorage
     */
    protected $textStorage;

    /**
     * TextController constructor
     *
     * @param TextStorage $textStorage
     */
    public function __construct(TextStorage $textStorage)
    {
        $this->textStorage = $textStorage;
    }

    /**
     * @Route("/contact", name="contact")
     * @Route("/delivery", name="delivery")
     *
     * @throws \Exception
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/Text/text.html.twig', array(
            'textItem' => $this->textStorage->get($request->get('_route'))
        ));
    }

    /**
     * @throws \Exception
     *
     * @param string $name
     * @return Response
     */
    public function blockAction(string $name)
    {
        return $this->render('@App/Text/block.html.twig', array(
            'textItem' => $this->textStorage->get($name)
        ));
    }
}