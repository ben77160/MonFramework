<?php


namespace App\Basket;

use App\Shop\Entity\Product;

class BasketRow
{

    /**
     * @var int|null
     */
    private $id;

    private $product;

    private $productId;

    private $quantity = 1;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->productId = $product->getId();
    }

    /**
     * @return mixed
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id)
    {
        $this->id = $id;
    }
}
