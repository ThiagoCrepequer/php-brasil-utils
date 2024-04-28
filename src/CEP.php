<?php

namespace Crepequer\PhpBrasilUtils;

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
     * This method is responsible for add a mask to CEP
     * 
     * @param string $cep
     * 
     * @example addMask("12345678");
     * 
     * @return string
     * 
     * @author Thiago Crepequer
     */
    public static function addMask(string $cep): string
    {
        return substr($cep, 0, 5) . "-" . substr($cep, 5, 3);
    }

    /**
     * This method is responsible for remove the mask from CEP
     * 
     * @param string|int $cep
     * 
     * @example removeMask("12345-678");
     * 
     * @return int
     * 
     * @author Thiago Crepequer
     */
    public static function toInt(string|int $cep): int
    {
        return (int) preg_replace("/[^0-9]/", "", $cep);
    }

    /**
     * This method generate a CEP with random numbers (it's not necessarily a real CEP)
     * 
     * @param bool $mask - If true, it will return the CEP with the mask (xxxxx-xxx)
     *
     * @return string
     * 
     * @example generate();
     * 
     * @author Thiago Crepequer
     */
    public static function generate(bool $mask): string
    {
        $cep = "";
        for ($i = 0; $i < 8; $i++) {
            $cep .= rand(0, 9);
        }

        if ($mask) {
            return self::addMask($cep);
        }

        return $cep;
    }
}