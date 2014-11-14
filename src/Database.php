<?php

namespace src;

use src\Abstracts\DatabaseAbstract;

class Database extends DatabaseAbstract
{
    /**
     * Constructor
     * 
     * @param string $file Database configuration files
     */
    public function __construct($file)
    {
       $this->parseDatabaseConfig($file); 
    }    
}