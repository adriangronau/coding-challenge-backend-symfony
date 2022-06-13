<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $timestamp;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerFirstname;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerLastname;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerEmail;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerAddressStreet;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerAddressStreetNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerAddressPostcode;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerAddressCity;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $customerAddressCountry;

    #[ORM\OneToMany(mappedBy: 'orderId', targetEntity: OrderItem::class)]
    private ArrayCollection $orderItems;

    #[ORM\Column(type: 'integer')]
    private ?int $foreignId;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getCustomerFirstname(): ?string
    {
        return $this->customerFirstname;
    }

    public function setCustomerFirstname(string $customerFirstname): self
    {
        $this->customerFirstname = $customerFirstname;

        return $this;
    }

    public function getCustomerLastname(): ?string
    {
        return $this->customerLastname;
    }

    public function setCustomerLastname(string $customerLastname): self
    {
        $this->customerLastname = $customerLastname;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getCustomerAddressStreet(): ?string
    {
        return $this->customerAddressStreet;
    }

    public function setCustomerAddressStreet(string $customerAddressStreet): self
    {
        $this->customerAddressStreet = $customerAddressStreet;

        return $this;
    }

    public function getCustomerAddressStreetNumber(): ?string
    {
        return $this->customerAddressStreetNumber;
    }

    public function setCustomerAddressStreetNumber(string $customerAddressStreetNumber): self
    {
        $this->customerAddressStreetNumber = $customerAddressStreetNumber;

        return $this;
    }

    public function getCustomerAddressPostcode(): ?string
    {
        return $this->customerAddressPostcode;
    }

    public function setCustomerAddressPostcode(string $customerAddressPostcode): self
    {
        $this->customerAddressPostcode = $customerAddressPostcode;

        return $this;
    }

    public function getCustomerAddressCity(): ?string
    {
        return $this->customerAddressCity;
    }

    public function setCustomerAddressCity(string $customerAddressCity): self
    {
        $this->customerAddressCity = $customerAddressCity;

        return $this;
    }

    public function getCustomerAddressCountry(): ?string
    {
        return $this->customerAddressCountry;
    }

    public function setCustomerAddressCountry(string $customerAddressCountry): self
    {
        $this->customerAddressCountry = $customerAddressCountry;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderId() === $this) {
                $orderItem->setOrderId(null);
            }
        }

        return $this;
    }

    public function getForeignId(): ?int
    {
        return $this->foreignId;
    }

    public function setForeignId(int $foreignId): self
    {
        $this->foreignId = $foreignId;

        return $this;
    }
}
