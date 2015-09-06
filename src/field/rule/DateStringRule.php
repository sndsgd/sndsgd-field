<?php

namespace sndsgd\field\rule;

use \DateTime;
use \DateTimeZone;
use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value is a string that can be converted to a DateTime object
 */
class DateStringRule extends \sndsgd\field\Rule
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'failed to decipher date';

    /**
     * A format string to use with with DateTime::createFromFormat
     * 
     * @see http://php.net/manual/en/datetime.createfromformat.php 
     * @var string|null
     */
    protected $format = null;

    /**
     * A timezone to use with the date
     * 
     * @var DateTimeZone
     */
    protected $timezone = null;

    /**
     * @param integer $value The max value for comparison
     */
    public function __construct($value = null)
    {
        if ($value !== null && is_string($value) === false) {
            throw new InvalidArgumentException(
                "invalid value provided for 'value'; ".
                "expecting a date format as string"
            );
        }
        $this->format = $value;
        $this->timezone = new DateTimeZone(date_default_timezone_get());
    }


    public function setTimezone(DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        if (is_string($this->value) && trim($this->value) !== '') {
            if ($this->format !== null) {
                $dt = DateTime::createFromFormat(
                    $this->format, 
                    $this->value, 
                    $this->timezone
                );
                if ($dt !== false) {
                    $this->value = $dt;
                    return true;
                }
            }
            else if (($timestamp = strtotime($this->value)) !== false) {
                $this->value = (new DateTime())->setTimestamp($timestamp);
                return true;
            }
        }
        return false;
    }
}
