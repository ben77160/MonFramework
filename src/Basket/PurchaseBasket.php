<?php

namespace App\Basket;

use App\Auth\User;
use App\Basket\Table\BasketTable;
use App\Basket\Table\OrderTable;
use App\Shop\Table\PurchaseTable;
use App\Shop\Table\StripeUserTable;
use Framework\Api\Stripe;
use Staaky\VATRates\VATRates;
use Stripe\Card;
use Stripe\Customer;

class PurchaseBasket
{

    /**
     * @var PurchaseTable
     */
    private $orderTable;

    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * @var StripeUserTable
     */
    private $stripeUserTable;
    /**
     * @var BasketTable
     */
    private $basketTable;

    public function __construct(
        OrderTable $orderTable,
        BasketTable $basketTable,
        Stripe $stripe,
        StripeUserTable $stripeUserTable
    ) {
        $this->orderTable = $orderTable;
        $this->stripe = $stripe;
        $this->stripeUserTable = $stripeUserTable;
        $this->basketTable = $basketTable;
    }

    /**
     * Génère l'achat du produit pour l'utilisateur en utilisant Stripe
     * @param Basket $basket
     * @param User $user
     * @param string $token
     */
    public function process(Basket $basket, User $user, string $token)
    {
        // Calculer le prix TTC
        $this->basketTable->hydrateBasket($basket);
        $card = $this->stripe->getCardFromToken($token);
        $vatRate = (new VATRates())->getStandardRate($card->country) ?: 0;
        $price = floor($basket->getPrice() * ((100 + $vatRate) / 100));

        // Créer ou récupérer le customer de l'utilisateur
        $customer = $this->findCustomerForUser($user, $token);
        $card = $this->getMatchingCard($customer, $card);
        if ($card === null) {
            $card = $this->stripe->createCardForCustomer($customer, $token);
        }

        $charge = $this->stripe->createCharge([
            "amount" => $price * 100,
            "currency" => "eur",
            "source" => $card->id,
            "customer" => $customer->id,
            "description" => "Achat sur monsite.com"
        ]);

        $this->orderTable->createFromBasket($basket, [
            'user_id' => $user->getId(),
            'vat'   => $vatRate,
            'country' => $card->country,
            'stripe_id' => $charge->id
        ]);
    }

    /**
     * @param Customer $customer
     * @param Card $card
     * @return null|Card
     */
    private function getMatchingCard(Customer $customer, Card $card): ?Card
    {
        foreach ($customer->sources->data as $datum) {
            if ($datum->fingerprint === $card->fingerprint) {
                return $datum;
            }
        }
        return null;
    }

    /**
     * Génère le client à partir de l'utilisateur
     * @param User $user
     * @param $token
     * @return Customer
     */
    private function findCustomerForUser(User $user, $token): Customer
    {
        $customerId = $this->stripeUserTable->findCustomerForUser($user);
        if ($customerId) {
            $customer = $this->stripe->getCustomer($customerId);
        } else {
            $customer = $this->stripe->createCustomer([
                'email'  => $user->getEmail(),
                'source' => $token
            ]);
            $this->stripeUserTable->insert([
                'user_id' => $user->getId(),
                'customer_id' => $customer->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        return $customer;
    }
}
