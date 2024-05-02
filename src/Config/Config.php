<?php

namespace Crepequer\PhpBrasilUtils\Config;

/**
 * This trait is responsible for the configuration of the classes
 * 
 * @package Crepequer\PhpBrasilUtils\Traits
 * 
 * @author Thiago Crepequer
 */
trait Config
{
    private static string $configFilename = "config-brasil-utils.json";
    private static string $configPath = "";
    protected static bool $erro = false;
    protected static bool $saveTemp = false;
    
    public static function getConfig()
    {
        if (!empty($config)) {
            return $config;
        }

        $config = self::searchRootConfig();
        self::setProperties($config);
    }

    private static function searchRootConfig()
    {
        $currentDirectory = __DIR__;
        $configPath = '';
        $level = 0;

        while(empty($configPath)) {
            $level++;

            $vendor = $currentDirectory . "/vendor";
            $composerJson = $currentDirectory . "/composer.json";

            if (file_exists($vendor) && 
                is_dir($vendor) &&
                file_exists($composerJson) &&
                is_file($composerJson) &&
                basename($currentDirectory) != "php-brasil-utils"
            ) {
                $configPath = $currentDirectory . "/" . self::$configFilename;
                break;
            } 
            
            if ($currentDirectory === "/" || $level > 5) {
                break;
            }
            
            $currentDirectory = dirname($currentDirectory);
        }

        if (empty($configPath) || !file_exists($configPath)) {
            return false;
        }

        self::$configPath = $configPath;
        
        $config = file_get_contents($configPath);
        return json_decode($config, true);
    }

    private static function setProperties($config)
    {
        if (empty($config) || !is_array($config)) {
            return;
        }

        if (isset($config['erro'])) {
            self::$erro = $config['erro'];
        }

        if (isset($config['saveTemp'])) {
            self::$saveTemp = $config['saveTemp'];
        }
    }
}