<?php

namespace Crepequer\PhpBrasilUtils\Tests;

use Crepequer\PhpBrasilUtils\Address;
use PHPUnit\Framework\TestCase;

final class AddressTest extends TestCase
{

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
            $returnedAdres = (new Address(saveTemp: true))->searchByCep($cep);
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
        $res = (new Address())->searchByCep("12345678");
        $this->assertFalse($res);

        $this->setName("Get address with invalid CEP and not formatted correctly");
        $res = (new Address())->searchByCep("123456789");
        $this->assertFalse($res);
    }

    public function testGetAdressWithInvalidCEPAndErrorTrue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Address())->searchByCep("123456789", true);
    }
}