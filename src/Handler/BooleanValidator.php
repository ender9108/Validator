<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class BooleanValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $error = '';

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var bool
     */
    private $strict;

    /**
     * @var array
     */
    private $boolValue = [
        false, 'false', 'False', 'FALSE', 'no', 'No', 'n', 'N', '0', 0, 'off', 'Off', 'OFF',
        true, 'true', 'True', 'TRUE', 'yes', 'Yes', 'y', 'Y', '1', 1, 'on', 'On', 'ON'
    ];

    /**
     * SlugValidator constructor.
     *
     * @param string $fieldName
     * @param mixed  $value
     */
    public function __construct(string $fieldName, $value)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (in_array($this->value, $this->boolValue, true)) {
            return true;
        }

        $this->buildError();

        return false;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    private function buildError(): void
    {
    }
}
