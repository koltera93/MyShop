<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
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
     * @ORM\Column(type="string", length=255)
     */
    private $Product;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumberOfOrderedItems;

    /**
     * @ORM\Column(type="integer")
     */
    private $Price;

    /**
     * @ORM\Column(type="integer")
     */
    private $Value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="OrderItem")
     */
    private $Items;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->Product;
    }

    public function setProduct(string $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getNumberOfOrderedItems(): ?int
    {
        return $this->NumberOfOrderedItems;
    }

    public function setNumberOfOrderedItems(int $NumberOfOrderedItems): self
    {
        $this->NumberOfOrderedItems = $NumberOfOrderedItems;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

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

    public function getItems(): ?Order
    {
        return $this->Items;
    }

    public function setItems(?Order $Items): self
    {
        $this->Items = $Items;

        return $this;
    }
}
