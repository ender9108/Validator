<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class LengthValidator implements ValidatorInterface
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
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * SlugValidator constructor.
     *
     * @param string   $fieldName
     * @param mixed    $value
     * @param int      $min
     * @param int|null $max
     */
    public function __construct(string $fieldName, $value, int $min, ?int $max = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $length = mb_strlen($this->value);

        if (null !== $this->min &&
            null !== $this->max &&
            ($length < $this->min || $length > $this->max)
        ) {
            $this->buildError();

            return false;
        }

        if (null !== $this->min && $length < $this->min) {
            $this->buildError();

            return false;
        }

        if (null !== $this->max && $length > $this->max) {
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
