<?php

namespace App\Basket;

use App\Shop\Entity\Product;

class Basket
{

    /**
     * @var BasketRow[]
     */
    protected $rows = [];

    /**
     * Ajoute un produit au panier
     * @param Product $product
     */
    public function addProduct(Product $product, ?int $quantity = null): void
    {
        if ($quantity === 0) {
            $this->removeProduct($product);
        } else {
            $row = $this->getRow($product);
            if ($row === null) {
                $row = new BasketRow();
                $row->setProduct($product);
                $this->rows[] = $row;
            } else {
                $row->setQuantity($row->getQuantity() + 1);
            }
            if ($quantity !== null) {
                $row->setQuantity($quantity);
            }
        }
    }

    /**
     * Supprime un produit du panier
     * @param Product $product
     */
    public function removeProduct(Product $product): void
    {
        $this->rows = array_filter($this->rows, function ($row) use ($product) {
            return $row->getProductId() !== $product->getId();
        });
    }

    /**
     * Nombre de produits dans le panier
     * @return int
     */
    public function count(): int
    {
        return array_reduce($this->rows, function ($count, BasketRow $row) {
            return $row->getQuantity() + $count;
        }, 0);
    }

    /**
     * Renvoie le prix du panier
     * @return float
     */
    public function getPrice(): float
    {
        return array_reduce($this->rows, function ($total, BasketRow $row) {
            return $row->getQuantity() * $row->getProduct()->getPrice() + $total;
        }, 0);
    }

    /**
     * @return BasketRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param Product $product
     * @return BasketRow|null
     */
    protected function getRow(Product $product): ?BasketRow
    {
        /** @var BasketRow $row */
        foreach ($this->rows as $row) {
            if ($row->getProductId() === $product->getId()) {
                return $row;
            }
        }
        return null;
    }

    /**
     * Vide le panier
     */
    public function empty()
    {
        $this->rows = [];
    }
}
