<?php

namespace schemas;

use Cassandra\Date;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('exchange')]
class Exchange
{
    #[Id]
    #[Column(name: 'hash_key', type: 'string', length: 64, nullable: false)]
    private string $hashKey;

    #[Column(name: 'currency_symbol', type: 'string', nullable: false)]
    private string $currencySymbol;

    #[Column(name: 'currency_date', type: 'date', nullable: false)]
    private \DateTime $currencyDate;

    #[Column(name: 'currency_rate', type: 'float', nullable: false)]
    private float $currencyRate;

    public function __construct(string $currencySymbol, \DateTime $currencyDate, float $currencyRate)
    {
        $this->currencySymbol = $currencySymbol;
        $this->currencyDate = $currencyDate;
        $this->currencyRate = $currencyRate;

        // Generate hash key based on currencySymbol and currencyDate
        $this->hashKey = hash('sha256', $currencySymbol . $currencyDate->format('Y-m-d'));
    }

    public function getCurrencyRate(): float
    {
        return $this->currencyRate;
    }

    public function setCurrencyRate(float $currencyRate): Exchange
    {
        $this->currencyRate = $currencyRate;
        return $this;
    }

    public function getCurrencyDate(): \DateTime
    {
        return $this->currencyDate;
    }

    public function setCurrencyDate(\DateTime $currencyDate): Exchange
    {
        $this->currencyDate = $currencyDate;
        return $this;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }

    public function setCurrencySymbol(string $currencySymbol): Exchange
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    public function getHashKey(): string
    {
        return $this->hashKey;
    }


}
