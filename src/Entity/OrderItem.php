<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("order_item")
 */
class OrderItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product" , inversedBy="orderItems")
     */
    private $Product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity; //Количество товаров

    /**
     * @ORM\Column(type="integer")
     */
    private $price; //Стоимость

    /**
     * @ORM\Column(type="integer")
     */
    private $Value; //Цена

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    public function __construct()
    {
        $this->quantity = 0;
        $this->price = 0;
        $this->Value = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->Product;
    }

    public function setProduct(Product $product): self
    {
        $this->Product = $product;
        $this->setPrice($product->getPrice());

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        $this->calculateCost();

        return $this;
    }

    public function calculateCost()
    {
        $this->Value = $this->price * $this->quantity;

        if($this->order)
        {
            $this->order->calculateAmount();
        }

    }

    public function addQuantity(int $quantity): self
    {
        $this->quantity +=$quantity;
        $this->calculateCost();

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $Price): self
    {
        $this->price = $Price;
        $this->calculateCost();

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->Value;
    }

    public function setValue(int $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

}
