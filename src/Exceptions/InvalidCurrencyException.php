<?php
namespace Money\Exceptions;

class InvalidCurrencyException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message ?? 'Currency is invalid', $code, $previous);
    }
}
