<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("orders")
 */
class Order
{
    const STATUS_NEW = 1;
    const STATUS_ORDERED = 2;
    const STATUS_SENT = 3;
    const STATUS_RECEIVED = 4;



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
     * @ORM\Column(type="integer")
     */
    private $amountOfOrder;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order" ,
     * orphanRemoval=true, indexBy="product_id" , cascade={"persist"})
     *
     * @var OrderItem[]
     * @Assert\NotBlank(groups={"MakeOrder"})
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"MakeOrder"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"MakeOrder"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(groups={"MakeOrder"}, checkMX = true)
     * @Assert\NotBlank(groups={"MakeOrder"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"MakeOrder"})
     * @Assert\Regex(groups={"MakeOrder"}, pattern="/^\+\d{12}$/", message="Введите телефон в формате +380981234567")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function __construct()
    {
        $this-> dateOfCreation = new \DateTime();
        $this-> status = self::STATUS_NEW;
        $this-> statusOfPayment = false;
        $this-> amountOfOrder = 0;
        $this-> items = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        if ($user)
        {
            $this->firstName = $user->getFirstName();
            $this->lastName = $user->getLastName();
            $this->email = $user->getEmail();
            $this->address = $user->getAdress();
            $this->phone = $user->getPhone();
        }

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
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $orderItem): self
    {
        if (!$this->items->contains($orderItem)) {
            $this->items[] = $orderItem;
            $orderItem->setOrder($this);
            $this->calculateAmount();
        }

        return $this;
    }

    public function removeItem(OrderItem $orderItem): self
    {
        if ($this->items->contains($orderItem)) {
            $this->items->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
                $this->calculateAmount();
            }
        }

        return $this;
    }

    public function calculateAmount()
    {
        $this->amountOfOrder = 0;

        foreach ($this->items as $item)
        {
            $this->amountOfOrder +=$item->getValue();
        }
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
