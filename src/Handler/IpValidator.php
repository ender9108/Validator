<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class IpValidator implements ValidatorInterface
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
     * @var bool
     */
    private $isIpv6;

    /**
     * SlugValidator constructor.
     *
     * @param string $fieldName
     * @param mixed  $value
     * @param bool   $isIpv6
     */
    public function __construct(string $fieldName, $value, $isIpv6 = false)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->isIpv6 = $isIpv6;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (false === filter_var(
            $this->value,
            FILTER_VALIDATE_IP,
            ($this->isIpv6 ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4)
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
