<?php
namespace Money;

class Calculator
{
    /**
     * Calculate sum of values
     *
     * @param array $values
     *
     * @return int
     */
    public function add(...$values)
    {
        return array_sum($values);
    }

    /**
     * Calculate difference of values
     *
     * @param array $values
     *
     * @return int
     */
    public function subtract(...$values)
    {
        $result = array_shift($values);

        foreach ($values as $value) {
            $result-=$value;
        }

        return $result;
    }

    /**
     * Multiply prodived values
     *
     * @param array $values
     *
     * @return int
     */
    public function multiply(...$values)
    {
        $result = array_shift($values);

        foreach ($values as $value) {
            $result *= $value;
        }

        return $result;
    }

    /**
     * Divide prodived values
     *
     * @param array $values
     *
     * @return int
     */
    public function divide(...$values)
    {
        $result = array_shift($values);

        foreach ($values as $value) {
            if ($value == 0) {
                throw new \Exception("Cannot divide by zero");
            }
            $result /= $value;
        }

        return $result;
    }
}
