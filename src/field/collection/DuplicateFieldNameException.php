<?php

namespace sndsgd\field\collection;

use \Exception;

/**
 * An exception that is thrown when attempting to add a field to a collection 
 * in which the field name is already defined
 */
class DuplicateFieldNameException extends Exception
{
    /**
     * {@inheritdoc}
     * @param string $name The name of the field that is already defined
     */
    public function __construct($name, $code = 0, Exception $previous = null) {
        $msg = "failed to define field; the field '$name' is already defined";
        parent::__construct($msg, $code, $previous);
    }
}
