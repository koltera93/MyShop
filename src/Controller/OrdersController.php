<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Service\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index()
    {
        return $this->render('orders/index.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }


    /**
     * @Route("/orders/add-to-cart/{id}/{quantity}", name="orders_add_to_cart")
     *
     * @param Orders $orders
     * @param Request $request
     * @param Product $product
     * @param int $quantity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function addToCart(Orders $orders, Request $request, Product $product, $quantity = 1)
    {
        $orders->addToCart($product, $quantity);

        if ($request->isXmlHttpRequest()) {
            return $this->cartInHeader($orders);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Orders $orders
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     *
     * @@Route("/orders/cart-in-header" , name="orders-cart-in-header")
     */
    public function cartInHeader(Orders $orders)
    {
        $cart = $orders->getCartFromSession();

        return $this->render('orders/cart_in_header.html.twig', ['cart' => $cart]);
    }

    /**
     * @param Orders $orders
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     *
     * @Route("/orders/cart" , name="cart")
     */
    public function cart(Orders $orders)
    {
        $cart = $orders->getCartFromSession();

        return $this->render('orders/cart.html.twig',
            ['cart' => $cart]);
    }
}
