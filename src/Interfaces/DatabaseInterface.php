<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Interfaces
 * @package   Arrest-PDO
 * @author    Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://creativcoders.wordpress.com
 * @since     0.0.0
 */
namespace src\Interfaces;

/**
 * Database Interface
 *
 * @category Interfaces
 * @package  Arrest-PDO
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
interface DatabaseInterface
{
    /**
     * Parse database config method
     *
     * @param string $file
     */
    public function parseDatabaseConfig($file);

    /**
     * Connect method
     */
    public function connect();

    /**
     * Map method
     */
    public function mapDatabase();
}
