<?php

namespace App\Shop;

use App\Auth\User;
use App\Shop\Entity\Product;
use App\Shop\Exception\AlreadyPurchasedException;
use App\Shop\Table\PurchaseTable;
use App\Shop\Table\StripeUserTable;
use Framework\Api\Stripe;
use Staaky\VATRates\VATRates;
use Stripe\Card;
use Stripe\Customer;

class PurchaseProduct
{

    /**
     * @var PurchaseTable
     */
    private $purchaseTable;
    /**
     * @var Stripe
     */
    private $stripe;
    /**
     * @var StripeUserTable
     */
    private $stripeUserTable;

    public function __construct(
        PurchaseTable $purchaseTable,
        Stripe $stripe,
        StripeUserTable $stripeUserTable
    ) {
        $this->purchaseTable = $purchaseTable;
        $this->stripe = $stripe;
        $this->stripeUserTable = $stripeUserTable;
    }

    /**
     * Génère l'achat du produit pour l'utilisateur en utilisant Stripe
     * @param Product $product
     * @param User $user
     * @param string $token
     * @throws AlreadyPurchasedException
     */
    public function process(Product $product, User $user, string $token)
    {
        // Vérifier que l'utilisateur n'a pas déjà acheté produit
        if ($this->purchaseTable->findFor($product, $user) !== null) {
            throw new AlreadyPurchasedException();
        }
        // Calculer le prix TTC
        $card = $this->stripe->getCardFromToken($token);
        $vatRate = (new VATRates())->getStandardRate($card->country) ?: 0;
        $price = floor($product->getPrice() * ((100 + $vatRate) / 100));

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
            "description" => "Achat sur monsite.com {$product->getName()}"
        ]);

        $this->purchaseTable->insert([
            'user_id' => $user->getId(),
            'product_id' => $product->getId(),
            'price' => $product->getPrice(),
            'vat'   => $vatRate,
            'country' => $card->country,
            'created_at' => date('Y-m-d H:i:s'),
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
