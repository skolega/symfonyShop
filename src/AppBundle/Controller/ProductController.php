<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    
    /**
     * @Route("/produkt/{id}", name="product")
     */
    public function productAction($id)
    {   
        $products = $this->getProducts();
        $product = $products[$id];
    
        return $this->render('product/index.html.twig' , [
            'product_details' => $product,
            ]);
    }
    
    private function getProducts()
    {
        $file = file('product.txt');
        $products = array();
        
        foreach ($file as $p) 
        {
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
}

