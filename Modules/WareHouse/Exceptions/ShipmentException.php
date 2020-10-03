<?php

namespace Modules\WareHouse\Exceptions;

use Exception;

class ShipmentException extends Exception
{
    private $errorsBag;

    public function __construct($errors = [], $message = '')
    {
        parent::__construct('The given data was invalid.');
        $this->errorsBag = $errors;
        $this->message = $message;
    }

    public function errors()
    {
        return $this->errorsBag;
    }
}
