<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\AdminCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends Controller
{

    /**
     * @Route("/produkty/{id}", name="products_list", defaults={"id" = false}, requirements={"id": "\d+"})
     */
    public function indexAction(Request $request, Category $category = null)
    {
        $getProductsQuery = $this->getDoctrine()
                ->getRepository('AppBundle:Product')
                ->getProductsQuery($category);

        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
                $getProductsQuery, $request->query->get('page', 1), 8
        );
        return $this->render('products/index.html.twig', [
                    'products' => $products,
        ]);
    }

    /**
     * @Route("/produkt/{id}", name="product_show")
     */
        public function showAction(Product $product, Request $request)
    {
        $comment = new Comment();
        $comment->setProduct($product);
        
        $form = $this->createForm(new AdminCommentType(), $comment);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($comment);
            $em->flush();
            
            $this->addFlash('notice', "Komentarz został pomyślnie zapisany.");
            
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('products/show.html.twig', [
                    'product_details' => $product,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @Route("/szukaj", name="product_search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->query->get('query');

        $products = $this->getDoctrine()
                //alternatywnie
                //->getMenager()
                //->createQueryBuilder()
                //->from('AppBundle:Product','p')
                //-----------------------------------
                ->getRepository('AppBundle:Product')
                ->createQueryBuilder('p')
                //-----------------------------------
                ->SELECT('p')
                ->where('p.name LIKE :query')
                ->orWhere('p.description LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();


        return $this->render('products/search.html.twig', [
                    'products' => $products,
                    'query' => $query,
        ]);
    }

    /**
     * 
     * @Route("/komentarz/dodaj")
     * 
     */
}
