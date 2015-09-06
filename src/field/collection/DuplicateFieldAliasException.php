<?php

namespace sndsgd\field\collection;

use \Exception;


class DuplicateFieldAliasException extends Exception
{
    /**
     * {@inheritdoc}
     * @param string $name The name of the alias that is already in use
     */
    public function __construct($name, $code = 0, Exception $previous = null) {
        $msg = "failed to define field; the alias '$name' is already in use";
        parent::__construct($msg, $code, $previous);
    }
}
