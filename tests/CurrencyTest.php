<?php

namespace Money\Tests;

use Mockery;
use Money\Currency;
use Money\CurrencyBag;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @covers \Money\Currency::__construct
     */
    public function it_creates_a_new_currency_instance()
    {
        new Currency('EUR');
    }

    /**
     * @test
     * @covers \Money\Currency::getCode
     */
    public function it_returns_currency_code()
    {
        $currency = new Currency('EUR');

        $this->assertEquals(
            'EUR',
            $currency->getCode()
        );
    }

    /**
     * @test
     * @covers \Money\Currency::getData
     */
    public function it_returns_currency_data()
    {
        $currency = new Currency('EUR');

        $data = $currency->getData();

        $this->assertEquals(
            $data['code'],
            $currency->getCode()
        );
    }

    /**
     * @test
     * @covers \Money\Currency::getData
     */
    public function it_returns_currency_data_using_the_provided_currency_bag()
    {
        $bag = Mockery::mock(CurrencyBag::class);
        $bag->shouldReceive('load')->once()->andReturn(['foo']);

        $currency = new Currency('EUR');

        $this->assertEquals(
            ['foo'],
            $currency->getData($bag)
        );
    }

    public function compareCurrenyDataProvider() {
        return [
            ['EUR', 'EUR', true],
            ['USD', 'EUR', false],
        ];
    }

    /**
     * @test
     * @covers \Money\Currency::equalsTo
     * @dataProvider compareCurrenyDataProvider
     */
    public function it_compares_two_currency_objects($currencyOne,$currencyTwo,$equals)
    {
        $currencyOne = new Currency($currencyOne);
        $currencyTwo = new Currency($currencyTwo);

        $this->assertEquals(
            $equals,
            $currencyOne->equalsTo($currencyTwo)
        );
    }
}
