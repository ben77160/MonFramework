<?php

namespace App\Shop\Table;

use App\Auth\User;
use App\Shop\Entity\Product;
use App\Shop\Entity\Purchase;
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
}
