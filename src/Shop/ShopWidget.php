<?php

namespace App\Shop;

use App\Admin\AdminWidgetInterface;
use App\Shop\Table\ProductTable;
use App\Shop\Table\PurchaseTable;
use Framework\Renderer\RendererInterface;

class ShopWidget implements AdminWidgetInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var ProductTable
     */
    private $table;
    /**
     * @var PurchaseTable
     */
    private $purchaseTable;

    public function __construct(
        RendererInterface $renderer,
        ProductTable $table,
        PurchaseTable $purchaseTable
    ) {
        $this->renderer = $renderer;
        $this->table = $table;
        $this->purchaseTable = $purchaseTable;
    }

    public function render(): string
    {
        $count = $this->table->count();
        $total = $this->purchaseTable->getMonthRevenue();
        return $this->renderer->render('@shop/admin/widget', compact('count', 'total'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@shop/admin/menu');
    }
}
