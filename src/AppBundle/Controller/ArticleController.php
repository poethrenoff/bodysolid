<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\SiteStorage;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @var SiteStorage
     */
    protected $siteStorage;

    /**
     * TextController constructor
     *
     * @param SiteStorage $siteStorage
     */
    public function __construct(SiteStorage $siteStorage)
    {
        $this->siteStorage = $siteStorage;
    }

    /**
     * Article list
     *
     * @Route("/article", name="article")
     *
     * @throws \Exception
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $articleList = array_merge(
            $this->getDoctrine()->getManager()
                ->getRepository(Article::class)->findBy(['active' => true, 'site' => null]),
            $this->getDoctrine()->getManager()
                ->getRepository(Article::class)->findBy(['active' => true,
                    'site' => $this->siteStorage->get()->getId()])
        );

        return $this->render('@App/Article/index.html.twig', array(
            'articleList' => $articleList,
        ));
    }

    /**
     * Article item
     *
     * @Route("/article/{id}", name="articleItem")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function articleAction(Request $request, int $id)
    {
        $articleItem = $this->getDoctrine()->getManager()
            ->getRepository(Article::class)->findOneBy(['id' => $id, 'active' => true]);

        if (!$articleItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $this->render('@App/Article/item.html.twig', array(
            'articleItem' => $articleItem,
        ));
    }
}