<?php

namespace App\Basket\Table;

use App\Auth\User;
use App\Basket\Basket;
use App\Basket\BasketRow;
use App\Basket\Entity\Basket as BasketEntity;
use App\Shop\Entity\Product;
use App\Shop\Table\ProductTable;
use Framework\Database\Hydrator;
use Framework\Database\Table;

class BasketTable extends Table
{

    protected $table = 'baskets';

    protected $entity = BasketEntity::class;

    private $productTable;

    private $basketRowTable;

    public function __construct(\PDO $pdo)
    {
        $this->productTable = new ProductTable($pdo);
        $this->basketRowTable = new BasketRowTable($pdo);
        parent::__construct($pdo);
    }

    public function hydrateBasket(Basket $basket)
    {
        $rows = $basket->getRows();
        if (empty($rows)) {
            return null;
        }
        $ids = array_map(function (BasketRow $row) {
            return $row->getProductId();
        }, $rows);
        /** @var Product[] $products */
        $products = $this->productTable->makeQuery()
            ->where('id IN (' . implode(',', $ids) . ')')
            ->fetchAll();
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }
        foreach ($rows as $row) {
            $row->setProduct($productsById[$row->getProductId()]);
        }
    }

    /**
     * @return ProductTable
     */
    public function getProductTable(): ProductTable
    {
        return $this->productTable;
    }

    public function findForUser(int $userId): ?BasketEntity
    {
        return $this->makeQuery()->where("user_id = $userId")->fetch() ?: null;
    }

    public function createForUser(int $userId): BasketEntity
    {
        $params = [
            'user_id' => $userId
        ];
        $this->insert($params);
        $params['id'] = $this->pdo->lastInsertId();
        return Hydrator::hydrate($params, $this->entity);
    }

    public function addRow(BasketEntity $basket, Product $product, int $quantity = 1): BasketRow
    {
        $params = [
            'basket_id'  => $basket->getId(),
            'product_id' => $product->getId(),
            'quantity'   => $quantity
        ];
        $this->basketRowTable->insert($params);
        $params['id'] = $this->pdo->lastInsertId();
        /** @var BasketRow $row */
        $row = Hydrator::hydrate($params, $this->basketRowTable->getEntity());
        $row->setProduct($product);
        return $row;
    }

    public function updateRowQuantity(BasketRow $row, int $quantity): BasketRow
    {
        $this->basketRowTable->update($row->getId(), ['quantity' => $quantity]);
        $row->setQuantity($quantity);
        return $row;
    }

    public function deleteRow(BasketRow $row): void
    {
        $this->basketRowTable->delete($row->getId());
    }

    public function findRows(BasketEntity $basketEntity): array
    {
        return $this->basketRowTable
            ->makeQuery()
            ->where("basket_id = {$basketEntity->getId()}")
            ->fetchAll()
            ->toArray();
    }

    public function deleteRows(\App\Basket\Entity\Basket $basket)
    {
        return $this->pdo->exec('DELETE FROM baskets_products WHERE basket_id = ' . $basket->getId());
    }
}
