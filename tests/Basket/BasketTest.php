<?php
namespace Tests\App\Basket;

use App\Basket\Basket;
use App\Shop\Entity\Product;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{

    private $basket;

    public function setUp()
    {
        $this->basket = new Basket();
    }

    public function testAddProduct()
    {
        $product1 = new Product();
        $product1->setId(1);
        $product2 = new Product();
        $product2->setId(2);
        $this->basket->addProduct($product1);
        $this->assertEquals(1, $this->basket->count());
        $this->assertCount(1, $this->basket->getRows());
        $this->basket->addProduct($product2);
        $this->assertEquals(2, $this->basket->count());
        $this->assertCount(2, $this->basket->getRows());
        $this->basket->addProduct($product1);
        $this->assertEquals(3, $this->basket->count());
        $this->assertCount(2, $this->basket->getRows());
    }

    public function testRemoveProduct()
    {
        $product1 = new Product();
        $product1->setId(1);
        $product2 = new Product();
        $product2->setId(2);
        $this->basket->addProduct($product1);
        $this->basket->addProduct($product2);
        $this->assertEquals(2, $this->basket->count());
        $this->basket->removeProduct($product1);
        $this->assertEquals(1, $this->basket->count());
    }

    public function testAddProductWithQuantity()
    {
        $product1 = new Product();
        $product1->setId(1);
        $product2 = new Product();
        $product2->setId(2);
        $this->basket->addProduct($product1, 3);
        $this->basket->addProduct($product2, 2);
        $this->assertEquals(5, $this->basket->count());
        $this->assertCount(2, $this->basket->getRows());
    }
}
