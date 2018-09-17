<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $dateOfCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statusOfPayment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $User;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountOfOrder;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="Items")
     */
    private $OrderItem;

    public function __construct()
    {
        $this->OrderItem = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusOfPayment(): ?bool
    {
        return $this->statusOfPayment;
    }

    public function setStatusOfPayment(bool $statusOfPayment): self
    {
        $this->statusOfPayment = $statusOfPayment;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->User;
    }

    public function setUser(string $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getAmountOfOrder(): ?int
    {
        return $this->amountOfOrder;
    }

    public function setAmountOfOrder(int $amountOfOrder): self
    {
        $this->amountOfOrder = $amountOfOrder;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItem(): Collection
    {
        return $this->OrderItem;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->OrderItem->contains($orderItem)) {
            $this->OrderItem[] = $orderItem;
            $orderItem->setItems($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->OrderItem->contains($orderItem)) {
            $this->OrderItem->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getItems() === $this) {
                $orderItem->setItems(null);
            }
        }

        return $this;
    }

}
