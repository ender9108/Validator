<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class IntValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $template = [
        'min'       => 'The value ":value" must be greater than :min',
        'max'       => 'The value ":value" must be less than :max',
        'between'   => 'The value ":value" must be between :min and :max',
        'notInt'    => 'Value ":value" is not integer'
    ];

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
     * @param string     $fieldName
     * @param mixed      $value
     * @param int|null   $min
     * @param int|null   $max
     * @param array|null $customTemplate
     */
    public function __construct(
        string $fieldName,
        $value,
        ?int $min = 0,
        ?int $max = null,
        ?array $customTemplate = null
    ) {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->min = $min;
        $this->max = $max;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName,
            ':min'       => $min,
            ':max'       => $max
        ];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (false === is_int($this->value)) {
            $this->buildError('notInt');

            return false;
        }

        if (null !== $this->min &&
            null !== $this->max &&
            ($this->value < $this->min || $this->value > $this->max)
        ) {
            $this->buildError('between');

            return false;
        }
        if (null !== $this->min &&
            $this->value < $this->min
        ) {
            $this->buildError('min');

            return false;
        }
        if (null !== $this->max &&
            $this->value > $this->max
        ) {
            $this->buildError('max');

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

    /**
     * @param string $templateName
     */
    private function buildError(string $templateName): void
    {
        if (isset($this->template[$templateName])) {
            $this->error = str_replace(
                $this->templateVar,
                [$this->value, $this->fieldName, $this->min, $this->max],
                $this->template[$templateName]
            );
        }
    }
}
