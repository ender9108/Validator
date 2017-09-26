<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class RegexValidator implements ValidatorInterface
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
     * @var string
     */
    private $regex;

    /**
     * SlugValidator constructor.
     *
     * @param string $fieldName
     * @param mixed  $value
     * @param string $regex
     */
    public function __construct(string $fieldName, $value, string $regex)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->regex = $regex;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (false === preg_match($this->regex, $this->value)) {
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
