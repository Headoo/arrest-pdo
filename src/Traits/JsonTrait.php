<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Traits
 * @package   php-mysql-api
 * @author    Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://creativcoders.wordpress.com
 * @since     0.0.0
 */
namespace src\Traits;

/**
 * Json Trait
 *
 * @category Traits
 * @package  php-mysql-api
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
trait JsonTrait
{
    /**
     * Return json errors
     *
     * @param string  $status  Json Status return
     * @param string  $message Message returned in Json
     * @param integer $code    Http status code
     *
     * @access protected
     */
    public function createJsonMessage($status, $message, $code)
    {
        $error = array(
            'status'  => $status,
            'content' => array(
                'message' => $message,
                'code' => $code,
            ),
        );
        return json_encode($error);
    }
}
