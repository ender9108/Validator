<?php

namespace EnderLab;

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
