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
        $this->validate($value);

        return round($value, $precision, $roundingMode);
    }

    /**
     * Split the value into n shares
     *
     * @param mixed $value
     * @param int   $shareNumber
     *
     * @return array
     */
    public function splitInto($value, $shareNumber)
    {
        return $this->split(
            $value,
            array_fill(0, $shareNumber, 1)
        );
    }

    /**
     * Split amount into given ratios and return an
     * array containing integer amount for each ratio.
     *
     * @param mixed $value
     * @param array $ratios Array of integer ratios
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function split($value, $ratios)
    {
        $this->validate($value);

        $total = $this->add(...$ratios);

        if ($total <= 0) {
            throw new \InvalidArgumentException("Sum of ratios must be greater than zero");
        }

        $unallocated = $value;
        $shares      = [];
        $remainders  = [];

        /**
         * Integer value 'share' for each ratio is calculated while the
         * 'remainder' is the float amount that was left over that value
         */
        foreach ($ratios as $ratio) {
            if ($ratio < 0) {
                throw new \InvalidArgumentException('Ratio must be zero or positive');
            }

            $fraction    = $value * $ratio / $total;
            $share       = floor($fraction);

            $shares[]     = $share;
            $remainders[] = $fraction - $share;

            $unallocated -= $share;
        }

        /**
         * Unallocated amount is distributed one by one, starting with the
         * share with biggest remainder first, until everythin is allocated
         */
        while ($unallocated > 0) {
            $index              = array_keys($remainders, max($remainders))[0];
            $remainders[$index] = null;
            $shares[$index] += 1;
            $unallocated--;
        }

        return $shares;
    }

    /**
     * Chech if provided values type is a valid 'number'
     *
     * @param mixed $values
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validate($values)
    {
        foreach ((array)$values as $value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException("Cannot perform calculation using non numeric values");
            }
        }
    }
}
