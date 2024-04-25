<?php

namespace Crepequer\PhpBrasilUtils;

use InvalidArgumentException;

final class CPF
{
    /**
     * This method is responsible for validating the CPF
     * 
     * @param string $cpf
     * @param bool|null $error - If true, it will throw an exception if the CPF is invalid
     * 
     * @example validate("123.456.789-09");
     * @example validte("98765432100");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validate(string $cpf, bool $error = false): bool
    {
        if (!self::validateFormatting($cpf, $error)) {
            return false;
        }

        if (!self::validateLength($cpf, $error)
            || !self::validateNotSameDigits($cpf, $error)
            || !self::validateVerificationDigits($cpf, $error)
        ) {
            return false;
        }
        return true;
    }

    /**
     * This method is responsible for validating the CPF formatting
     * 
     * @param string $cpf
     * @param bool|null $error - If true, it will throw an exception if the CPF is invalid
     * 
     * @example validate("123.456.789-09");
     * @example validte("98765432100");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateFormatting(string $cpf, bool $error = false): bool
    {
        if (!preg_match("/^(\d{3})\.?(\d{3})\.?(\d{3})-?(\d{2})$/", $cpf)) {
            if ($error) {
                throw new InvalidArgumentException("Invalid CPF format. Please use the following formats: xxx.xxx.xxx-xx or xxxxxxxxxxx");
            }
            return false;
        }
        return true;
    }
    
    /**
     * This method is responsible for validating the CPF length
     * 
     * @param string $cpf
     * @param bool|null $error - If true, it will throw an exception if the CPF is invalid
     * 
     * @example validateLength("123.456.789-00");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateLength(string $cpf, bool $error = false): bool
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        if (strlen($cpf) != 11) {
            if ($error) {
                throw new InvalidArgumentException("Invalid CPF length. Please use only 11 digits");
            }
            return false;
        }
        return true;
    }

    /**
     * This method is responsible for validating the CPF with the same digits
     * 
     * @param string $cpf
     * @param bool|null $error - If true, it will throw an exception if the CPF is invalid
     * 
     * @example validateSameDigits("111.111.111-11");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateNotSameDigits(string $cpf, bool $error = false): bool
    {
        $cpfNumber = preg_replace("/\D+/", "", $cpf);
        $cpfArray = str_split($cpfNumber);
        $uniqueDigits = array_unique($cpfArray);

        if (count($uniqueDigits) > 1) {
            return true;
        } 

        if ($error) {
            throw new InvalidArgumentException("Invalid CPF. All digits are the same");
        }
        return false;
    }

    /**
     * This method is responsible for validating the CPF verification digits
     * 
     * @param string $cpf
     * @param bool|null $error - If true, it will throw an exception if the CPF is invalid
     * 
     * @example validateVerificationDigits("123.456.789-09");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateVerificationDigits(string $cpf, bool $error = false): bool
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $firstNineDigits = substr($cpf, 0, 9);
        $firstVerificationDigit = substr($cpf, 9, 1);
        $secondVerificationDigit = substr($cpf, 10, 1);

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $firstNineDigits[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $calculatedFirstVerificationDigit = ($remainder < 2) ? 0 : (11 - $remainder);

        if ($firstVerificationDigit != $calculatedFirstVerificationDigit) {
            if ($error) {
                throw new InvalidArgumentException("Invalid CPF. The first verification digit is incorrect");
            }
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $calculatedSecondVerificationDigit = ($remainder < 2) ? 0 : (11 - $remainder);

        if ($secondVerificationDigit != $calculatedSecondVerificationDigit) {
            if ($error) {
                throw new InvalidArgumentException("Invalid CPF. The second verification digit is incorrect");
            }
            return false;
        }

        return true;
    }

    /**
     * This method is responsible for generating a random CPF
     * 
     * @param bool $mask - If true, it will return the CPF with formatting
     * 
     * @example generate();
     * 
     * @return string
     * 
     * @author Thiago Crepequer
     */
    public static function generate(bool $mask = false): string
    {
        $cpf = '';
        for ($i = 0; $i < 9; $i++) {
            $cpf .= mt_rand(0, 9);
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : (11 - $remainder);
        
        $cpf .= $digit1;
        
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : (11 - $remainder);
        
        $cpf .= $digit2;

        if ($mask) {
            $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, -2);
        }
        
        return $cpf;
    }
}