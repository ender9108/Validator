<?php
namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class LessThanValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Value :value is not less than to :compareValue';

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
     * @var mixed
     */
    private $compareValue;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param string $fieldName
     * @param mixed $value
     * @param $compareValue
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, $compareValue, ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'        => $value,
            ':compareValue' => $compareValue,
            ':fieldname'    => $fieldName
        ];
        $this->compareValue = $compareValue;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->value < $this->compareValue) {
            return true;
        }

        $this->buildError();
        return false;
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
            [$this->value, $this->fieldName, $this->compareValue],
            $this->template
        );
    }
}