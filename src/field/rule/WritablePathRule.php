<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Dir;
use \sndsgd\File;
use \sndsgd\Path;


/**
 * Ensure a path can be written to
 *
 * Before writing to the path, the appropriate prepare method
 */
class WritablePathRule extends \sndsgd\field\Rule
{
    /**
     * Whether or not the provided path will be a directory
     *
     * @var boolean
     */
    protected $isDir = false;

    /**
     * @param boolean $isDir
     */
    public function __construct($isDir = false)
    {
        if (!is_bool($isDir)) {
            throw new InvalidArgumentException(
                "invalid value provided for 'isDir'; ".
                "expecting a boolean"
            );
        }
        $this->isDir = $isDir;
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $path = Path::normalize($this->value);
        $test = ($this->isDir === true)
            ? Dir::isWritable($path)
            : File::isWritable($path);
        if ($test === true) {
            $this->value = $path;
            return true;
        }
        $this->message = $test;
        return false;
    }
}
