<?php

final class Phone
{
    /**
     * This method is responsible for validating the phone formatting
     * 
     * @param string $phone
     * @param bool|null $error - If true, it will throw an exception if the phone is invalid
     * 
     * @example validate("(12) 1234-5678");
     * @example validate("1234-5678");
     * @example validte("12345678");
     * 
     * @return bool
     * 
     * @author Thiago Crepequer
     */
    public static function validateFormatting(string $phone, bool $includeDDD, bool $throwError = false): bool
    {
        $dddPattern = $includeDDD ? "(\(\d{2}\) )" : "";
        $phonePattern = "\d{4}-\d{4}";
    
        if (!preg_match("/^$dddPattern$phonePattern$/", $phone)) {
            if ($throwError) {
                $errorMessage = $includeDDD 
                    ? "Invalid phone format. Please use the following formats: (xx) xxxx-xxxx or xxxx-xxxx." 
                    : "Invalid phone format. Please use the following formats: xxxx-xxxx or xxxxxxxx.";
                throw new InvalidArgumentException($errorMessage);
            }
            return false;
        }
    
        return true;
    }

    /**
     * This method is responsible for add a mask to phone
     * 
     * @param string $phone
     * 
     * @example addMask("12345678");
     * 
     * @return string
     * 
     * @author Thiago Crepequer
     */
    public static function addMask(string $phone): string
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $phone);
        
        $phoneNumberLength = strlen($phoneNumber);
        switch ($phoneNumberLength) {
            case 10:
                // Format for landline numbers with DDD
                return preg_replace("/^(\d{2})(\d{4,5})(\d{4})$/", "($1) $2-$3", $phoneNumber);
            case 11:
                // Format for mobile numbers with DDD
                return preg_replace("/^(\d{2})(\d{5})(\d{4})$/", "($1) $2-$3", $phoneNumber);
            case 12:
                // Format for especial numbers like 0800
                return preg_replace("/^(\d{4})(\d{3})(\d{4})$/", "$1 $2 $3", $phoneNumber);
            default:
                // Return the phone without mask if it doesn't match any pattern
                return $phoneNumber;
    }
    }

    /**
     * This method is responsible for remove the mask from phone
     * 
     * @param string $phone
     * 
     * @example removeMask("1234-5678");
     * 
     * @return string
     * 
     * @author Thiago Crepequer
     */
    public static function removeMask(string $phone): string
    {
        return preg_replace("/[\(\)\s-]+/", "", $phone);
    }

    /**
     * This method is responsible for converting the phone to int
     * (Note: it will remove 0 from the left of the number, if it exists, please use it carefully)
     * 
     * @param string $phone
     * 
     * @example toInt("1234-5678");
     * 
     * @return int
     * 
     * @author Thiago Crepequer
     */
    public static function toInt(string $phone): int
    {
        return (int) self::removeMask($phone);
    }
}