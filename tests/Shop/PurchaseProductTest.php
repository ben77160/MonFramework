<?php

namespace Tests\App\Shop;

use App\Auth\User;
use App\Shop\Entity\Product;
use App\Shop\Entity\Purchase;
use App\Shop\Exception\AlreadyPurchasedException;
use App\Shop\PurchaseProduct;
use App\Shop\Table\PurchaseTable;
use App\Shop\Table\StripeUserTable;
use Framework\Api\Stripe;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Stripe\Card;
use Stripe\Charge;
use Stripe\Collection;
use Stripe\Customer;

class PurchaseProductTest extends TestCase
{

    private $purchase;
    private $purchaseTable;
    private $stripe;
    private $stripeUserTable;

    public function setUp()
    {
        $this->purchaseTable = $this->prophesize(PurchaseTable::class);
        $this->stripe = $this->prophesize(Stripe::class);
        $this->stripeUserTable = $this->prophesize(StripeUserTable::class);
        $this->purchase = new PurchaseProduct(
            $this->purchaseTable->reveal(),
            $this->stripe->reveal(),
            $this->stripeUserTable->reveal()
        );
        $this->stripe->getCardFromToken(Argument::any())->will(function ($args) {
            $card = new Card();
            $card->fingerprint = "a";
            $card->country = $args[0];
            $card->id = "tokencard";
            return $card;
        });
    }

    public function testAlreadyPurchasedProduct()
    {
        $product = $this->makeProduct();
        $user = $this->makeUser();
        $this->purchaseTable->findFor($product, $user)
            ->shouldBeCalled()
            ->willReturn($this->makePurchase());
        $this->expectException(AlreadyPurchasedException::class);
        $this->purchase->process($product, $user, 'token');
    }

    public function testPurchaseFrance()
    {
        $customerId = 'cuz_12312312';
        $token = 'FR';
        $product = $this->makeProduct();
        $card = $this->makeCard('FR');
        $user = $this->makeUser();
        $customer = $this->makeCustomer();
        $charge = $this->makeCharge();

        $this->purchaseTable->findFor($product, $user)->willReturn(null);
        $this->stripeUserTable->findCustomerForUser($user)->willReturn($customerId);
        $this->stripe->getCustomer($customerId)->willReturn($customer);
        $this->stripe->createCardForCustomer($customer, $token)
            ->shouldBeCalled()
            ->willReturn($card);
        $this->stripe->createCharge(new Argument\Token\LogicalAndToken([
            Argument::withEntry('amount', 6000),
            Argument::withEntry('source', $card->id)
        ]))->shouldBeCalled()
            ->willReturn($charge);
        $this->purchaseTable->insert([
            'user_id' => $user->getId(),
            'product_id' => $product->getId(),
            'price' => 50.00,
            'vat' => 20,
            'country' => 'FR',
            'created_at' => date('Y-m-d H:i:s'),
            'stripe_id' => $charge->id
        ])->shouldBeCalled();
        // On lance l'achat
        $this->purchase->process($product, $user, $token);
    }

    public function testPurchaseUS()
    {
        $customerId = 'cuz_12312312';
        $token = 'US';
        $product = $this->makeProduct();
        $card = $this->makeCard();
        $user = $this->makeUser();
        $customer = $this->makeCustomer();
        $charge = $this->makeCharge();

        $this->purchaseTable->findFor($product, $user)->willReturn(null);
        $this->stripeUserTable->findCustomerForUser($user)->willReturn($customerId);
        $this->stripe->getCustomer($customerId)->willReturn($customer);
        $this->stripe->createCardForCustomer($customer, $token)
            ->shouldBeCalled()
            ->willReturn($card);
        $this->stripe->createCharge(new Argument\Token\LogicalAndToken([
            Argument::withEntry('amount', 5000),
            Argument::withEntry('source', $card->id)
        ]))->shouldBeCalled()
            ->willReturn($charge);
        $this->purchaseTable->insert([
            'user_id' => $user->getId(),
            'product_id' => $product->getId(),
            'price' => 50.00,
            'vat' => 0,
            'country' => 'US',
            'created_at' => date('Y-m-d H:i:s'),
            'stripe_id' => $charge->id
        ])->shouldBeCalled();
        // On lance l'achat
        $this->purchase->process($product, $user, $token);
    }

    public function testPurchaseWithExistingCard()
    {
        $customerId = 'cuz_12312312';
        $token = 'US';
        $product = $this->makeProduct();
        $card = $this->makeCard();
        $user = $this->makeUser();
        $customer = $this->makeCustomer([$card]);
        $charge = $this->makeCharge();

        $this->purchaseTable->findFor($product, $user)->willReturn(null);
        $this->stripeUserTable->findCustomerForUser($user)->willReturn($customerId);
        $this->stripe->getCustomer($customerId)->willReturn($customer);
        $this->stripe->createCardForCustomer($customer, $token)->shouldNotBeCalled();
        $this->stripe->createCharge(new Argument\Token\LogicalAndToken([
            Argument::withEntry('amount', 5000),
            Argument::withEntry('source', $card->id)
        ]))->shouldBeCalled()
            ->willReturn($charge);
        $this->purchaseTable->insert([
            'user_id' => $user->getId(),
            'product_id' => $product->getId(),
            'price' => 50.00,
            'vat' => 0,
            'country' => 'US',
            'created_at' => date('Y-m-d H:i:s'),
            'stripe_id' => $charge->id
        ])->shouldBeCalled();
        // On lance l'achat
        $this->purchase->process($product, $user, $token);
    }

    public function testWithNonExistingCustomer()
    {
        $customerId = 'cuz_12312312';
        $token = 'US';
        $product = $this->makeProduct();
        $card = $this->stripe->reveal()->getCardFromToken($token);
        $user = $this->makeUser();
        $customer = $this->makeCustomer([$card]);
        $charge = $this->makeCharge();

        $this->purchaseTable->findFor($product, $user)->willReturn(null);
        $this->stripeUserTable->findCustomerForUser($user)->willReturn(null);
        $this->stripeUserTable->insert([
            'user_id' => $user->getId(),
            'customer_id' => $customer->id,
            'created_at' => date('Y-m-d H:i:s')
        ])->shouldBeCalled();
        $this->stripe->createCustomer([
            'email' => $user->getEmail(),
            'source' => $token
        ])->shouldBeCalled()->willReturn($customer);
        $this->stripe->createCardForCustomer($customer, $token)->shouldNotBeCalled();
        $this->stripe->createCharge(new Argument\Token\LogicalAndToken([
            Argument::withEntry('amount', 5000),
            Argument::withEntry('source', $card->id),
            Argument::withEntry('customer', $customer->id)
        ]))->shouldBeCalled()
            ->willReturn($charge);
        $this->purchaseTable->insert([
            'user_id' => $user->getId(),
            'product_id' => $product->getId(),
            'price' => 50.00,
            'vat' => 0,
            'country' => 'US',
            'created_at' => date('Y-m-d H:i:s'),
            'stripe_id' => $charge->id
        ])->shouldBeCalled();
        // On lance l'achat
        $this->purchase->process($product, $user, $token);
    }

    private function makePurchase(): Purchase
    {
        $purchase = new Purchase();
        $purchase->setId(3);
        return $purchase;
    }

    private function makeUser(): User
    {
        $user = new User();
        $user->setId(4);
        return $user;
    }

    private function makeCustomer(array $sources = []): Customer
    {
        $customer = new Customer();
        $customer->id = "cus_1233";
        $collection = new Collection();
        $collection->data = $sources;
        $customer->sources = $collection;
        return $customer;
    }

    private function makeProduct(): Product
    {
        $product = new Product();
        $product->setId(4);
        $product->setPrice(50);
        return $product;
    }

    private function makeCard(string $country = "US"): Card
    {
        $card = new Card();
        $card->id = "card_13123";
        $card->fingerprint = "a";
        $card->country = $country;
        return $card;
    }

    private function makeCharge(): Charge
    {
        $charge = new Charge();
        $charge->id = "azeaz_13123";
        return $charge;
    }
}
