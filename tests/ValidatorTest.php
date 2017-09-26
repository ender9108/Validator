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

    public function testAddCustomValidValidatorWithValidatorInstance()
    {
        $validator = $this->makeValidator();
        $customValidator = new ValidValidator('field1', 'hello');
        $validator->setCustomValidator($customValidator);
        $this->assertSame(1, $validator->count());
    }

    public function testAddCustomValidValidatorWithFieldError()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->setCustomValidator('Tests\\EnderLab\\ValidValidator', 'test');
    }

    public function testValidatorValid()
    {
        $validator = $this->makeValidator(['field1' => 'slug-slug']);
        $validator->slug('field1');
        $this->assertSame(true, $validator->isValid());
    }

    public function testValidatorInvalid()
    {
        $validator = $this->makeValidator([
            'field1' => 'slug-slug',
            'field2' => 'test.com'
        ]);
        $validator->slug('field1');
        $validator->email('field2');
        $this->assertSame(false, $validator->isValid());
    }

    public function testDatetimeValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => '2017-09-26']);
        $validator->datetime('field1', 'Y-m-d');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'pouette']);
        $validator->datetime('field1', 'Y-m-d');
        $this->assertSame(false, $validator->isValid());
    }

    public function testEmailValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'alexandreberthelot9108@gmail.com']);
        $validator->email('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'alexandreberthelot9108@gmail']);
        $validator->email('field1');
        $this->assertSame(false, $validator->isValid());
    }

    public function testIntValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 1]);
        $validator->int('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 5]);
        $validator->int('field1', 1, 10);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'test']);
        $validator->int('field1');
        $this->assertSame(false, $validator->isValid());
    }

    /*public function testIpValidator()
    {
        $validator = $this->makeValidator(['field1' => '']);
    }*/

    public function testLengthValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 5);
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 1, 6);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 10);
        $this->assertSame(false, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', null, 2);
        $this->assertSame(false, $validator->isValid());
    }

    /*public function testNotEmptyValidator()
    {
        $validator = $this->makeValidator(['field1' => '']);
    }*/

    public function testSlugValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'slug-slug']);
        $validator->slug('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'slug-slug_Slug']);
        $validator->slug('field1');
        $this->assertSame(false, $validator->isValid());
    }

    /*public function testUrlValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'www.test.com']);
        $validator->url('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'http://www.test']);
        $validator->url('field1');
        $this->assertSame(false, $validator->isValid());
    }*/
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
