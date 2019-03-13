<?php
namespace Money\Tests;

use Money\Money;
use Money\Currency;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     * @covers \Money\Money::__construct
     * @covers \Money\Money::validateAmount
     */
    public function it_creates_new_money()
    {
        new Money(10, new Currency('eur'));
    }

    /**
     * @test
     * @covers \Money\Money::__callStatic
     */
    public function it_creates_new_money_using_the_currency_code()
    {
        $money = Money::eur(100);

        $this->assertEquals(
            'eur',
            $money->getCurrency()->getCode()
        );
    }

    /**
     * @test
     * @covers \Money\Money::instance
     */
    public function it_creates_new_money_instance_with_its_currency()
    {
        $currency = new Currency('eur');
        $oneEuro  = new Money(100, $currency);

        $twoEuro = $oneEuro->instance(200);

        $this->assertEquals(
            $currency->getCode(),
            $twoEuro->getCurrency()->getCode()
        );
    }

    public function invalidAmountDataProvider()
    {
        return [
            [null],
            [''],
            [.1],
            [0.5]
        ];
    }

    /**
     * @test
     * @covers \Money\Money::__construct
     * @covers \Money\Money::validateAmount
     * @dataProvider invalidAmountDataProvider
     * @expectedException \Money\Exceptions\InvalidAmountException
     */
    public function it_throws_an_exception_when_amount_is_invalid($amount)
    {
        new Money($amount, new Currency('eur'));
    }

    /**
     * @test
     * @covers \Money\Money::getAmount
     */
    public function it_returns_money_amount()
    {
        $money = new Money('10', new Currency('eur'));

        $this->assertEquals(
            10,
            $money->getAmount()
        );
    }

    /**
     * @test
     * @covers \Money\Money::getCurrency
     */
    public function it_returns_money_currency_object()
    {
        $currency = new Currency('eur');
        $money    = new Money(10, $currency);

        $this->assertEquals(
            $currency,
            $money->getCurrency()
        );
    }

    /**
     * @test
     * @covers \Money\Money::getCalculator
     */
    public function it_returns_money_calculator_object()
    {
        $money = new Money(10, new Currency('eur'));

        $this->assertInstanceOf(
            \Money\Calculator::class,
            $money->getCalculator()
        );
    }

    public function compareMoneyDataProvider()
    {
        return [
            [Money::eur(10),Money::eur(10),true],
            [Money::eur(10),Money::usd(10),false],
            [Money::eur(10),Money::eur(0),false],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::equalsTo
     * @dataProvider compareMoneyDataProvider
     */
    public function it_compares_two_money_objects($moneyOne, $moneyTwo, $equals)
    {
        $this->assertEquals(
            $moneyOne->equalsTo($moneyTwo),
            $equals
        );
    }

    public function compareMoneyAmountDataProvider()
    {
        return [
            [Money::eur(10),Money::eur(10),0],
            [Money::eur(10),Money::eur(100),-1],
            [Money::eur(100),Money::eur(10),1],
            [Money::eur(-10),Money::eur(10),-1],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::compareTo
     * @covers \Money\Money::validateCurrency
     * @dataProvider compareMoneyAmountDataProvider
     */
    public function it_compares_two_money_amounts($moneyOne, $moneyTwo, $result)
    {
        $this->assertEquals(
            $moneyOne->compareTo($moneyTwo),
            $result
        );
    }

    /**
     * @test
     * @covers \Money\Money::compareTo
     * @covers \Money\Money::validateCurrency
     * @expectedException \Money\Exceptions\InvalidCurrencyException
     */
    public function it_throws_an_exception_when_comparing_money_with_different_currencies()
    {
        $euro = Money::eur(100);
        $usd  = Money::usd(100);

        $euro->compareTo($usd);
    }

    /**
     * @test
     * @covers \Money\Money::greaterThan
     */
    public function it_compares_two_money_objects_to_see_if_amount_is_greater()
    {
        $money = Money::eur(100);

        $this->assertTrue(
            $money->greaterThan($money->instance(10))
        );
        $this->assertFalse(
            $money->greaterThan($money->instance(1000))
        );
    }

    /**
     * @test
     * @covers \Money\Money::greaterThanOrEqual
     */
    public function it_compares_two_money_objects_to_see_if_amount_is_greater_or_equal()
    {
        $money = Money::eur(100);

        $this->assertTrue(
            $money->greaterThanOrEqual($money->instance(10))
        );
        $this->assertTrue(
            $money->greaterThanOrEqual($money->instance(100))
        );
        $this->assertFalse(
            $money->greaterThanOrEqual($money->instance(1000))
        );
    }

    /**
     * @test
     * @covers \Money\Money::lessThan
     */
    public function it_compares_two_money_objects_to_see_if_amount_is_less()
    {
        $money = Money::eur(10);

        $this->assertTrue(
            $money->lessThan($money->instance(100))
        );
        $this->assertFalse(
            $money->lessThan($money->instance(0))
        );
    }

    /**
     * @test
     * @covers \Money\Money::lessThanOrEqual
     */
    public function it_compares_two_money_objects_to_see_if_amount_is_less_or_equal()
    {
        $money = Money::eur(10);

        $this->assertTrue(
            $money->lessThanOrEqual($money->instance(100))
        );
        $this->assertTrue(
            $money->lessThanOrEqual($money->instance(10))
        );
        $this->assertFalse(
            $money->lessThanOrEqual($money->instance(0))
        );
    }

    public function addAmountsDataProvider()
    {
        return [
            [Money::eur(10),Money::eur(10),20],
            [Money::eur(-10),Money::eur(10),0],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::add
     * @covers \Money\Money::calculateWith
     * @dataProvider addAmountsDataProvider
     */
    public function it_adds_money_amounts($moneyOne, $moneyTwo, $result)
    {
        $this->assertEquals(
            $moneyOne->add($moneyTwo)->getAmount(),
            $result
        );
    }

    /**
     * @test
     * @covers \Money\Money::add
     * @covers \Money\Money::validateCurrency
     * @expectedException \Money\Exceptions\InvalidCurrencyException
     */
    public function it_throws_an_exception_when_adding_differrent_currencies()
    {
        (Money::eur(10))
            ->add(Money::usd(10));
    }

    public function subtractAmountsDataProvider()
    {
        return [
            [Money::eur(10),Money::eur(10),0],
            [Money::eur(-10),Money::eur(10),-20],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::subtract
     * @covers \Money\Money::calculateWith
     * @dataProvider subtractAmountsDataProvider
     */
    public function it_subtracts_money_amounts($moneyOne, $moneyTwo, $result)
    {
        $this->assertEquals(
            $moneyOne->subtract($moneyTwo)->getAmount(),
            $result
        );
    }

    /**
     * @test
     * @covers \Money\Money::subtract
     * @covers \Money\Money::validateCurrency
     * @expectedException \Money\Exceptions\InvalidCurrencyException
     */
    public function it_throws_an_exception_when_subtracting_differrent_currencies()
    {
        (Money::eur(10))
            ->subtract(Money::usd(10));
    }

    public function multiplyAmountDataProvider()
    {
        return [
            [Money::eur(10),10,100],
            [Money::eur(10),10,100],
            [Money::eur(-10),10,-100],
            [Money::eur(10),0.5,5],
            [Money::eur(100),0.555,56,PHP_ROUND_HALF_UP],
            [Money::eur(100),0.555,55,PHP_ROUND_HALF_DOWN],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::multiply
     * @covers \Money\Money::calculateWith
     * @dataProvider multiplyAmountDataProvider
     */
    public function it_multiplies_money_amounts($moneyOne, $multiplier, $result, $roundingMode = null)
    {
        $this->assertEquals(
            $moneyOne->multiply($multiplier, $roundingMode)->getAmount(),
            $result
        );
    }

    public function divideAmountDataProvider()
    {
        return [
            [Money::eur(10),10,1],
            [Money::eur(-10),10,-1],
            [Money::eur(10),0.5,20],
            [Money::eur(100),24,4,PHP_ROUND_HALF_UP],
            [Money::eur(100),33,3,PHP_ROUND_HALF_DOWN],
        ];
    }

    /**
     * @test
     * @covers \Money\Money::divide
     * @covers \Money\Money::calculateWith
     * @dataProvider divideAmountDataProvider
     */
    public function it_divides_money_amounts($moneyOne, $divisor, $result, $roundingMode = null)
    {
        $this->assertEquals(
            $moneyOne->divide($divisor, $roundingMode)->getAmount(),
            $result
        );
    }
}
