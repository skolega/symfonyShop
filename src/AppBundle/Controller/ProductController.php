<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;

class ProductController extends Controller
{

    /**
     * @Route("/produkt/{id}", name="product")
     */
    public function productAction(Product $product, $id)
    {
        $qb = $this->getDoctrine()
                ->getRepository('AppBundle:Product')
                ->findOneBy(['id' => $id]);

        return $this->render('product/index.html.twig', [
                    'product_details' => $qb,
        ]);
    }

}
