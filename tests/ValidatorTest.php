<?php

namespace Tests\EnderLab;

use EnderLab\Validator;
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
        $this->assertEquals(1, $validator->count());
    }

    public function testCallInvalidValidator()
    {
        $validator = $this->makeValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->setCustomValidator('Tests\\EnderLab\\InvalidValidator', 'field1');
    }
}
