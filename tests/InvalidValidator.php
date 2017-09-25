<?php
namespace Tests\EnderLab;


class InvalidValidator
{
    private $value;
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
    }
}