<?php
namespace App\Shop\Table;

use App\Shop\Entity\Product;
use Framework\Database\Query;
use Framework\Database\Table;

class ProductTable extends Table
{

    protected $entity = Product::class;

    protected $table = "products";

    public function findPublic(): Query
    {
        return $this->makeQuery()
            ->where('created_at < NOW()');
    }
}
