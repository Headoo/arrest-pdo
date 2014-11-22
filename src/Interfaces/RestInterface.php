<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Interfaces
 * @package   php-mysql-api
 * @author    Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://creativcoders.wordpress.com
 * @since     0.0.0
 */
namespace src\Interfaces;

/**
 * Rest Interface
 *
 * @category Interfaces
 * @package  php-mysql-api
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
interface RestInterface
{
    /**
     * Get url segments
     *
     * @param  string $baseUri
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
