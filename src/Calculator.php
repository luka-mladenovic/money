<?php
namespace Money;

class Calculator
{
    /**
     * Calculate sum of values
     *
     * @param array $values
     *
     * @return mixed
     */
    public function add(...$values)
    {
        $this->validate($values);
        $result = array_shift($values);

        foreach ($values as $value) {
            $result += $value;
        }

        return $result;
    }

    /**
     * Calculate difference of values
     *
     * @param array $values
     *
     * @return mixed
     */
    public function subtract(...$values)
    {
        $this->validate($values);
        $result = array_shift($values);

        foreach ($values as $value) {
            $result -= $value;
        }

        return $result;
    }

    /**
     * Multiply provided values
     *
     * @param array $values
     *
     * @return mixed
     */
    public function multiply(...$values)
    {
        $this->validate($values);
        $result = array_shift($values);

        foreach ($values as $value) {
            $result *= $value;
        }

        return $result;
    }

    /**
     * Divide provided values
     *
     * @param array $values
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function divide(...$values)
    {
        $this->validate($values);
        $result = array_shift($values);

        foreach ($values as $value) {
            if ($value == 0) {
                throw new \InvalidArgumentException("Cannot divide by zero");
            }
            $result /= $value;
        }

        return $result;
    }

    /**
     * Round the value
     *
     * @param mixed $value
     * @param int   $roundingMode
     * @param int   $precision
     *
     * @return mixed
     */
    public function round($value, $roundingMode = PHP_ROUND_HALF_UP, $precision = 0)
    {
        return round($value, $precision, $roundingMode);
    }

    /**
     * Chech the provided values type
     *
     * @param array $values
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validate($values)
    {
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException("Cannot perform calculation using non numeric values");
            }
        }
    }
}
