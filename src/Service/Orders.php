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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
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

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;
    }


    /**
     * @param Product $product
     * @param int $quantity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function addToCart(Product $product, $quantity = 1)
    {
        $order = $this->getCartFromSession();
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
    public function getCartFromSession()
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

}