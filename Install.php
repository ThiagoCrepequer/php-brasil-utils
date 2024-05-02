<?php

include 'src/Config/Config.php';

use Crepequer\PhpBrasilUtils\Config\Config;

class Install 
{
    use Config;

    public function __construct()
    {
        $this->getConfig();
    }
}

new Install();