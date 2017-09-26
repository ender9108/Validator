<?php

namespace Tests\EnderLab;

use EnderLab\Validator;
use EnderLab\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private function makeValidator(array $fields = ['field1' => 'hello'])
    {
        return new Validator($fields);
    }

    public function testInstanceValidator()
    {
        $validator = $this->makeValidator();
        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testCallUndefinedValidator()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->test();
    }

    public function testCallUndefinedKey()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->slug('test');
    }

    public function testCallGoodKey()
    {
        $validator = $this->makeValidator();
        $validator->slug('field1');
        $this->assertSame(1, $validator->count());
        $this->assertSame(1, count($validator->getFields()));
        $this->assertSame(0, count($validator->getErrors()));
    }

    public function testAddCustomInvalidValidator()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->setCustomValidator('Tests\\EnderLab\\InvalidValidator', 'field1');
    }

    public function testAddCustomValidValidator()
    {
        $validator = $this->makeValidator();
        $validator->setCustomValidator('Tests\\EnderLab\\ValidValidator', 'field1');
        $this->assertSame(1, $validator->count());
    }
}

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

class ValidValidator implements ValidatorInterface
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

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return 'error message';
    }
}
