<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Service\PreferenceStorage;
use AppBundle\Entity\Discount;
use AppBundle\Form\DiscountType;

class DiscountController extends Controller
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
     * DiscountController constructor
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
     * @Route("/discount", name="discount")
     *
     * @throws \Exception
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
                return $this->render('@App/Discount/success.html.twig');
            }
        }

        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount, [
            'action' => $this->generateUrl('discount')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($discount);

            $flush->add('success', true);

            return $this->redirectToRoute('discount');
        }

        return $this->render('@App/Discount/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Send message
     *
     * @throws \Exception
     *
     * @param Discount $discount
     */
    protected function sendMessage(Discount $discount)
    {
        $from_email = $this->preferenceStorage->get('from_email');
        $from_name = $this->preferenceStorage->get('from_name');
        $discount_email = $this->preferenceStorage->get('discount_email');
        $discount_subject = $this->preferenceStorage->get('discount_subject');

        $message = (new \Swift_Message())
            ->setSubject($discount_subject)
            ->setFrom($from_email, $from_name)
            ->setTo($discount_email)
            ->setBody(
                $this->renderView('@App/Discount/message.html.twig', array(
                    'discount' => $discount,
                )),
                'text/html'
            );
        $this->mailer->send($message);
    }
}