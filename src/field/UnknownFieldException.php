<?php

namespace sndsgd\field;


class UnknownFieldException extends \Exception
{
    /**
     * {@inheritdoc}
     * @param string $name The name of the alias that is already in use
     */
    public function __construct($name, $code = 0, Exception $previous = null) {
        $msg = "the field '$name' has not been defined";
        parent::__construct($msg, $code, $previous);
    }
}
