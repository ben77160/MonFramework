<?php


namespace App\Basket\Table;

use App\Basket\BasketRow;
use Framework\Database\Table;

class BasketRowTable extends Table
{

    protected $table = 'baskets_products';

    protected $entity = BasketRow::class;
}
