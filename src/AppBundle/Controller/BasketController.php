<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller
{

    /**
     * @Route("/koszyk", name="basket")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        $basket = $session->get('basket', array());
        $products = $this->getProducts();

        $productsInBasket = array();
        foreach ($basket as $id => $b) {
            $productsInBasket[] = $products[$id];
        }
        return array(
            'products_in_basket' => $productsInBasket,
        );
    }

    /**
     * @Route("/koszyk/{id}/dodaj", name="basket_add")
     */
    public function addAction($id, Request $request)
    {
        $session = $request->getSession();

        $basket = $session->get('basket', array());

        $basket[$id] = 1;
        $session->set('basket', $basket);
        $this->addFlash('notice', 'Produkt został dodany do koszyka');

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/{id}/usun", name="basket_remove")
     */
    public function removeAction($id, Request $request)
    {
        $session = $request->getSession();

        $basket = $session->get('basket', array());

        if (!array_key_exists($id, $basket)) {
            $this->addFlash('notice', 'Produkt nie znajduje się w koszyku');

            return $this->redirectToRoute('basket');
        }

        unset($basket[$id]);
        $product = $this->getProduct($id);
        $session->set('basket', $basket);
        
        //alternatywne wyświetlanie
        //$this->addFlash('notice',
        // 'Produkt .$product['name']. został usunięty z koszyka');
        
        $this->addFlash('notice',
                sprintf('Produkt %s został usunięty z koszyka',$product['name']));
        
        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/{id}/zaktualizuj-ilosc/{quantity}", name="quantity_update")
     */
    public function updateAction($id, $quantity, Request $request)
    {

        $session = $request->getSession();
        $basket = $session->get('basket', array());
        $products = $this->getProducts();

        $session->set('basket', $basket);
        $products[$id]['quantity'] = 3;
        $this->addFlash('notice', 'Tu powinno sie zmienić ilość produktu ' . $products[$id]['name'] . ' na ' . $products[$id]['quantity']);

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/wyczysc", name="basket_clear")
     * @Template()
     */
    public function clearAction(Request $request)
    {

        $session = $request->getSession();
        $basket = $session->get('basket', array());
        $basket = array();

        $session->set('basket', $basket);
        $this->addFlash('notice', 'Koszyk został wyczyszczony');

        return $this->redirectToRoute('products_list');
    }

    /**
     * @Route("/koszyk/kup", name="basket_buy")
     * @Template()
     */
    public function buyAction()
    {
        $this->addFlash('notice', 'Towar Kupiony :)');
        return $this->redirectToRoute('basket');
    }

    private function getProducts()
    {
        $file = file('product.txt');
        $products = array();

        foreach ($file as $p) {
            $e = explode(':', trim($p));
            $products[$e[0]] = array(
                'id' => $e[0],
                'name' => $e[1],
                'price' => $e[2],
                'desc' => $e[3],
                'quantity' => 1,
            );
        }

        return $products;
    }

    private function getProduct($id)
    {
        $products = $this->getProducts();

        return $products[$id];
    }

}
