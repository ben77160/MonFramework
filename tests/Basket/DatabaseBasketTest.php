<?php


namespace Tests\Basket;

use App\Basket\Basket;
use App\Basket\DatabaseBasket;
use App\Basket\Table\BasketRowTable;
use App\Basket\Table\BasketTable;
use Tests\DatabaseTestCase;

class DatabaseBasketTest extends DatabaseTestCase
{

    private $basketTable;
    private $basket;
    private $rowTable;

    public function setUp()
    {
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);
        $this->basketTable = new BasketTable($pdo);
        $this->rowTable = new BasketRowTable($pdo);
        $this->basket = new DatabaseBasket(2, $this->basketTable);
    }

    public function testAddProduct()
    {
        $products = $this->basketTable->getProductTable()
            ->makeQuery()
            ->limit(2)
            ->fetchAll();
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[1], 2);
        $this->assertEquals(2, $this->rowTable->count());
    }

    public function testPersistence()
    {
        $products = $this->basketTable->getProductTable()
            ->makeQuery()
            ->limit(2)
            ->fetchAll();
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[1], 2);

        $basket = new DatabaseBasket(2, $this->basketTable);
        $this->assertEquals(3, $basket->count());
    }

    public function testRemoveProduct()
    {
        $products = $this->basketTable->getProductTable()
            ->makeQuery()
            ->limit(2)
            ->fetchAll();
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[1], 2);
        $this->basket->removeProduct($products[1]);
        $this->assertEquals(1, $this->rowTable->count());
    }

    public function testMergeBasket()
    {
        $products = $this->basketTable->getProductTable()
            ->makeQuery()
            ->limit(2)
            ->fetchAll();
        $this->basket->addProduct($products[0]);

        $basket = new Basket();
        $basket->addProduct($products[0], 2);
        $basket->addProduct($products[1]);

        $this->basket->merge($basket);

        $this->assertEquals(4, $this->basket->count());
        $this->assertEquals(3, $this->basket->getRows()[0]->getQuantity());
        $this->assertEquals(1, $this->basket->getRows()[1]->getQuantity());
    }

    public function testEmptyBasket()
    {
        $products = $this->basketTable->getProductTable()
            ->makeQuery()
            ->limit(2)
            ->fetchAll();
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[0]);
        $this->basket->addProduct($products[1], 2);
        $this->basket->empty();
        $this->assertEquals(0, $this->rowTable->count());
    }
}
