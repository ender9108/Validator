<?php
namespace EnderLab\Validator;

class Validator
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
     * @param string $name
     * @param array|null $arguments
     * @return Validator
     */
    public function __call(string $name, ?array $arguments = []): self
    {
        $className = ucfirst($name).'Validator';

        $this->checkValidator($className);

        $key = array_shift($arguments);

        if (false === $this->has($key)) {
            throw new \InvalidArgumentException('The first parameter must be a field name');
        }

        array_unshift($arguments, $key, $this->getField($key));

        $this->validators[] = call_user_func_array(array($className, '__construct'), $arguments);

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
     * @return bool
     */
    public function has(string $key): bool
    {
        return (array_key_exists($key, $this->fields) ? true : false);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function getField(string $key)
    {
        return (array_key_exists($key, $this->fields) ? $this->fields[$key] : false);
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
     * @param array|null $arguments
     * @return Validator
     */
    public function setCustomValidator(string $validator, array $arguments): self
    {
        $this->checkValidator($validator);

        $key = array_shift($arguments);

        if (false === $this->has($key)) {
            throw new \InvalidArgumentException('The first parameter must be a field name');
        }

        array_unshift($arguments, $key, $this->getField($key));

        if (is_string($name)) {
            $this->validators[] = call_user_func_array(array($validator, '__construct'), $arguments);
        } else {
            $this->validators[] = $validator;
        }

        return $this;
    }

    /**
     * @param string $className
     */
    private function checkValidator(string $className)
    {
        if (class_exists($className)) {
            throw new \InvalidArgumentException('Class "'.$className.'" does not exists.');
        }

        $reflection = new \ReflectionClass($className);

        if (false == $reflection->implementsInterface('EnderLab\\Validator\\ValidatorInterface')) {
            throw new \InvalidArgumentException(
                'Validator must be implement "EnderLab\\Validator\\ValidatorInterface" interface.'
            );
        }

        unset($reflection);
    }
}