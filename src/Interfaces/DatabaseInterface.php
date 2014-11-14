<?php

namespace src\Interfaces;

/**
 * Database api interface
 */
interface DatabaseInterface {

    /**
     * Parse database config method
     * 
     * @param string $file
     */
    public function parseDatabaseConfig($file);
    
    /**
     * Connect method 
     */
    public function connect();
    
    /**
     * Map method
     */
    public function mapDatabase();   
}