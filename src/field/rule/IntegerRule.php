<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a value is an integer
 */
class IntegerRule extends \sndsgd\field\Rule
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'must be an integer';

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        if (
            is_bool($this->value) === false &&
            $this->value !== null &&
            ($newValue = filter_var($this->value, FILTER_VALIDATE_INT)) !== false
        ) {
            $this->value = $newValue;
            return true;
        }
        return false;
    }
}
