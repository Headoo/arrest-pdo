<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Abstracts
 * @package   Arrest-PDO
 * @author    Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://creativcoders.wordpress.com
 * @since     0.0.0
 */
namespace src\Abstracts;

use src\Interfaces\RestInterface;
use \Exception;

/**
 * Rest Abstract
 *
 * @category Abstracts
 * @package  Arrest-PDO
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
abstract class RestAbstract implements RestInterface
{
    use \src\Traits\JsonTrait;

    /**
     *
     * @var array
     */
    public $urlSegments;

    /**
     *
     * @var array
     */
    public $queryString;

    /**
     *
     * @var mixed
     */
    public $params = array();
    
    /**
     *
     * @var boolean
     */
    public $error = false;     


    /**
     * Get url segments
     * If url doesn't match "/table/id" format, generate json error
     *
     * @param  string        $baseUri
     * @return array|boolean
     * @throws Exception
     */
    public function getUrlSegments($baseUri = 'index.php')
    {
        $phpSelf    = (string) filter_input(INPUT_SERVER, "PHP_SELF");

        $segments   = (array) explode('/',
                str_replace("/$baseUri/", '', $phpSelf));

        try {
            if (empty($segments[0])) {
                 throw new Exception($this->createJsonMessage('error', 
                        'Wrong url api', 404));              
            }
            if (isset($segments[1]) && ($segments[1] != $baseUri)) {
                $result = (array) $this->urlSegments = $segments;
            } elseif (!isset($segments[1])) {
                $result = (array) $this->urlSegments = $segments;
            }
            return $result;
            
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->error = true;
        }
    }

    /**
     * Get url params
     *
     * @return array
     */
    public function getUrlParams()
    {
        $queryString    = (string) filter_input(INPUT_SERVER, "QUERY_STRING");

        if (!empty($queryString)) {
            $newQueryString         = explode('&', $queryString);
            foreach ($newQueryString as $key) {
                $var = explode('=', $key);
                $this->params[$var[0]] = $var[1];
            }
        }

        return $this->params;
    }

    /**
     * Get $_GET parameters
     *
     * @return array
     */
    public function get()
    {
        $this->params['start_query'] = (string) 'select';

        return (array) $this->params;
    }

    /**
     * Get $_POST datas
     *
     * @return array
     */
    public function post()
    {
        $this->params = (array) $_POST;
        $this->params['start_query'] = (string) 'insert';

        return (array) $this->params;
    }

    /**
     * Get Http PUT datas
     *
     * @return array
     */
    public function put()
    {
        $output = array();
        parse_str(file_get_contents("php://input"), $output);
        $this->params['start_query'] = (string) 'update';

        foreach ($output as $key => $value) {
            $this->params[$key] = (string) $value;
        }

        return (array) $this->params;
    }

    /**
     * Prepare Http delete
     *
     * @return array
     */
    public function delete()
    {
        $this->params['start_query'] = (string) 'delete';

        return (array) $this->params;
    }
}
