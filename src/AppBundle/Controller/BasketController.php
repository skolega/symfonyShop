<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class BasketController extends Controller
{

    /**
     * @Route("/koszyk", name="basket")
     * 
     */
    public function indexAction(Request $request)
    {
        
        $basket = $this->get('basket');
        $quantities = $request->request->get('quantity', []); 
        foreach ($quantities as $id => $quantity) {
            
            $basket->updateQuantity($id, $quantity);
        }

        return $this->render('Basket/index.html.twig', array(
                    'basket' => $basket,
                    'sum' => $basket->getPrice(),
                    'quantitySum' => $basket->getQuantity(),
                        )
        );
    }

    /**
     * @Route("/koszyk/{id}/dodaj", name="basket_add")
     */
    public function addAction(Request $request, Product $product = null)
    {
        if (is_null($product)) {
            $this->addFlash('notice', 'Produkt który próbujesz dodać nie został znaleziony');
            return $this->redirect($request->headers->get('referer'));
        }


        try {
            $basket = $this->get('basket');
            $basket->add($product);
            $this->addFlash('notice', sprintf('Produkt %s został dodany do koszyka', $product->getName()));
            return $this->redirect($request->headers->get('referer'));
        } catch (\Exception $e) {

            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('products_list');
        }

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/{id}/usun", name="basket_remove")
     */
    public function removeAction(Product $product)
    {
        $basket = $this->get('basket');
        try {
            $basket->remove($product);
            $this->addFlash('notice', sprintf('Produkt %s został usunięty z koszyka', $product->getName()));
        } catch (\Exception $ex) {
            $this->addFlash('notice', $ex->getMessage());
        }

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/{id}/zaktualizuj-ilosc/{quantity}", name="quantity_update")
     */
    public function updateAction($id, $quantity, Request $request)
    {

        $basket = $this->get('basket');
        $products = $basket->getProducts();

        $products[$id]['quantity'] += $quantity;
        $this->addFlash('notice', 'Zmieniono ilość ' . $products[$id]['name'] . ' na ' . $products[$id]['quantity']);

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/wyczysc", name="basket_clear")
     */
    public function clearAction()
    {

        //alrternatywnie
        $this->get('basket')
                ->clear();

        $this->addFlash('notice', 'Koszyk został pomyślnie wyczyszczony');

        return $this->redirectToRoute('products_list');
    }

    /**
     * @Route("/koszyk/kup", name="basket_order")
     * @Template()
     */
    public function orderAction()
    {
        $basket = $this->get('basket');
        $products = $basket->getProducts();

        $em = $this->getDoctrine()->getManager();
        $orders = new Orders();

        foreach ($products as $value) {
            $product = $em->getRepository('AppBundle:Product')
                    ->find($value['id']);

            $product->addOrder($orders);
            $orders->addProduct($product);
            $orders->setCreatedAt();
            $orders->setModifiedAt();
            $orders->setOrderValue($basket->getPrice());
            $orders->setRealised(FALSE);
            $orders->setUser($user = $this->getUser());
        }

        $em->persist($orders);
        $em->persist($product);
        $em->flush();
        $basket->clear();
        return $this->redirectToRoute('products_list');
    }

    private function getProduct($id)
    {
        $products = $this->getProducts();

        return $products[$id];
    }

}
