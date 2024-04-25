<?php

use Crepequer\PhpBrasilUtils\CPF;
use PHPUnit\Framework\TestCase;

final class CPFTest extends TestCase
{
    public function testCpfValidate()
    {
        $this->setName("CPF validate");

        $validate = CPF::validate("123.456.789-09");
        $this->assertTrue($validate);

        $validate = CPF::validate("98765432100");
        $this->assertTrue($validate);
    }

    public function testFormattingValidate()
    {
        $this->setName("CPF formatting validate");

        $validate = CPF::validateFormatting("123.456.789-09");
        $this->assertTrue($validate);

        $validate = CPF::validateFormatting("98765432100");
        $this->assertTrue($validate);
    }

    public function testFormattingValidateError()
    {
        $this->setName("CPF formatting validate error");

        $validate = CPF::validateFormatting("123.456.789-0");
        $this->assertFalse($validate);

        $validate = CPF::validateFormatting("987654321000");
        $this->assertFalse($validate);

        $this->expectException(InvalidArgumentException::class);
        CPF::validateFormatting("123.456.789-0", true);
    }

    public function testLengthValidate()
    {
        $this->setName("CPF length validate");

        $validate = CPF::validateLength("123.456.789-09");
        $this->assertTrue($validate);

        $validate = CPF::validateLength("98765432100");
        $this->assertTrue($validate);
    }

    public function testLengthValidateError()
    {
        $this->setName("CPF length validate error");

        $validate = CPF::validateLength("123.456.789-0");
        $this->assertFalse($validate);

        $validate = CPF::validateLength("987654321000");
        $this->assertFalse($validate);

        $this->expectException(InvalidArgumentException::class);
        CPF::validateLength("123.456.789-0", true);
    }

    public function testSameDigitsValidate()
    {
        $this->setName("CPF same digits validate");

        $validate = CPF::validateNotSameDigits("111.111.111-11");
        $this->assertFalse($validate);

        $validate = CPF::validateNotSameDigits("11111111111");
        $this->assertFalse($validate);

        $validate = CPF::validateNotSameDigits("98765432100");
        $this->assertTrue($validate);
    }

    public function testCpfGenerate()
    {
        $this->setName("CPF generate");

        $cpf = CPF::generate();
        $this->assertIsString($cpf);

        $validate = CPF::validate($cpf);
        $this->assertTrue($validate);
    }

    public function testCpfGenerateWithMask()
    {
        $this->setName("Formatted CPF generate");

        $cpf = CPF::generate(true);
        $this->assertIsString($cpf);
        $this->assertMatchesRegularExpression("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf);

        $validate = CPF::validate($cpf);
        $this->assertTrue($validate);
    }
}