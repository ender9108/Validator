<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class LengthValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $template = [
        'min'       => 'Field ":fieldname" must contain more than :min characters',
        'max'       => 'Field ":fieldname" must contain less than :max characters',
        'between'   => 'Field ":fieldname" must contain between :min and :max characters'
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
     * @param string     $fieldName
     * @param mixed      $value
     * @param int|null   $min
     * @param int|null   $max
     * @param array|null $customTemplate
     */
    public function __construct(
        string $fieldName,
        $value,
        ?int $min = null,
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
        $length = mb_strlen($this->value);

        if (null !== $this->min &&
            null !== $this->max &&
            ($length < $this->min || $length > $this->max)
        ) {
            $this->buildError('between');

            return false;
        }
        if (null !== $this->min &&
            $length < $this->min
        ) {
            $this->buildError('min');

            return false;
        }
        if (null !== $this->max &&
            $length > $this->max
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
