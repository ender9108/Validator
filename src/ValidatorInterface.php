<?php
namespace EnderLab\Validator;

interface ValidatorInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return string
     */
    public function getError(): string;
}