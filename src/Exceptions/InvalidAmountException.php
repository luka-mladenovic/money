<?php
namespace Money\Exceptions;

class InvalidAmountException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message ?? 'Amount must be numeric', $code, $previous);
    }
}
