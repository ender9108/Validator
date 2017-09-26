<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class EmailValidator implements ValidatorInterface
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
        if (false === filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->buildError();

            return false;
        }

        return true;
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
