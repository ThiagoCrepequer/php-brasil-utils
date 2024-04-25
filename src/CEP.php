<?php

namespace Crepequer\PhpBrasilUtils;

use Crepequer\PhpBrasilUtils\Traits\Temp;
use Exception;
use InvalidArgumentException;

final class CEP {
    /**
     * This method is responsible for validating the CEP
     * 
     * @param string $cep
     * @param bool|null $error - If true, it will throw an exception if the CEP is invalid
     * 
     * @example validate("12345-678");
     * @example validte("98765432");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateFormatting(string $cep, bool $error = false): bool
    {
        if (!preg_match("/^(\d{5})-?(\d{3})$/", $cep)) {
            if ($error) {
                throw new InvalidArgumentException("Invalid CEP format. Please use the following formats: xxxxx-xxx or xxxxxxxx");
            }
            return false;
        }
        return true;
    }

    /**
     * This method is responsible for getting the address from the CEP using the ViaCEP API
     * 
     * @param string $cep
     * @param bool $error - If true, it will throw an exception if the CEP is invalid
     * @param bool $saveTemp - If true, it will save the address in a temporary file
     * 
     * @example getAddresses("12345-678");
     * @example getAddresses("98765432");
     * 
     * @return array|bool
     * 
     * @author Thiago Crepequer
     */
    public static function getAddresses(string $cep, bool|null $error = false, bool $saveTemp = false): array|bool
    {
        if (!self::validateFormatting($cep, $error)) {
            return false;
        }

        if ($saveTemp) {
            $temp = Temp::getTempJson($cep, "ceps");
            if ($temp) {
                return $temp;
            }
        }

        $filtredCep = preg_replace("/[^0-9]/", "", $cep);
        $url = "https://viacep.com.br/ws/{$filtredCep}/json/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response, true);

        if (empty($json) || (isset($json["erro"]) && $json["erro"] === true)) {
            if ($error) {
                throw new Exception("No address found for the CEP: {$cep}");
            }
            return false;
        }

        if ($saveTemp) {
            Temp::saveTempJson($response, $cep, "ceps");
        }

        return $json;
    }
}