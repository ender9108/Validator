<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class UrlValidator implements ValidatorInterface
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
     * @var array
     */
    private $flags;

    /**
     * SlugValidator constructor.
     *
     * @param string   $fieldName
     * @param mixed    $value
     * @param int|null $flags
     */
    public function __construct(string $fieldName, $value, ?int $flags = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->flags = $flags;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (false === filter_var(
            $this->value,
            FILTER_VALIDATE_URL,
            (null === $this->flags ? [] : ['flags' => $this->flags])
        )) {
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
