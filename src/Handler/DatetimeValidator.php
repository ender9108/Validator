<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class DatetimeValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Datetime ":value" is not valid (format: :format)';

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
     * @var string
     */
    private $format;

    /**
     * SlugValidator constructor.
     *
     * @param string      $fieldName
     * @param mixed       $value
     * @param string      $format
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, string $format = 'Y-m-d H:i:s', ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->format = $format;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName,
            ':format'    => $format
        ];
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
        $this->error = str_replace(
            $this->templateVar,
            [$this->value, $this->fieldName, $this->format],
            $this->template
        );
    }
}
