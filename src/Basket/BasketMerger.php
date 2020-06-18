<?php


namespace App\Basket;

use App\Auth\Event\LoginEvent;
use App\Basket\Table\BasketTable;

class BasketMerger
{

    /**
     * @var SessionBasket
     */
    private $basket;
    /**
     * @var BasketTable
     */
    private $basketTable;

    public function __construct(SessionBasket $basket, BasketTable $basketTable)
    {
        $this->basket = $basket;
        $this->basketTable = $basketTable;
    }

    public function __invoke(LoginEvent $event)
    {
        $user = $event->getTarget();
        (new DatabaseBasket($user->getId(), $this->basketTable))->merge($this->basket);
        $this->basket->empty();
    }
}
