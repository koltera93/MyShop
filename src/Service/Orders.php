<?php
/**
 * Created by PhpStorm.
 * User: volodya
 * Date: 24.09.18
 * Time: 19:09
 */

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Orders
{
    const CART_SESSION_NAME = 'shoppingCartId';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    private $adminEmail;

    public function __construct(
        entityManagerInterface $entityManager,
        SessionInterface $session,
        ParameterBagInterface $parameterBag,
        Mailer $mailer
    )
    {
     $this->em = $entityManager;
     $this->session = $session;
     $this->adminEmail = $parameterBag->get('admin_email');
     $this->mailer = $mailer;
    }


    /**
     * @param Product $product
     * @param int $quantity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function addToCart(Product $product, ?User $user , $quantity = 1)
    {
        $order = $this->getCartFromSession($user);
        $items = $order->getItems();

        if (isset($items[$product->getId()]))
        {
            $item = $items[$product->getId()];
            $item->addQuantity($quantity);
        }
        else
            {
                $item = new OrderItem();
                $item->setProduct($product);
                $item->addQuantity($quantity);
                $order->addItem($item);
            }

            $this->saveCart($order);

    }


    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws ORMInvalidArgumentException
     */
    public function getCartFromSession(?User $user): Order
    {
        $orderId = $this->session->get(self::CART_SESSION_NAME);

        if ($orderId)
        {
            $order = $this->em->find(Order::class, $orderId);
        }
        else
        {
            $order = null;
        }

        if (!$order)
        {
            $order = new Order();
            $order->setUser($user);
        }

        return $order;
    }

    /**
     * @param OrderItem $item
     * @param int $quantity
     * @return Order
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateItemQuantity(OrderItem $item, int $quantity): Order
    {
        $item->setQuantity($quantity);
        $this->em->flush();

        return $item->getOrder();
    }

    /**
     * @param OrderItem $item
     * @return Order
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeItem(OrderItem $item): Order
    {
        $order = $item->getOrder();
        $order->removeItem($item);
        $this->em->remove($item);
        $this->em->flush();

        return $order;
    }

    /**
     * @throws
     */
    public function checkout(Order $order)
    {
        $order->setStatus(Order::STATUS_ORDERED);
        $this->em->flush();
        $this->removeCart();
        $this->mailer->send($this->adminEmail, 'orders/admin.email.twig', ['order' => $order]);
    }

    /**
     * @param Order $order
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveCart(Order $order)
    {
        $this->em->persist($order);
        $this->em->flush();
        $this->session->set(self::CART_SESSION_NAME, $order->getId());

    }

    private function removeCart()
    {
        $this->session->remove(self::CART_SESSION_NAME);
    }
}