<?php

namespace App\Controller;

use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="categories")
     */
    public function index()
    {
        return $this->render('category/CategoryShow.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


    /**
     * @Route("/Category" , name="category_show")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categories(Category $category)
    {
        return $this->render('category/CategoryShow.html.twig' ,
        [
            'category' => $category
        ]
        );
    }
}
