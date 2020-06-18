<?php

namespace App\Basket\Table;

use App\Auth\User;
use App\Basket\Basket;
use App\Basket\BasketRow;
use App\Basket\Entity\Order;
use App\Basket\Entity\OrderRow;
use App\Shop\Entity\Product;
use Framework\Database\Query;
use Framework\Database\Table;

class OrderTable extends Table
{

    protected $table = 'orders';

    protected $entity = Order::class;

    /**
     * @var OrderRowTable
     */
    protected $basketRowTable;

    public function findForUser(User $user): Query
    {
        return $this->makeQuery()->where("user_id = {$user->getId()}");
    }

    /**
     * @param Order[] $orders
     * @return Order[]
     */
    public function findRows($orders)
    {
        $ordersId = [];
        foreach ($orders as $order) {
            $ordersId[] = $order->getId();
        }
        $rows = $this->getRowTable()->makeQuery()
            ->where('o.order_id IN (' . implode(',', $ordersId) . ')')
            ->join('products as p', 'p.id = o.product_id')
            ->select('o.*', 'p.name as productName', 'p.slug as productSlug')
            ->fetchAll();
        /** @var OrderRow $row */
        foreach ($rows as $row) {
            foreach ($orders as $order) {
                if ($order->getId() === $row->getOrderId()) {
                    $product = new Product();
                    $product->setId($row->getProductId());
                    $product->setName($row->productName);
                    $product->setSlug($row->productSlug);
                    $row->setProduct($product);
                    $order->addRow($row);
                    break;
                }
            }
        }
        return $rows;
    }

    public function createFromBasket(Basket $basket, array $params = []): void
    {
        $params['price'] = $basket->getPrice();
        $params['created_at'] = date('Y-m-d H:i:s');

        $this->pdo->beginTransaction();
        $this->insert($params);
        $orderId = $this->getPdo()->lastInsertId();
        /** @var BasketRow $row */
        foreach ($basket->getRows() as $row) {
            $this->getRowTable()->insert([
                'order_id' => $orderId,
                'price' => $row->getProduct()->getPrice(),
                'product_id' => $row->getProductId(),
                'quantity' => $row->getQuantity()
            ]);
        }
        $this->pdo->commit();
    }

    private function getRowTable(): OrderRowTable
    {
        if ($this->basketRowTable === null) {
            $this->basketRowTable = new OrderRowTable($this->pdo);
        }
        return $this->basketRowTable;
    }
}
