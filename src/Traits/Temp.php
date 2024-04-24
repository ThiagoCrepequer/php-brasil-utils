<?php

namespace Crepequer\PhpBrasilUtils\Traits;

use DateTime;
use Exception;

final class Temp
{
    /**
     * This method is responsible for saving the jsons in a temporary file
     * 
     * @param string $data - The data that will be saved
     * @param string $filename - The name of the file that will be saved
     * @param string $folder - The folder where the file will be saved
     * 
     * @example saveTempJson("{}", "file", "folder");
     * 
     * @return bool
     *
     * @author Thiago Crepequer
     */
    public static function saveTempJson(string $data, string $filename, string $folder): bool
    {
        $tempPath = sys_get_temp_dir();

        if (!is_dir($tempPath . "/" . $folder)) {
            mkdir($tempPath . "/" . $folder, 0777, true);
        }
        $file = fopen($tempPath . "/$folder" . "/$filename.json", "w");
        fwrite($file, $data);
        fclose($file);

        return true;
    }

    /**
     * This method is responsible for getting the jsons from a temporary file
     * 
     * @param string $filename - The name of the file that will be saved
     * @param string $folder - The folder where the file will be saved
     * @param DateTime $validity - The validity of the file, if it is not set, it will be 30 days
     * 
     * @example getTempJson("file", "folder");
     * 
     * @return mixed
     *
     * @author Thiago Crepequer
     */
    public static function getTempJson(string $filename, string $folder, DateTime $validity = new DateTime('+30 days')): array|bool
    {
        $tempPath = sys_get_temp_dir();
        $filePath = $tempPath . "/$folder" . "/$filename.json";
    
        if (!is_dir($tempPath . "/" . $folder) || !file_exists($filePath)) {
            return false;
        }

        $currentTime = new DateTime();
        if ($currentTime > $validity) {
            return false;
        }

        $data = file_get_contents($filePath);
        return json_decode($data, true);
    }
}
