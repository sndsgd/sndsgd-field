<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\Dir;
use \sndsgd\Path;


/**
 * Ensure a value is an empty directory
 *
 * Note: if you use this rule, you should use sndsgd\field\rule\PathTestRule 
 * first to ensure the value is actually a directory
 */
class WritableDirectoryRule extends \sndsgd\field\Rule
{
    /**
     * {@inheritdoc}
     */
    protected $message = "'{{path}}' must be an empty directory";

    /**
     * Whether or not the provided path will be a directory
     *
     * @var boolean
     */
    protected $mustBeEmpty = false;

    /**
     * @param boolean $mustBeEmpty
     */
    public function __construct($mustBeEmpty = false)
    {
        if (!is_bool($mustBeEmpty)) {
            throw new InvalidArgumentException(
                "invalid value provided for 'mustBeEmpty'; ".
                "expecting a boolean"
            );
        }
        $this->mustBeEmpty = $mustBeEmpty;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return str_replace('{{path}}', $this->value, $this->message);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        if (($test = Dir::isWritable($this->value)) !== true) {
            $this->message = $test;
            return false;
        }
        else if (
            file_exists($this->value) && 
            $this->mustBeEmpty === true && 
            count(scandir($this->value)) !== 2
        ) {
            $this->message = "'{$this->value}' must be an empty directory";
            return false;
        }
        return true;
    }
}
