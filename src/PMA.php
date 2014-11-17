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
        $ipsArray = explode(',', $this->ip['allowed_ips']);
        
        $httpClientIp   = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');
        $httpXForwarded = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');
        $remoteAddr     = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        
        if (!empty($httpClientIp)) {
            $ip = $httpClientIp;
        } elseif (!empty($httpXForwarded)) {
            $ip = $httpXForwarded;
        } else {
            $ip = $remoteAddr;
        }        
        
        if (!in_array($ip, $ipsArray)) {
            return $this->createJsonMessage('error', 'Not authorized', 404);            
        }
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