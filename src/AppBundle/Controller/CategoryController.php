<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;

class CategoryController extends Controller
{

    /**
     * @Route("/list")
     */
    public function listAction(Request $request)
    {

        $qb = $this
                ->getDoctrine()
                ->getManager()
                ->createQueryBuilder();

        $qb
                ->select('c', 'p')
                ->from('AppBundle:Category', 'c')
                ->innerJoin('c.products', 'p');

        $categories = $qb
                ->getQuery()
                ->getResult();


        return $this->render('Category/list.html.twig', [
                    'categories' => $categories,
                    'basket' => $this->get('basket'),
        ]);
    }

}
