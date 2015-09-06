<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value is at least a certain value
 */
class MinValueRule extends \sndsgd\field\Rule
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'must be no less than {{len}}';

    /**
     * The minimum value
     *
     * @var integer
     */
    protected $min = 1;

    /**
     * @param integer $value The min value for comparison
     */
    public function __construct($value = null)
    {
        if ($value === null || (!is_int($value) && !is_float($value))) {
            throw new InvalidArgumentException(
                "invalid value provided for 'value'; ".
                "expecting an integer or a float"
            );
        }
        $this->min = $value;
    }

    /**
     * Create a message that specifies the min value
     *
     * @return string
     */
    protected function getMessage()
    {
        return str_replace('{{len}}', $this->min, $this->message);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        return ($this->value >= $this->min);
    }
}
