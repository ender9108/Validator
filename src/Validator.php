<?php

namespace EnderLab;

use Tests\EnderLab\ValidValidator;

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
                $this->setError($validator->getError());
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
    public function addCustomValidator($validator, ...$arguments): self
    {
        if (is_object($validator) && $validator instanceof ValidValidator) {
            $this->validators[] = $validator;
        } else {
            $this->checkValidator($validator);

            $key = array_shift($arguments);

            if (false === $this->has($key)) {
                throw new \InvalidArgumentException('The first parameter must be a field name');
            }

            array_unshift($arguments, $key, $this->getField($key));

            $reflection = new \ReflectionClass($validator);
            $this->validators[] = $reflection->newInstanceArgs($arguments);
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
     * @param string|array $errors
     */
    private function setError($errors): void
    {
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $this->errors[] = $error;
            }
        } else {
            $this->errors[] = $errors;
        }
    }

    /**
     * @param string $validator
     *
     * @return bool
     */
    private function checkValidator(string $validator): bool
    {
        if (!class_exists($validator)) {
            throw new \InvalidArgumentException('Class "' . $validator . '" does not exists.');
        }

        $reflection = new \ReflectionClass($validator);

        if (false === $reflection->implementsInterface('EnderLab\\ValidatorInterface')) {
            throw new \InvalidArgumentException(
                'Validator must be implement "EnderLab\\ValidatorInterface" interface.'
            );
        }

        unset($reflection);

        return true;
    }
}
