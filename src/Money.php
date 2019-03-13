<?php
namespace Money;

use Money\Exceptions\InvalidAmountException;
use Money\Exceptions\InvalidCurrencyException;

class Money
{
    /**
     * Amount
     *
     * @var int
     */
    protected $amount;

    /**
     * Currency
     *
     * @var Money\Currency
     */
    protected $currency;

    /**
     * Calculator
     *
     * @var Money\Calculator
     */
    protected $calculator;

    /**
     * Create new money
     *
     * @param mixed          $amount
     * @param Money\Currency $currency
     */
    public function __construct($amount, Currency $currency)
    {
        static::validateAmount($amount);

        $this->amount   = (int)$amount;
        $this->currency = $currency;
    }

    /**
     * Create new money instance using
     * the same currency and given amount
     *
     * @param int $amount
     *
     * @return Money\Money
     */
    public function instance($amount)
    {
        return new static($amount,$this->currency);
    }

    /**
     * Create new isntance using the provided currency code
     *
     * @param string $currency
     * @param array  $args
     *
     * @return Money\Money
     */
    public static function __callStatic($currency, $args)
    {
        return new static($args[0], new Currency($currency));
    }

    /**
     * Assert that the amount is numeric
     *
     * @param mixed $amount
     *
     * @throws Money\Exceptions\InvalidAmountException
     *
     * @return void
     */
    protected static function validateAmount($amount)
    {
        if (!is_numeric($amount) || $amount != round($amount, 0)) {
            throw new InvalidAmountException;
        }
    }

    /**
     * Assert that the given currency is the same
     *
     * @param Money\Currency $currency
     *
     * @throws Money\Exceptions\InvalidCurrencyException
     *
     * @return void
     */
    protected function validateCurrency(Currency $currency)
    {
        if (!$this->getCurrency()->equalsTo($currency)) {
            throw new InvalidCurrencyException('Currencies do not match');
        }
    }

    /**
     * Return amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Return currency instance
     *
     * @return Money\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Check if given money has
     * the same currency and amount
     *
     * @param Money\Money $money
     *
     * @return bool
     */
    public function equalsTo(Money $money)
    {
        return $this->getAmount() == $money->getAmount()
                && $this->getCurrency()->equalsTo($money->getCurrency());
    }

    /**
     * Compare given money amount
     *
     * @param Money\Money $money
     *
     * @return int
     */
    public function compareTo(Money $money)
    {
        $this->validateCurrency(
            $money->getCurrency()
        );

        return $this->getAmount() <=> $money->getAmount();
    }

    /**
     * Check if given money amount is greater
     *
     * @param Money\Money $money
     *
     * @return bool
     */
    public function greaterThan(Money $money)
    {
        return $this->compareTo($money) == 1;
    }

    /**
     * Check if given money amount is greater or equal
     *
     * @param Money\Money $money
     *
     * @return bool
     */
    public function greaterThanOrEqual(Money $money)
    {
        return $this->compareTo($money) != -1;
    }

    /**
     * Check if given money amount is less
     *
     * @param Money\Money $money
     *
     * @return bool
     */
    public function lessThan(Money $money)
    {
        return $this->compareTo($money) == -1;
    }

    /**
     * Check if given money amount is less or equal
     *
     * @param Money\Money $money
     *
     * @return bool
     */
    public function lessThanOrEqual(Money $money)
    {
        return $this->compareTo($money) != 1;
    }

    /**
     * Return calculator instance
     *
     * @return Money\Calculator
     */
    public function getCalculator()
    {
        return $this->calculator ?? $this->calculator = new Calculator;
    }

    /**
     * Add money amount and return a new
     * money instance using the same currency
     *
     * @param Money\Money $money
     *
     * @return Money\Money
     */
    public function add(Money $money)
    {
        $this->validateCurrency(
            $money->getCurrency()
        );

        return $this->instance(
            $this->calculateWith(__FUNCTION__, $money->getAmount())
        );
    }

    /**
     * Subtract money amount and return a new
     * money instance using the same currency
     *
     * @param Money\Money $money
     *
     * @return Money\Money
     */
    public function subtract(Money $money)
    {
        $this->validateCurrency(
            $money->getCurrency()
        );

        return $this->instance(
            $this->calculateWith(__FUNCTION__, $money->getAmount())
        );
    }

    /**
     * Multiply money amount with given value and return
     * a new money instance using the same currency
     *
     * @param mixed $multiplier
     * @param int   $roundingMode
     *
     * @return Money\Money
     */
    public function multiply($multiplier, $roundingMode = PHP_ROUND_HALF_UP)
    {
        return $this->instance(
            $this->calculateWith(__FUNCTION__, $multiplier, $roundingMode)
        );
    }

    /**
     * Divide money amount with given value and return
     * a new money instance using the same currency
     *
     * @param mixed $divisor
     * @param int   $roundingMode
     *
     * @return Money\Money
     */
    public function divide($divisor, $roundingMode = PHP_ROUND_HALF_UP)
    {
        return $this->instance(
            $this->calculateWith(__FUNCTION__, $divisor, $roundingMode)
        );
    }

    /**
     * Perform a calculation operation with given money object
     *
     * @param string   $operation
     * @param int      $amount
     * @param int|null $roundingMode
     *
     * @return int
     */
    protected function calculateWith($operation, $amount, $roundingMode = null)
    {
        $result = $this->getCalculator()->{$operation}(
            $this->getAmount(),
            $amount
        );

        if ($roundingMode) {
            $result = $this->getCalculator()->round($result, $roundingMode);
        }

        return $result;
    }
}
