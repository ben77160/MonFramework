<?php

namespace App\Framework\Twig;

class PriceExtension extends \Twig_Extension
{

    /**
     * @var string
     */
    private $currency;

    public function __construct(string $currency = '€')
    {
        $this->currency = $currency;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('vat', [$this, 'getVat']),
            new \Twig_SimpleFunction('vat_only', [$this, 'getVatOnly'])
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price_format', [$this, 'priceFormat'])
        ];
    }

    public function priceFormat(float $price, ?string $currency = null)
    {
        return number_format($price, 2, ',', ' ') . ' ' . ($currency ?: $this->currency);
    }

    public function getVat(float $price, ?float $vat): float
    {
        return $price + $this->getVatOnly($price, $vat);
    }

    public function getVatOnly(float $price, ?float $vat): float
    {
        if ($vat === null) {
            return 0;
        }
        return $price * $vat / 100;
    }
}
