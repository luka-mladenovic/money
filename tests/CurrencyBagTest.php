<?php

namespace Money\Tests;

use Money\CurrencyBag;
use PHPUnit\Framework\TestCase;

class CurrencyBagTest extends TestCase
{
    /**
     * @test
     * @covers \Money\CurrencyBag::loadAll
     * @covers \Money\CurrencyBag::getConfigPath
     */
    public function it_loads_a_list_of_currencies_from_config_file()
    {
        CurrencyBag::loadAll();
    }

    public function currencyCodeDataProvider() {
        return [
            ['EUR'],
            ['USD'],
        ];
    }

    /**
     * @test
     * @covers \Money\CurrencyBag::load
     * @dataProvider currencyCodeDataProvider
     */
    public function it_loads_the_data_for_given_currency_code($code)
    {
        $data = CurrencyBag::load($code);

        $this->assertEquals(
            $data['code'],
            $code
        );
    }

    public function invalidCurrencyCodeDataProvider() {
        return [
            ['FOO'],
            [''],
        ];
    }

    /**
     * @test
     * @covers \Money\CurrencyBag::load
     * @dataProvider invalidCurrencyCodeDataProvider
     * @expectedException \Money\Exceptions\InvalidCurrencyException
     */
    public function it_throws_an_error_when_currency_code_is_not_found($code)
    {
        CurrencyBag::load($code);
    }
}
