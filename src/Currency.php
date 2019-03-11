<?php
namespace Money;

class Currency
{
    /**
     * Currenct code
     *
     * @var string
     */
    protected $code;

    /**
     * Currency data used for formatting
     *
     * @var array
     */
    protected $data;

    /**
     * Create new currency instance
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Return currency code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return currency data
     *
     * @param null|Money\CurrencyBag $currencyBag
     *
     * @return array
     */
    public function getData(CurrencyBag $currencyBag = null)
    {
        /**
         * The data is stored in the configuration file
         * and is not loaded until first requested.
         */
        if ($this->data) {
            return $this->data;
        }

        if (is_null($currencyBag)) {
            $currencyBag = new CurrencyBag;
        }

        return $this->data = $currencyBag->load(
            $this->getCode()
        );
    }

    /**
     * Check if currency code matches the given currency
     *
     * @param self $currency
     *
     * @return bool
     */
    public function equalsTo(self $currency)
    {
        return $this->getCode() == $currency->getCode();
    }
}
