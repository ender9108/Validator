<?php
namespace EnderLab\Validator;

class SlugValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $errors;

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
     * @param mixed $value
     */
    public function __construct(string $fieldName, $value)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';

        if (!is_null($this->value) && !preg_match($pattern, $value)) {
            // @todo error message
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->errors;
    }
}