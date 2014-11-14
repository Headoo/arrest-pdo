<?php

namespace src;

use src\Abstracts\RestAbstract;

/**
 * Rest api interface
 */
class PMA extends RestAbstract {
 
    
    /**
     *
     * @var array 
     */
    public $uri_segments;
    
    /**
     *
     * @var string 
     */
    public $table;
    
    /**
     *
     * @var string 
     */
    public $id;
    
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
     * Get uri segments
     * 
     * @param  string $base_uri
     * @return array
     */
    public function getUriSegments($base_uri = '')
    {
        $requestURI     = (string) filter_input(INPUT_SERVER, "REQUEST_URI");
        $phpSelf        = (string) filter_input(INPUT_SERVER, "PHP_SELF");
        $queryString    = (string) filter_input(INPUT_SERVER, "QUERY_STRING");
        
        if(!isset($requestURI)) {
            $requestURI = substr($phpSelf, 1);
            if (isset($queryString)) {
                $requestURI .= '?'. $queryString;
            }
        }
        
    	$_requestUrl = $requestURI;
    	$scriptUrl  = $phpSelf;
    	$requestUrl = str_replace($base_uri, '', $_requestUrl);
    	if ($requestUrl != $scriptUrl) {
            $url = trim(preg_replace('/'. str_replace('/', '\/', 
                    str_replace('index.php', '', $scriptUrl)) .'/', '', 
                    $requestUrl, 1), '/');
        }
        $url = rtrim(preg_replace('/\?.*/', '', $url), '/');
        
    	return $this->uri_segments = explode('/', $url);
    }
    
    /**
     * Hydrate datas with url parameters
     */
    public function hydrate()
    {
        $uri         = $this->uri_segments;
        $this->table = (!empty($uri[0])) ? $uri[0] : '';
        $this->id    = (!empty($uri[1])) ? $uri[1] : '';        
    }
    
    /**
     * Dispatch all values to different properties
     * 
     * @access protected
     */
    public function secureBeforeRequest()
    {
        $table = $this->table;
        
        if(!$table || !isset($this->database->db_structure[$table])){
            $this->createJsonError('error', 'Not found', 404);
        } else {
            $this->database->table = $table;
            $this->database->id = $this->id;            
        }        
    }    
    
    /**
     * Handle the REST calls and map them to corresponding CRUD
     *
     * @access public
     */
    public function rest()
    {
        header('Content-type: application/json');
        /*
        create > POST   /table
        read   > GET    /table[/id]
        update > PUT    /table/id
        delete > DELETE /table/id
        */
        switch (filter_has_var(INPUT_SERVER, "REQUEST_METHOD")) {
            case 'POST':
                $this->post($this->database);
                break;
            case 'GET':
                $this->get($this->database);
                break;
            case 'PUT':
                $this->put($this->database);
                break;
            case 'DELETE':
                $this->delete($this->database);
                break;
        }
    }    
}