<?php

namespace Imhotep\Tests\Validation\Rules;

use Imhotep\Http\UploadedFile;
use Imhotep\Validation\Factory;
use PHPUnit\Framework\TestCase;

class MaxTest extends TestCase
{
    protected Factory $validator;

    protected function setUp(): void
    {
        $this->validator = new Factory();
    }

    public function testStringValue(): void
    {
        $validation = $this->validator->make(['foo' => 'hello'], ['foo' => 'required|string|max:10']);
        $this->assertTrue($validation->passes());

        $validation = $this->validator->make(['foo' => 'hello'], ['foo' => 'required|string|max:2']);
        $this->assertFalse($validation->passes());
        $this->assertSame(['max'], $validation->errors()->all());
    }

    public function testIntegerValue(): void
    {
        $validation = $this->validator->make(['foo' => 10], ['foo' => 'required|int|max:15']);
        $this->assertTrue($validation->passes());

        $validation = $this->validator->make(['foo' => 10], ['foo' => 'required|int|max:7']);
        $this->assertFalse($validation->passes());
        $this->assertSame(['max'], $validation->errors()->all());
    }

    public function testFloatValue(): void
    {
        $validation = $this->validator->make(['foo' => 4.1], ['foo' => 'required|float|max:4.1']);
        $this->assertTrue($validation->passes());

        $validation = $this->validator->make(['foo' => 4.1], ['foo' => 'required|float|max:4.0']);
        $this->assertFalse($validation->passes());
        $this->assertSame(['max'], $validation->errors()->all());
    }

    public function testArrayValue(): void
    {
        $validation = $this->validator->make(
            ['foo' => [1,2,3,4]],
            ['foo' => 'required|array|max:4']
        );
        $this->assertTrue($validation->passes());

        $validation = $this->validator->make(
            ['foo' => [1,2,3,4]],
            ['foo' => 'required|array|max:2']
        );
        $this->assertFalse($validation->passes());
        $this->assertSame(['max'], $validation->errors()->all());
    }

    public function testFileValue(): void
    {
        $file = UploadedFile::createFrom([
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'text/plain',
            'size' => 2048, // 1kb
            'tmp_name' => __FILE__,
            'error' => 0
        ], true);

        $validation = $this->validator->make(
            ['foo' => $file], ['foo' => 'required|file|max:2kb']
        );
        $this->assertTrue($validation->passes());

        $validation = $this->validator->make(
            ['foo' => $file], ['foo' => 'required|file|max:1kb']
        );
        $this->assertFalse($validation->passes());
        $this->assertSame(['max'], $validation->errors()->all());
    }
}