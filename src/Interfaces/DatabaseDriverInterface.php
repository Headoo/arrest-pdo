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
 * Database Driver Interface
 *
 * @category Interfaces
 * @package  Arrest-PDO
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
interface DatabaseDriverInterface
{
    /**
     * Mysql driver
     *
     */
    public function mysql();

    /**
     * PostgreSql driver
     */
    public function postgreSql();
    
    /**
     * SQLite driver
     */
    public function sqlite();
       
    /**
     * oracle driver
     */
    public function oracle();
    
    /**
     * Cubrid driver
     */
    public function cubrid();
    
    /**
     * Firebird driver
     */
    public function firebird();
    
    /**
     * Ibm driver
     */
    public function ibm();
    
    /**
     * Informix driver
     */
    public function informix(); 
    
    /**
     * MsSqlServer driver
     */
    public function msSqlServer();
    
    /**
     * Odbc driver
     */
    public function odbc();
    
    /**
     * 4D driver
     */
    public function fourD();    
}
