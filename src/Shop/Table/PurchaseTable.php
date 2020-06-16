<?php

namespace App\Shop\Table;

use App\Auth\User;
use App\Shop\Entity\Product;
use App\Shop\Entity\Purchase;
use Framework\Database\QueryResult;
use Framework\Database\Table;

class PurchaseTable extends Table
{

    protected $entity = Purchase::class;

    protected $table = "purchases";

    public function findFor(Product $product, User $user): ?Purchase
    {
        return $this->makeQuery()
            ->where('product_id = :product AND user_id = :user')
            ->params(['user' => $user->getId(), 'product' => $product->getId()])
            ->fetch() ?: null;
    }

    public function findForUser(User $user): QueryResult
    {
        return $this->makeQuery()
            ->select('p.*, pr.name as product_name')
            ->where('p.user_id = :user')
            ->join('products as pr', 'pr.id = p.product_id')
            ->params(['user' => $user->getId()])
            ->fetchAll();
    }

    public function findWithProduct(int $purchaseId): Purchase
    {
        return $this->makeQuery()
            ->select('p.*, pr.name as product_name')
            ->join('products as pr', 'pr.id = p.product_id')
            ->where("p.id = $purchaseId")
            ->fetchOrFail();
    }

    public function getMonthRevenue()
    {
        return $this->makeQuery()
            ->select('SUM(price)')
            ->where("p.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()")
            ->fetchColumn();
    }
}
