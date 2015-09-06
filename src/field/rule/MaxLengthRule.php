<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a value doesn't contain too many characters
 */
class MaxLengthRule extends \sndsgd\field\Rule
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'must be no more than {{len}} {{what}}';

    /**
     * The maximum number of characters required
     *
     * @var integer
     */
    protected $max = 1;

    /**
     * @param string $value The regex to use in validation
     */
    public function __construct($value = null)
    {
        if ($value === null || !is_int($value) || $value < 1) {
            throw new InvalidArgumentException(
                "invalid value provided for 'value'; ".
                "expecting a integer that is greater than 0"
            );
        }
        $this->max = $value;
    }

    /**
     * Create a message that specifies the number of characters required
     *
     * @return string
     */
    protected function getMessage()
    {
        return Str::replace($this->message, [
            '{{len}}' => $this->max,
            '{{what}}' => ($this->max === 1) ? 'character' : 'characters'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        return (strlen($this->value) <= $this->max);
    }
}
