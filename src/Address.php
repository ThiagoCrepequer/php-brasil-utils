<?php

namespace Crepequer\PhpBrasilUtils;

use Crepequer\PhpBrasilUtils\Helpers\Temp;
use DateTime;
use Exception;

final class Address
{
    public function __construct(
        private bool $saveTemp = false,
        private DateTime $validity = new DateTime('+30 days')
    )
    {}
    /**
     * This method is responsible for getting the address from the CEP using the ViaCEP API
     * 
     * @param string $cep
     * @param bool $throwError - If true, it will throw an exception if the CEP is invalid
     * @param bool $saveTemp - If true, it will save the address in a temporary file
     * 
     * @example getAddresses("12345-678");
     * @example getAddresses("98765432");
     * 
     * @return array|bool
     * 
     * @author Thiago Crepequer
     */
    public function searchByCep(string $cep, bool|null $throwError = false): array|bool
    {
        if (!CEP::validateFormatting($cep, $throwError)) {
            return false;
        }

        if ($this->saveTemp) {
            $temp = Temp::get($cep, "ceps", $this->validity);
            if ($temp) {
                return $temp;
            }
        }

        $filtredCep = CEP::removeMask($cep);
        $url = "https://viacep.com.br/ws/{$filtredCep}/json/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response, true);

        if (empty($json) || (isset($json["erro"]) && $json["erro"] === true)) {
            if ($throwError) {
                throw new Exception("No address found for the CEP: {$cep}");
            }
            return false;
        }

        if ($this->saveTemp) {
            Temp::set($response, $cep, "ceps");
        }

        return $json;
    }
}