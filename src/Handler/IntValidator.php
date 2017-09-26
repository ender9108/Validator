<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class IntValidator implements ValidatorInterface
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
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(string $fieldName, $value, ?int $min = null, ?int $max = null)
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
        $options = [];

        if (null !== $this->min || null !== $this->max) {
            $options['options'] = [];
        }

        if (null !== $this->min) {
            $options['options']['min_range'] = $this->min;
        }

        if (null !== $this->max) {
            $options['options']['max_range'] = $this->max;
        }

        var_dump($options);

        if (false === filter_var($this->value, FILTER_VALIDATE_INT, $options)) {
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
