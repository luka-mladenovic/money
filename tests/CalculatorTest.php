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

    /**
     * @test
     * @covers \Money\Calculator::round
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot perform calculation using non numeric values
     */
    public function it_throws_an_exception_when_rounding_an_invalid_value()
    {
        $this->calculator->round('foo');
    }

    public function splitValueDataProvider()
    {
        return [
            [99, [1, 1, 1], [33, 33, 33]],
            [100, [1, 1, 1], [34, 33, 33]],
            [100, [1], [100]],
            [101, [1, 1, 1], [34, 34, 33]],
            [101, [3, 7], [30, 71]],
            [101, [7, 3], [71, 30]],
            [5, [3, 7], [2, 3]],
            [5, [7, 3], [4, 1]],
            [5, [7, 3, 0], [4, 1, 0]],
            [2, [1, 1, 1], [1, 1, 0]],
            [1, [1, 1], [1, 0]],
            [-5, [7, 3], [-3, -2]],
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::split
     * @dataProvider splitValueDataProvider
     */
    public function it_splits_the_value($value,$ratios,$result)
    {
        $split = $this->calculator->split($value,$ratios);

        foreach ($split as $key => $value) {
            $this->assertEquals(
                $result[$key],
                $split[$key]);
        }
    }

    public function splitValueIntoDataProvider()
    {
        return [
            [99, 3, [33, 33, 33]],
            [100, 3, [34, 33, 33]],
            [100, 1, [100]]
        ];
    }

    /**
     * @test
     * @covers \Money\Calculator::splitInto
     * @dataProvider splitValueIntoDataProvider
     */
    public function it_splits_the_value_into_n_shares($value,$shareNumber,$result)
    {
        $split = $this->calculator->splitInto($value,$shareNumber);

        foreach ($split as $key => $value) {
            $this->assertEquals(
                $result[$key],
                $split[$key]);
        }
    }

    /**
     * @test
     * @covers \Money\Calculator::split
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Ratio must be zero or positive
     */
    public function it_throws_an_exception_when_spliting_into_negative_ratio()
    {
        $this->calculator->split(100,[-1,5]);
    }

    /**
     * @test
     * @covers \Money\Calculator::split
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Sum of ratios must be greater than zero
     */
    public function it_throws_an_exception_when_spliting_into_empty_ratio()
    {
        $this->calculator->split(100,[]);
    }

    public function invalidValuesDataProvider()
    {
        return [
            [[null,null],'add'],
            [['a',5],'subtract'],
            [[5,''],'multiply'],
            [[5,''],'divide'],
            [['foo'],'round'],
            [[5,['foo']],'split'],
            [['foo',[1,1]],'split'],
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
