<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Orders;

class OrdersController extends Controller
{

    /**
     * @Route("/zamowienia", name="orders_list")
     * @Template()
     */
    public function indexAction()
    {

        $orders = $this->getUser()->getOrders();

        return array(
            'orders' => $orders,
        );
    }

    /**
     * @Route("/zamowienia/edytuj/{id}/", name="edit_order")
     * @Template()
     */
    public function editAction($id)
    {
        $order = $this->getDoctrine()
                ->getRepository('AppBundle:Orders')
                ->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                    'Nie znaleziono zamówienia nr' . $id
            );
        }
        $products = $order->getProducts();
        return array(
            'products' => $products,
            'order' => $order,
        );
    }

    /**
     * @Route("/zamowienia/usun/{id}", name="remove_order")
     * @Template()
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $this->getDoctrine()
                ->getRepository('AppBundle:Orders')
                ->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                    'Nie znaleziono zamówienia o numerze ' . $id
            );
        }
        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute('orders_list');
    }

    /**
     * @Route("/zamowienia/realizuj/{id}", name="realise_order")
     * @Template()
     */
    public function realiseAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $this->getDoctrine()
                ->getRepository('AppBundle:Orders')
                ->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                    'Nie znaleziono zamówienia' . $id
            );
        }
        $order->setRealised(TRUE);
        $em->flush();

        return $this->redirectToRoute('orders_list');
    }

}
