<?php

namespace App\Basket\Table;

use App\Basket\Entity\OrderRow;
use Framework\Database\Table;

class OrderRowTable extends Table
{

    protected $table = 'orders_products';

    protected $entity = OrderRow::class;
}
