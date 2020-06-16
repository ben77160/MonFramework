<?php
namespace App\Basket\Table;

use App\Basket\Basket;
use App\Basket\BasketRow;
use App\Shop\Entity\Product;
use App\Shop\Table\ProductTable;

class BasketTable
{

    private $productTable;

    public function __construct(\PDO $pdo)
    {
        $this->productTable = new ProductTable($pdo);
    }

    public function hydrateBasket(Basket $basket)
    {
        $rows = $basket->getRows();
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
}
