<?php
use PHPUnit\Framework\TestCase;
use schemas\Exchange;

final class ExchangeTest extends TestCase
{
    public function testExchange(): void
    {
        $currencySymbol = 'USD';
        $currencyDate = new \DateTime('2023-05-17');
        $currencyRate = 1.23;

        $exchange = new Exchange($currencySymbol, $currencyDate, $currencyRate);

        $this->assertEquals($currencySymbol, $exchange->getCurrencySymbol());
        $this->assertEquals($currencyDate, $exchange->getCurrencyDate());
        $this->assertEquals($currencyRate, $exchange->getCurrencyRate());
        $this->assertEquals(hash('sha256', $currencySymbol . $currencyDate->format('Y-m-d')), $exchange->getHashKey());
    }

    public function testSetCurrencyRate()
    {
        $exchange = new Exchange('USD', new \DateTime('2023-05-17 12:00:00'), 1.23);
        $newRate = 1.25;
        $exchange->setCurrencyRate($newRate);

        $this->assertEquals($newRate, $exchange->getCurrencyRate());
    }

    public function testSetCurrencyDate()
    {
        $exchange = new Exchange('USD', new \DateTime('2023-05-17'), 1.23);
        $newDate = new \DateTime('2023-06-01');
        $exchange->setCurrencyDate($newDate);

        $this->assertEquals($newDate, $exchange->getCurrencyDate());
    }

    public function testSetCurrencySymbol()
    {
        $exchange = new Exchange('USD', new \DateTime('2023-05-17'), 1.23);
        $newSymbol = 'EUR';
        $exchange->setCurrencySymbol($newSymbol);

        $this->assertEquals($newSymbol, $exchange->getCurrencySymbol());
    }

}