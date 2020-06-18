<?php

namespace App\Basket\Entity;

class Order
{

    private $id;

    private $userId;

    private $vat;

    private $price;

    private $country;

    private $createdAt;

    private $stripeId;

    private $rows = [];

    /**
     * @return OrderRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param OrderRow[] $rows
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;
    }

    public function addRow(OrderRow $row): void
    {
        $this->rows[] = $row;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getVat(): ?float
    {
        return $this->vat;
    }

    /**
     * @param mixed $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return mixed
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param $datetime
     */
    public function setCreatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->createdAt = new \DateTime($datetime);
        } else {
            $this->createdAt = $datetime;
        }
    }

    /**
     * @return mixed
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * @param mixed $stripeId
     */
    public function setStripeId($stripeId)
    {
        $this->stripeId = $stripeId;
    }
}
