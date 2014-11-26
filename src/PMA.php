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
    public function hydrateDatabaseProperties()
    {
        $url                    = $this->urlSegments;
        $this->database->table  = (!empty($url[0])) ? $url[0] : '';
        $table                  = $this->database->table;

        if (!empty($table)) {
            if (!empty($this->allowedTables) && !in_array($table, $this->allowedTables)) {
                throw new \Exception($this->createJsonMessage('error', 'Forbidden table', 404));
            }
            $this->database->id     = (!empty($url[1])) ? $url[1] : '';
        }
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
     * @access public
     */
    public function rest()
    {
        header('Content-type: application/json');
        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");

        $this->restSwitchCases($method);

        if (!empty($this->forbiddenMethods) &&
                in_array($method, $this->forbiddenMethods)) {
            echo $this->createJsonMessage('error', 'Forbidden', 404);
        } else {
            $this->database->params = $this->params;

            return $this->database->doQuery();
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
