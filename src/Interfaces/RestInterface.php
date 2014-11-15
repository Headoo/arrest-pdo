<?php

namespace src\Interfaces;

/**
 * Rest api interface
 */
interface RestInterface 
{
    /**
     * Get url segments
     * 
     * @param string $baseUri
     * @return array
     */
    public function getUrlSegments($baseUri = "index.php");
    
    /**
     * Get url segments
     * 
     * @return mixed
     */
    public function getUrlparams();    
    
    /**
     * Post method 
     */
    public function post();
    
    /**
     * Get method
     */
    public function get();
    
    /**
     * Put method
     */
    public function put();
    
    /**
     * Delete method
     */
    public function delete();    
}