<?php
namespace Money;

use Money\Exceptions\InvalidCurrencyException;

class CurrencyBag
{
    /**
     * Load currencies list from the configuration file
     *
     * @return array
     */
    public static function loadAll()
    {
        return include(
            static::getConfigPath()
        );
    }

    /**
     * Return configuration file path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return '.' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'currencies.php';
    }

    /**
     * Return data for selected currency code
     *
     * @param string $code
     *
     * @return array
     */
    public function load($code)
    {
        $code       = strtoupper($code);
        $currencies = static::loadAll();

        if (!isset($currencies[$code])) {
            throw new InvalidCurrencyException("Currency {$code} is not supported");
        }

        return $currencies[$code];
    }
}
