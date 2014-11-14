<?php

namespace src\Interfaces;

/**
 * Rest api interface
 */
interface RestInterface {
 
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