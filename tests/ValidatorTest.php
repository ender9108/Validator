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
        $validator->addCustomValidator('Tests\\EnderLab\\InvalidValidator', 'field1');
    }

    public function testAddCustomValidValidator()
    {
        $validator = $this->makeValidator();
        $validator->addCustomValidator('Tests\\EnderLab\\ValidValidator', 'field1');
        $this->assertSame(1, $validator->count());
    }

    public function testAddCustomValidValidatorWithValidatorInstance()
    {
        $validator = $this->makeValidator();
        $customValidator = new ValidValidator('field1', 'hello');
        $validator->addCustomValidator($customValidator);
        $this->assertSame(1, $validator->count());
    }

    public function testAddCustomValidValidatorWithFieldError()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->addCustomValidator('Tests\\EnderLab\\ValidValidator', 'test');
    }

    public function testAddCustomValidValidatorWithEmptyError()
    {
        $validator = $this->makeValidator();
        $validator->addCustomValidator('Tests\\EnderLab\\ValidValidator', 'field1');
        $validator->isValid();
        $this->assertSame('Tests\\EnderLab\\ValidValidator - Unknown error', $validator->getErrors()[0]);
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

        // invalid
        $validator = $this->makeValidator(['field1' => 2]);
        $validator->int('field1', 3, 4);
        $this->assertSame(false, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 2]);
        $validator->int('field1', 3);
        $this->assertSame(false, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 10]);
        $validator->int('field1', null, 4);
        $this->assertSame(false, $validator->isValid());
    }

    public function testIpValidator()
    {
        // valid ipv4
        $validator = $this->makeValidator(['field1' => '192.168.1.1']);
        $validator->ip('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid ipv4
        $validator = $this->makeValidator(['field1' => '192.168']);
        $validator->ip('field1');
        $this->assertSame(false, $validator->isValid());

        // valid ipv6
        $validator = $this->makeValidator(['field1' => '2001:0db8:0000:85a3:0000:0000:ac1f:8001']);
        $validator->ip('field1', true);
        $this->assertSame(true, $validator->isValid());

        // invalid ipv6
        $validator = $this->makeValidator(['field1' => '2001:0db8']);
        $validator->ip('field1', true);
        $this->assertSame(false, $validator->isValid());
    }

    public function testLengthValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 5);
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 1, 5);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', 10);
        $this->assertSame(false, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'hello']);
        $validator->length('field1', null, 2);
        $this->assertSame(false, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'h']);
        $validator->length('field1', 2, 4);
        $this->assertSame(false, $validator->isValid());
    }

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

    public function testNotEmptyValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'test']);
        $validator->notEmpty('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => '']);
        $validator->notEmpty('field1');
        $this->assertSame(false, $validator->isValid());
    }

    public function testBooleanValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => true]);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 'on']);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => false]);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 'yes']);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 0]);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 1]);
        $validator->boolean('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'test']);
        $validator->boolean('field1');
        $this->assertSame(false, $validator->isValid());
    }

    public function testUrlValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'http://www.test.com']);
        $validator->url('field1');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'www.test']);
        $validator->url('field1');
        $this->assertSame(false, $validator->isValid());
    }

    public function testRegexValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 'test-test']);
        $validator->regex('field1', '/^[a-z0-9]+(-[a-z0-9]+)*$/');
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 'test_test']);
        $validator->regex('field1', '/^[a-z0-9]+(-[a-z0-9]+)*$/');
        $this->assertSame(false, $validator->isValid());
    }

    public function testEqualToValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->equalTo('field1', 4);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->equalTo('field1', 12);
        $this->assertSame(false, $validator->isValid());
    }

    public function testGreaterThanOrEqualValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->greaterThanOrEqual('field1', 4);
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->greaterThanOrEqual('field1', 3);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->greaterThanOrEqual('field1', 12);
        $this->assertSame(false, $validator->isValid());
    }

    public function testGreaterThanValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->greaterThan('field1', 3);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->greaterThan('field1', 5);
        $this->assertSame(false, $validator->isValid());
    }

    public function testLessThanOrEqualValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->lessThanOrEqual('field1', 4);
        $this->assertSame(true, $validator->isValid());

        // valid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->lessThanOrEqual('field1', 5);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->lessThanOrEqual('field1', 2);
        $this->assertSame(false, $validator->isValid());
    }

    public function testLessThanValidator()
    {
        // valid
        $validator = $this->makeValidator(['field1' => 1]);
        $validator->lessThan('field1', 4);
        $this->assertSame(true, $validator->isValid());

        // invalid
        $validator = $this->makeValidator(['field1' => 4]);
        $validator->lessThan('field1', 4);
        $this->assertSame(false, $validator->isValid());
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
        return false;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return '';
    }
}
