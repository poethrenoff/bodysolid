<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\Cart;
use AppBundle\Service\PreferenceStorage;
use AppBundle\Entity\Product;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\PurchaseItem;
use AppBundle\Form\PurchaseType;

class PurchaseController extends Controller
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var PreferenceStorage
     */
    protected $preferenceStorage;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * PurchaseController constructor
     *
     * @param Cart $cart
     * @param SessionInterface $session
     * @param PreferenceStorage $preferenceStorage
     * @param EntityManagerInterface $entityManager
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Cart $cart,
                                SessionInterface $session,
                                PreferenceStorage $preferenceStorage,
                                EntityManagerInterface $entityManager,
                                \Swift_Mailer $mailer)
    {
        $this->cart = $cart;
        $this->session = $session;
        $this->preferenceStorage = $preferenceStorage;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/purchase", name="purchase")
     *
     * @throws \Exception
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $flush = $this->session->getFlashBag();

        foreach ($flush->get('success') as $message) {
            if ($message) {
                return $this->render('@App/Purchase/success.html.twig');
            }
        }

        $productList = array();
        foreach ($this->cart->getItems() as $item ) {
            $productList[$item->id] = $this->getProduct($item->id);
        }

        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->beginTransaction();

            try {
                $purchase->setSum($this->cart->getSum());

                foreach ($this->cart->getItems() as $item ) {
                    $purchaseItem = new PurchaseItem();
                    $purchaseItem
                        ->setPurchase($purchase)
                        ->setProduct($productList[$item->id])
                        ->setPrice($item->price)
                        ->setQuantity($item->quantity);
                    $purchase->addItem($purchaseItem);
                }

                $this->entityManager->persist($purchase);
                $this->entityManager->flush();

                $this->sendMessages($purchase);

                $this->entityManager->commit();
            } catch (\Exception $e) {
                $this->entityManager->rollBack();
                throw $e;
            }

            $this->cart->clear();

            $flush->add('success', true);

            return $this->redirectToRoute('purchase');
        }

        return $this->render('@App/Purchase/form.html.twig', array(
            'productList' => $productList, 'form' => $form->createView(),
        ));
    }

    /**
     * Send messages
     *
     * @throws \Exception
     *
     * @param Purchase $purchase
     */
    protected function sendMessages(Purchase $purchase)
    {
        $from_email = $this->preferenceStorage->get('from_email');
        $from_name = $this->preferenceStorage->get('from_name');

        $manager_email= $this->preferenceStorage->get('manager_email');
        $manager_subject = $this->preferenceStorage->get('manager_subject');
        $client_subject = $this->preferenceStorage->get('client_subject');

        $manager_message = (new \Swift_Message())
            ->setSubject($manager_subject)
            ->setFrom($from_email, $from_name)
            ->setTo($manager_email)
            ->setBody(
                $this->renderView('@App/Purchase/manager_message.html.twig', array(
                    'purchase' => $purchase
                )),
                'text/html'
            );
        $this->mailer->send($manager_message);

        $client_message = (new \Swift_Message())
            ->setSubject($client_subject)
            ->setFrom($from_email, $from_name)
            ->setTo($purchase->getEmail())
            ->setBody(
                $this->renderView('@App/Purchase/client_message.html.twig', array(
                    'purchase' => $purchase
                )),
                'text/html'
            );
        $this->mailer->send($client_message);
    }

    /**
     * Get product
     *
     * @param int $id
     * @return Product
     */
    protected function getProduct($id)
    {
        $productItem = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findOneBy(['id' => $id, 'active' => true]);

        if (!$productItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $productItem;
    }
}