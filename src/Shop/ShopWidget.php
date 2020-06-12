<?php

namespace App\Shop;

use App\Admin\AdminWidgetInterface;
use App\Shop\Table\ProductTable;
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

    public function __construct(RendererInterface $renderer, ProductTable $table)
    {
        $this->renderer = $renderer;
        $this->table = $table;
    }

    public function render(): string
    {
        $count = $this->table->count();
        return $this->renderer->render('@shop/admin/widget', compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@shop/admin/menu');
    }
}
