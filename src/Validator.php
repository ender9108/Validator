<?php

namespace EnderLab;

class Validator implements \Countable
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Validator constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string     $name
     * @param array|null $arguments
     *
     * @return Validator
     */
    public function __call(string $name, ?array $arguments = []): self
    {
        $className = 'EnderLab\\Handler\\' . ucfirst($name) . 'Validator';

        $this->checkValidator($className);

        $key = array_shift($arguments);

        if (false === $this->has($key)) {
            throw new \InvalidArgumentException('The first parameter must be a field name');
        }

        array_unshift($arguments, $key, $this->getField($key));

        $reflection = new \ReflectionClass($className);
        $this->validators[] = $reflection->newInstanceArgs($arguments);

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $result = true;

        foreach ($this->validators as $validator) {
            if (false === $validator->isValid()) {
                $result = false;
                $this->errors[] = $validator->getError();
            }
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->fields) ? true : false;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function getField(string $key)
    {
        return array_key_exists($key, $this->fields) ? $this->fields[$key] : null;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param ValidatorInterface|string $validator
     * @param array|null                $arguments
     *
     * @return Validator
     */
    public function setCustomValidator(string $validator, ...$arguments): self
    {
        $this->checkValidator($validator);

        $key = array_shift($arguments);

        if (false === $this->has($key)) {
            throw new \InvalidArgumentException('The first parameter must be a field name');
        }

        array_unshift($arguments, $key, $this->getField($key));

        if (is_string($validator)) {
            $reflection = new \ReflectionClass($validator);
            $this->validators[] = $reflection->newInstanceArgs($arguments);
        } else {
            $this->validators[] = $validator;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->validators);
    }

    /**
     * @param string $className
     */
    private function checkValidator(string $className): void
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Class "' . $className . '" does not exists.');
        }

        $reflection = new \ReflectionClass($className);

        if (false === $reflection->implementsInterface('EnderLab\\ValidatorInterface')) {
            throw new \InvalidArgumentException(
                'Validator must be implement "EnderLab\\ValidatorInterface" interface.'
            );
        }

        unset($reflection);
    }
}
