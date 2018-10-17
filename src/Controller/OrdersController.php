<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\MakeOrderType;
use App\Service\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $orders->addToCart($product,$this->getUser() , $quantity);

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
     * @Route("/orders/cart-in-header" , name="orders_cart_in_header")
     */
    public function cartInHeader(Orders $orders)
    {
        $cart = $orders->getCartFromSession($this->getUser());

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
        $cart = $orders->getCartFromSession($this->getUser());

        return $this->render('orders/cart.html.twig',
            ['cart' => $cart]);
    }

    /**
     * @param OrderItem $item
     * @param Orders $orders
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/cart/update-quantity/{id}", name="orders_update_item_quantity")
     */
    public function updateItemQuantity(OrderItem $item, Orders $orders, Request $request)
    {
        $quantity = (int)$request->request->get('quantity');

        if ($quantity < 1 || $quantity > 1000)
        {
            throw new \InvalidArgumentException();
        }

        $cart = $orders->updateItemQuantity($item, $quantity);

        return new JsonResponse(
          $this->renderView('orders/cart.json.twig',
              ['cart' => $cart]), 200, [], true
        );
    }

    /**
     * @param OrderItem $item
     * @param Orders $orders
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws
     *
     * @Route("/cart/remove-item/{id}", name="orders_remove_item")
     */
    public function removeItem(OrderItem $item, Orders $orders, Request $request)
    {
        $cart = $orders->removeItem($item);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                $this->renderView('orders/cart.json.twig', ['cart' => $cart]),
                200,
                [],
                true
            );
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/checkout", name = "orders_checkout")
     * @throws
     */
    public function makeOrder(Orders $orders, Request $request)
    {
        $cart = $orders->getCartFromSession($this->getUser());
        $form = $this->createForm(MakeOrderType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $orders->checkout($cart);
            $this->addFlash('success', 'Спасибо за заказ.');

            return $this->redirectToRoute('orders_success');
        }

        return $this->render('orders/checkout.html.twig',
            [
            'cart' => $cart,
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/cart/success", name = "orders_success")
     * @throws
     */
    public function success(Orders $orders)
    {
        $order = $orders->getCartFromSession($this->getUser());

        if (!$order->getStatusOfPayment()){
        $liqpay = new \LiqPay(getenv('LIQPAY_PUBLIC_KEY'), getenv('LIQPAY_PRIVATE_KEY'));
        $html = $liqpay->cnb_form([
            'action' => 'pay',
            'amount' => $order->getAmountOfOrder(),
            'currency' => 'UAH',
            'description' => 'Покупка в магазине',
            'order_id' => $order->getId(),
            'version' => '3',
            'result_url' => $this->generateUrl('orders_payment_result', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'sandbox' => '1',
        ]);
    }
    else
        {
            $orders->removeCart();
            $html = '';
        }

        return $this->render('orders/success.html.twig', [
            'paymentButton' => $html,
        ]);
    }

    /**
     * @Route("/payment/result", name = "orders_payment_result")
     * @throws
     */
    public function paymentResult(Orders $orders)
    {
        $order = $orders->getCartFromSession($this->getUser());

        $liqpay = new \LiqPay(getenv('LIQPAY_PUBLIC_KEY'), getenv('LIQPAY_PRIVATE_KEY'));
        $responce = $liqpay->api(
            'request',[
                'action' => 'status',
                'version' => '3',
                'order_id' => $order->getId(),
            ]
        );

        if ($responce->status == 'success' || $responce->status == 'sandbox' || $responce->status == 'wait_accept')
        {
            $orders->setPaid($order);
            $this->addFlash('success', 'Заказ успешно оплачен');
        }
        else
        {
            $this->addFlash('error', 'Ошибка оплаты. Попробуйте еще раз.');
        }

        return $this->redirectToRoute('orders_success');
    }

}
