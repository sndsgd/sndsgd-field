<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\FloatRule;
use \sndsgd\field\rule\MaxValueRule;
use \sndsgd\field\rule\MinValueRule;


/**
 * A field for storing a latitudinal value
 */
class LatitudeField extends \sndsgd\Field
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->addRules([
            new FloatRule,
            new MinValueRule(-90),
            new MaxValueRule(90)
        ]);
    }

    /**
     * {@inheritdoc}
     * @param float $value
     */
    public function setDefault($value)
    {
        if (!is_float($value) || $value < -90 || $value > 90) {
            throw new InvalidArgumentException(
                "invalid value provided for 'value'; ".
                "expecting a float that is between -90 and 90"
            );
        }
        $this->defaultValue = $value;
        return $this;
    }
}
