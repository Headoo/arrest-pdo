<?php

namespace src;

use src\Abstracts\RestAbstract;

/**
 * Rest api interface
 */
class PMA extends RestAbstract 
{     
    /**
     *
     * @var \src\Database
     */
    public $database;
        
    
    /**
     * Constructor
     * 
     * @param \src\Database $database Database object
     */
    public function __construct(\src\Database $database)
    {
        $this->database = $database;
    }
    
    /**
     * Hydrate database properties (table and id)
     */
    public function hydrateDatabaseProperties()
    {
        $url                    = $this->url_segments;
        $this->database->table  = (!empty($url[0])) ? $url[0] : '';
        $this->database->id     = (!empty($url[1])) ? $url[1] : '';
    }
    
    /**
     * Dispatch all values to different properties
     * 
     * @access protected
     */
    public function authentifyRequest()
    {
        return true;    
    }    
    
    /**
     * handle the rest call
     *
     * @access public
     */
    public function rest()
    {
        header('Content-type: application/json');

        switch (filter_input(INPUT_SERVER, "REQUEST_METHOD")) {
            case 'POST':
                $this->post();
                break;
            case 'GET':
                $this->get();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
                $this->delete();
                break;
        }
        
        $this->database->params = $this->params;
        return $this->database->doQuery();
    }    
}