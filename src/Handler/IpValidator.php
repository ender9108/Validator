<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class IpValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Ip ":value" is not valid';

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
     * @var bool
     */
    private $isIpv6;

    /**
     * @param string      $fieldName
     * @param mixed       $value
     * @param bool        $isIpv6
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, $isIpv6 = false, ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->isIpv6 = $isIpv6;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName
        ];
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
        $this->error = str_replace(
            $this->templateVar,
            [$this->value, $this->fieldName],
            $this->template
        );
    }
}
