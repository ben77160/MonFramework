<?php


namespace App\Basket;

use App\Basket\Entity\Basket;
use App\Basket\Table\BasketTable;
use App\Shop\Entity\Product;
use App\Basket\Basket as BasketClass;

class DatabaseBasket extends BasketClass
{

    /**
     * @var int
     */
    private $userId;

    /**
     * @var null|Basket
     */
    private $basketEntity;

    /**
     * @var BasketTable
     */
    private $basketTable;

    public function __construct(int $userId, BasketTable $basketTable)
    {
        $this->userId = $userId;
        $this->basketTable = $basketTable;
        $this->basketEntity = $this->basketTable->findForUser($userId);
        if ($this->basketEntity) {
            $this->rows = $this->basketTable->findRows($this->basketEntity);
        }
    }

    public function addProduct(Product $product, ?int $quantity = null): void
    {
        if ($this->basketEntity === null) {
            $this->basketEntity = $this->basketTable->createForUser($this->userId);
        }
        if ($quantity === 0) {
            $this->removeProduct($product);
        } else {
            $row = $this->getRow($product);
            if ($row === null) {
                $this->rows[] = $this->basketTable->addRow($this->basketEntity, $product, $quantity ?: 1);
            } else {
                $this->basketTable->updateRowQuantity($row, $quantity ?: ($row->getQuantity() + 1));
            }
        }
    }

    public function removeProduct(Product $product): void
    {
        $row = $this->getRow($product);
        $this->basketTable->deleteRow($row);
        parent::removeProduct($product);
    }

    public function merge(\App\Basket\Basket $basket)
    {
        $rows = $basket->getRows();
        foreach ($rows as $r) {
            $row = $this->getRow($r->getProduct());
            if ($row) {
                $this->addProduct($r->getProduct(), $row->getQuantity() + $r->getQuantity());
            } else {
                $this->addProduct($r->getProduct(), $r->getQuantity());
            }
        }
    }

    public function empty()
    {
        $this->basketTable->deleteRows($this->basketEntity);
        parent::empty();
    }
}
