<?php

namespace Crepequer\PhpBrasilUtils\Tests;

use Crepequer\PhpBrasilUtils\CEP;
use PHPUnit\Framework\TestCase;

final class CEPTest extends TestCase
{
    public function testValidateFormatting(): void
    {
        $this->setName("Valid CEP formatting");

        $res = CEP::validateFormatting("12345-678");
        $this->assertTrue($res, "Valid CEP format");

        $res = CEP::validateFormatting("12345678");
        $this->assertTrue($res, "Valid CEP format");
    }

    public function testInvalidCEPFormattingWithError(): void
    {
        $this->setName("Invalid CEP formatting with error");

        $this->expectException(\InvalidArgumentException::class);
        CEP::validateFormatting("123456789", true);
    }

    public function testInvalidCEPFormattingWithoutError(): void
    {
        $this->setName("Invalid CEP formatting without error");

        $ceps = [
            "123456789",
            "023456789",
            "000000000",
            "000000-000",
            "00000-0000",
            "01099-999ab"
        ];

        foreach ($ceps as $cep) {
            $res = CEP::validateFormatting($cep);
            $this->assertFalse($res, "Invalid CEP format");
        }
    }
}