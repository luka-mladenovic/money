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

    public function addValuesDataProvider()
    {
        return [
            [[0,0],0],
            [[-5,0],-5],
            [[5,0],5],
        ];
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

    /**
     * @test
     * @covers \Money\Calculator::add
     * @dataProvider addValuesDataProvider
     *
     * @param mixed $values
     * @param mixed $result
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
     *
     * @param mixed $values
     * @param mixed $result
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
     *
     * @param mixed $values
     * @param mixed $result
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
     * @expectedException \Exception
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
     *
     * @param mixed $values
     * @param mixed $result
     */
    public function it_divides_the_values($values, $result)
    {
        $this->assertEquals(
            $result,
            $this->calculator->divide(...$values)
        );
    }
}
