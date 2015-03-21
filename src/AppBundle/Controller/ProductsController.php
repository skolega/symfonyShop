<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;

class ProductsController extends Controller
{

    /**
     * @Route("/produkty/{id}", name="products_list", defaults={"id" = false})
     */
    public function indexAction(Category $category = null)
    {

        if ($category) {
            $products = $this->getDoctrine()
                    ->getRepository('AppBundle:Product')
                    ->findBy(['category' => $category]);
        } else {
            $products = $this->getDoctrine()
                    ->getRepository('AppBundle:Product')
                    ->findAll();
        }



        return $this->render('products/index.html.twig', [
                    'products' => $products,
        ]);
    }

}
