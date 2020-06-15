<?php

use Phinx\Migration\AbstractMigration;

class CreatePurchasesTable extends AbstractMigration
{
    public function change()
    {
        $this->table('purchases')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id')
            ->addColumn('product_id', 'integer')
            ->addForeignKey('product_id', 'products', 'id')
            ->addColumn('price', 'float', ['precision' => 6, 'scale' => 2])
            ->addColumn('vat', 'float', ['precision' => 6, 'scale' => 2])
            ->addColumn('country', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('stripe_id', 'string')
            ->create();
    }
}
