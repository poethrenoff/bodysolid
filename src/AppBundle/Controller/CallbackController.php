<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Service\PreferenceStorage;
use AppBundle\Entity\Callback;
use AppBundle\Form\CallbackType;

class CallbackController extends Controller
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
     * CallbackController constructor
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
     * @Route("/callback", name="callback")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('index');
        }

        $flush = $this->session->getFlashBag();

        foreach ($flush->get('success') as $message) {
            if ($message) {
                return $this->render('@App/Callback/success.html.twig');
            }
        }

        $callback = new Callback();
        $form = $this->createForm(CallbackType::class, $callback, [
            'action' => $this->generateUrl('callback')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($callback);

            $flush->add('success', true);

            return $this->redirectToRoute('callback');
        }

        return $this->render('@App/Callback/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Send message
     *
     * @throws \Exception
     *
     * @param Callback $callback
     */
    protected function sendMessage(Callback $callback)
    {
        $from_email = $this->preferenceStorage->get('from_email');
        $from_name = $this->preferenceStorage->get('from_name');
        $callback_email = $this->preferenceStorage->get('callback_email');
        $callback_subject = $this->preferenceStorage->get('callback_subject');

        $message = (new \Swift_Message())
            ->setSubject($callback_subject)
            ->setFrom($from_email, $from_name)
            ->setTo($callback_email)
            ->setBody(
                $this->renderView('@App/Callback/message.html.twig', array(
                    'callback' => $callback,
                )),
                'text/html'
            );
        $this->mailer->send($message);
    }
}