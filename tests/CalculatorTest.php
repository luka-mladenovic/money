<?php
namespace Money\Tests;

use Money\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->calculator = new Calculator;
    }

    /**
     * @test
     * @covers \Money\Calculator::add
     */
    public function it_adds_two_values_together()
    {
        $this->assertEquals(
            10,
            $this->calculator->add(5, 5)
        );
    }

    public function addValuesDataProvider()
    {
        return [
            [[0,0],0],
            [[-5,0],-5],
            [[5,0],5],
            [[0.5,0.5],1],
            [['0.5',0.5],1],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::add
     * @dataProvider addValuesDataProvider
     */
    public function it_adds_the_values($values, $result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->add(...$values)
        );
    }

    public function subtractValuesDataProvider()
    {
        return [
            [[0,0],0],
            [[-5,0],-5],
            [[5,5],0],
            [[-5,-5],0],
            [[.5,5],-4.5],
            [[-5,'-5'],0],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::subtract
     */
    public function it_subtracts_two_values_together()
    {
        $this->assertEquals(
            0,
            $this->calculator->subtract(5, 5)
        );
    }

    /**
     * @test
     * @covers \Money\Calculator::subtract
     * @dataProvider subtractValuesDataProvider
     */
    public function it_subtracts_the_values($values, $result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->subtract(...$values)
        );
    }

    public function multiplyValuesDataProvider()
    {
        return [
            [[0,0],0],
            [[-5,0],0],
            [[5,5],25],
            [[-5,-5],25],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::multiply
     */
    public function it_multiplies_two_values_together()
    {
        $this->assertEquals(
            25,
            $this->calculator->multiply(5, 5)
        );
    }

    /**
     * @test
     * @covers \Money\Calculator::multiply
     * @dataProvider multiplyValuesDataProvider
     */
    public function it_multiplies_the_values($values, $result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->multiply(...$values)
        );
    }

    public function divideValuesDataProvider()
    {
        return [
            [[5,5],1],
            [[-5,-5],1],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::divide
     */
    public function it_divides_two_values_together()
    {
        $this->assertEquals(
            1,
            $this->calculator->divide(5, 5)
        );
    }

    /**
     * @test
     * @covers \Money\Calculator::divide
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot divide by zero
     */
    public function it_throws_an_exception_when_dividing_by_zero()
    {
        $this->calculator->divide(5, 0);
    }

    /**
     * @test
     * @covers \Money\Calculator::divide
     * @dataProvider divideValuesDataProvider
     */
    public function it_divides_the_values($values, $result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->divide(...$values)
        );
    }

    public function roundValueDataProvider()
    {
        return [
            [0.5,0,PHP_ROUND_HALF_UP,1],
            [0.5,0,PHP_ROUND_HALF_DOWN,0],
            [0.55,1,PHP_ROUND_HALF_UP,0.6],
            [0.555,2,PHP_ROUND_HALF_UP,0.56],
            [-0.5,0,PHP_ROUND_HALF_UP,-1],
            [-0.5,0,PHP_ROUND_HALF_DOWN,0],
            [-0.55,1,PHP_ROUND_HALF_UP,-0.6],
            [-0.555,2,PHP_ROUND_HALF_UP,-0.56],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::round
     * @dataProvider roundValueDataProvider
     */
    public function it_rounds_a_value($value,$precision,$mode,$result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->round($value,$mode,$precision)
        );
    }

    public function invalidValuesDataProvider()
    {
        return [
            [[null,null],'add'],
            [['a',5],'subtract'],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::validate
     * @dataProvider invalidValuesDataProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot perform calculation using non numeric values
     */
    public function it_throws_an_exception_when_values_are_invalid($values, $operation)
    {
        $this->calculator->{$operation}(...$values);
    }
}
