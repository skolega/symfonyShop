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
        
        $orders = $this->getDoctrine()
                ->getRepository('AppBundle:Orders')
                ->findAll();
        
        
        return array(
                'orders' => $orders,
            );    }

    /**
     * @Route("/zamowienia/edytuj/{id}", name="edit_order")
     * @Template()
     */
    public function editAction($id)
    {
        return array(
                // ...
            );    }

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
        return array(
                // ...
            );    }

}
