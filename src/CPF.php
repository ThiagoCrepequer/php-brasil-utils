<?php

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
    public function validateFormatting(string $cpf, bool $error = false): bool
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
    
}