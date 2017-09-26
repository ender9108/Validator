<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class BooleanValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Value ":value" is not valid boolean';

    /**
     * @var array
     */
    private $templateVar = [];

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
     * @param string      $fieldName
     * @param mixed       $value
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName
        ];
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
        $this->error = str_replace(
            $this->templateVar,
            [$this->value, $this->fieldName],
            $this->template
        );
    }
}
