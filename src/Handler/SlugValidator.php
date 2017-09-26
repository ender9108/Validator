<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class SlugValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Slug ":value" is not valid';

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
     * SlugValidator constructor.
     *
     * @param string $fieldName
     * @param mixed  $value
     */
    public function __construct(string $fieldName, $value)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
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
        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';

        if (null !== $this->value && !preg_match($pattern, $this->value)) {
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
            [$this->value, $this->fieldName],
            $this->template
        );
    }
}
