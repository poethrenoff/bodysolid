<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Service\PreferenceStorage;
use AppBundle\Entity\Subscribe;
use AppBundle\Form\SubscribeType;

class SubscribeController extends Controller
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var PreferenceStorage
     */
    protected $preferenceStorage;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * SubscribeController constructor
     *
     * @param SessionInterface $session
     * @param PreferenceStorage $preference
     * @param \Swift_Mailer $mailer
     */
    public function __construct(SessionInterface $session,
                                PreferenceStorage $preference,
                                \Swift_Mailer $mailer)
    {
        $this->session = $session;
        $this->preferenceStorage = $preference;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/subscribe", name="subscribe")
     *
     * @throws \Exception
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $flush = $this->session->getFlashBag();

        foreach ($flush->get('success') as $message) {
            if ($message) {
                return $this->render('@App/Subscribe/success.html.twig');
            }
        }

        $subscribe = new Subscribe();
        $form = $this->createForm(SubscribeType::class, $subscribe, [
            'action' => $this->generateUrl('subscribe')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($subscribe);

            $flush->add('success', true);

            return $this->redirectToRoute('subscribe');
        }

        return $this->render('@App/Subscribe/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Send message
     *
     * @throws \Exception
     *
     * @param Subscribe $subscribe
     */
    protected function sendMessage(Subscribe $subscribe)
    {
        $from_email = $this->preferenceStorage->get('from_email');
        $from_name = $this->preferenceStorage->get('from_name');
        $subscribe_email = $this->preferenceStorage->get('subscribe_email');
        $subscribe_subject = $this->preferenceStorage->get('subscribe_subject');

        $message = (new \Swift_Message())
            ->setSubject($subscribe_subject)
            ->setFrom($from_email, $from_name)
            ->setTo($subscribe_email)
            ->setBody(
                $this->renderView('@App/Subscribe/message.html.twig', array(
                    'subscribe' => $subscribe, 'types' => SubscribeType::$types
                )),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
