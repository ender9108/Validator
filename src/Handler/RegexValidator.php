<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class RegexValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Value ":value" is not valid';

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
    private $regex;

    /**
     * SlugValidator constructor.
     *
     * @param string      $fieldName
     * @param mixed       $value
     * @param string      $regex
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, string $regex, ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->regex = $regex;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName,
            ':regex'     => $regex
        ];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (!preg_match($this->regex, $this->value)) {
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
            [$this->value, $this->fieldName, $this->regex],
            $this->template
        );
    }
}
