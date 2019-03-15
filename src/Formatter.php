<?php
namespace Money;

class Formatter
{
    /**
     * Format the amount
     *
     * @param int    $amount
     * @param int    $decimals
     * @param string $currency
     * @param string $template
     * @param string $decimalPoint
     * @param string $thousandPoint
     *
     * @return string
     */
    public function format($amount, $decimals, $currency, $template = '%1', $decimalPoint = ',', $thousandPoint = '.')
    {
        $formattedAmount = number_format(
            abs($amount) / pow(10, $decimals),
            $decimals, $decimalPoint, $thousandPoint);

        $template = str_replace('1', $formattedAmount, $template);
        $template = str_replace('%', $currency, $template);

        if ($amount < 0) {
            $template = '-' . $template;
        }

        return $template;
    }
}
