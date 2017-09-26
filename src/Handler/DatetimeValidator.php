<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class DatetimeValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $error;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var
     */
    private $format;

    /**
     * SlugValidator constructor.
     *
     * @param string $fieldName
     * @param mixed  $value
     * @param string $format
     */
    public function __construct(string $fieldName, $value, string $format = 'Y-m-d H:i:s')
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->format = $format;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $date = \DateTime::createFromFormat($this->format, $this->value);
        $errors = \DateTime::getLastErrors();

        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || false === $date) {
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
