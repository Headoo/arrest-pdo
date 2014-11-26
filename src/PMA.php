<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Src
 * @package   php-mysql-api
 * @author    Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://creativcoders.wordpress.com
 * @since     0.0.0
 */
namespace src;

use src\Abstracts\RestAbstract;
use \Exception;

/**
 * PMA Controller
 *
 * @category Src
 * @package  php-mysql-api
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
class PMA extends RestAbstract
{
    /**
     *
     * @var \src\Database
     */
    public $database;

    /**
     *
     * @var array
     */
    public $ip;

    /**
     *
     * @var array
     */
    public $forbiddenMethods = array();

    /**
     *
     * @var array
     */
    public $allowedTables = array();   

    /**
     * Constructor
     *
     * @param \src\Database $database Database object
     * @param string        $ip       File containing allowed ips
     */
    public function __construct(\src\Database $database, $ip)
    {
        $this->database = $database;
        $this->ip       = parse_ini_file($ip);
    }

    /**
     * Hydrate database properties (table and id)
     */
    
    /**
     * Hydrate database properties (table and id)
     * 
     * @return boolean
     * @throws Exception
     */
    public function hydrateDatabaseProperties()
    {
        if (true === $this->error) { return false;  }        
        
        $url                    = $this->urlSegments;
        $this->database->table  = (!empty($url[0])) ? $url[0] : '';
        $table                  = $this->database->table;

        try {
            if (empty($table)) {
                throw new Exception($this->createJsonMessage('error',
                        'No table specified', 404));                
            }
            if (!empty($this->allowedTables) 
                    && !in_array($table, $this->allowedTables)) {
                throw new Exception($this->createJsonMessage('error',
                        'Forbidden table', 404));
            }
            $this->database->id     = (!empty($url[1])) ? $url[1] : '';
            
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->error = true;
        }
    }

    /**
     * Dispatch all values to different properties
     * 
     * @return boolean
     * @throws Exception
     */
    public function authentifyRequest()
    {
        if (true === $this->error) { return false; }
        
        $ipsArray = explode(',', $this->ip['allowed_ips']);
        $ip                = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $http_client_id    = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');
        $http_x_forwarded  = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');
        
        if (!empty($http_client_id)) {
            $ip = $http_client_id;
        } elseif (!empty($http_x_forwarded)) {
            $ip = $http_x_forwarded;
        }

        try {
            if (!in_array($ip, $ipsArray)) {
                throw new Exception($this->createJsonMessage('error', 
                        'Not authorized http request', 404));
            }           
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->error = true;
        }

    }

    /**
     * Specify http methods to forbid
     *
     * @param  array $methods Http Methods to forbid
     * @return array
     */
    public function forbiddenMethods($methods)
    {
        return $this->forbiddenMethods = (array) $methods;
    }

    /**
     * Authorized tables to request
     *
     * @param  array $tables Database tables to request
     * @return array
     */
    public function allowedTables($tables)
    {
        return $this->allowedTables = (array) $tables;
    }

    
    /**
     * handle the rest call
     * 
     * @return mixed
     * @throws Exception
     */
    public function rest()
    {
        if (true === $this->error) {
            return false;
        }        
        
        header('Content-type: application/json');
        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");

        $this->restSwitchCases($method);
        
        try {
            if (!empty($this->forbiddenMethods) &&
                    in_array($method, $this->forbiddenMethods)) {
                throw new Exception($this->createJsonMessage('error', 
                        'Forbidden http method', 404));
            } else {
                $this->database->params = $this->params;
                echo $this->database->doQuery();
            }            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Execute switch cases for rest method
     *
     * @param  string  $method Http method specified
     * @return boolean
     */
    private function restSwitchCases($method)
    {
        switch ($method) {
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

        return true;
    }
}
