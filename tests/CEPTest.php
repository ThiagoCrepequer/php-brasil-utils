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

    public function testGetAddresses(): void
    {
        $this->setName("Get address with valid CEP");

        $addresses = [
            '01001-000',
            '70070-600',
            '20010000',
            '60175045',
            '04094050'
        ];

        foreach ($addresses as $cep) {
            $returnedAdres = CEP::getAddresses($cep);
            $this->assertIsArray($returnedAdres);
            $this->assertArrayHasKey("cep", $returnedAdres);
            $this->assertArrayHasKey("logradouro", $returnedAdres);
            $this->assertArrayHasKey("complemento", $returnedAdres);
            $this->assertArrayHasKey("bairro", $returnedAdres);
            $this->assertArrayHasKey("localidade", $returnedAdres);
            $this->assertArrayHasKey("uf", $returnedAdres);
            $this->assertArrayHasKey("ibge", $returnedAdres);
        }
    }

    public function testGetAdressWithInvalidCEP(): void
    {
        $this->setName("Get address with invalid CEP but formatted correctly");
        $res = CEP::getAddresses("12345678");
        $this->assertFalse($res);

        $this->setName("Get address with invalid CEP and not formatted correctly");
        $res = CEP::getAddresses("123456789");
        $this->assertFalse($res);
    }

    public function testGetAdressWithInvalidCEPAndErrorTrue(): void
    {
        $this->setName("Get address with invalid CEP and error true");

        $this->expectException(\InvalidArgumentException::class);
        CEP::getAddresses("123456789", true);
    }
}