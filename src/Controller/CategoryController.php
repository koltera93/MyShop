<?php

namespace App\Controller;

use App\Entity\Category;

use App\Entity\Product;
use App\Service\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="categories")
     */
    public function index(Products $products)
    {
        return $this->render('category/index.html.twig', [
            'categories' => $products->getAllCategories(),
        ]);
    }


    /**
     * @Route("/category/{id}" , name="category_show")
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
