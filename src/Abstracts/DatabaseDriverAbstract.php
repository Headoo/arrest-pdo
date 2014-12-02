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

use src\Interfaces\DatabaseDriverInterface;
use \PDO;
use \Exception;

/**
 * Database Driver Abstract
 *
 * @category Abstracts
 * @package  Arrest-PDO
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
abstract class DatabaseDriverAbstract implements DatabaseDriverInterface
{
    /**
     *
     * @var string 
     */
    protected $host;
    
    /**
     *
     * @var string 
     */
    protected $port;
    
    /**
     *
     * @var string 
     */
    protected $database;
    
    /**
     *
     * @var string 
     */
    protected $user;
    
    /**
     *
     * @var string 
     */
    protected $password; 
    
    /**
     *
     * @var string 
     */
    protected $sqlitePathToDb; 
    
    /**
     *
     * @var string 
     */
    protected $oraclePathToDb; 
    
    /**
     *
     * @var string 
     */
    protected $firebirdPathToDb; 
    
    /**
     *
     * @var string 
     */
    protected $ibmPathToDb; 
    
    /**
     *
     * @var string 
     */
    protected $odbcPathToDb;
    
    /**
     *
     * @var string 
     */
    protected $charset;
    
    /**
     *
     * @var string 
     */
    protected $dsn;    
    
    /**
     * Mysql Driver
     * 
     * @return mixed
     */
    public function mysql()
    {
        $this->dsn = (string) "mysql:host=$this->host;port=$this->host;"
                . "dbname=$this->database";
        
        return $this->pdoConnect(1);
    }

    /**
     * PostgreSql driver
     * 
     * @return mixed
     */
    public function postgreSql()
    {
        $this->dsn = (string) "pgsql:host=$this->host;port=$this->port;"
                . "dbname=$this->database;user=$this->user;"
                . "password=$this->password";
        
        return $this->pdoConnect(0);
    }
    
    /**
     * SQLite driver
     * 
     * @return mixed
     */
    public function sqlite()
    {
        $this->dsn = (string) "sqlite:$this->sqlitePathToDb";
        
        return $this->pdoConnect(1);
    }
       
    /**
     * Oracle driver
     * 
     * @return mixed
     */
    public function oracle()
    {
        $this->dsn = (string) "oci:$this->oraclePathToDb";
        
        return $this->pdoConnect(1);         
    }
    
    /**
     * Cubrid driver
     * 
     * @return mixed
     */
    public function cubrid()
    {
        $this->dsn = (string) "cubrid:host=$this->host;port=$this->host;"
                . "dbname=$this->database";
        
        return $this->pdoConnect(1);        
    }
    
    /**
     * Firebird driver
     * 
     * @return mixed
     */
    public function firebird()
    {
        $this->dsn = (string) "firebird:$this->firebirdPathToDb";
        
        return $this->pdoConnect(1);        
    }
    
    /**
     * Ibm driver
     * 
     * @return mixed
     */
    public function ibm()
    {
        $this->dsn = (string) "ibm:$this->ibmPathToDb";
        
        return $this->pdoConnect(1);        
    }
    
    /**
     * Informix driver
     * 
     * @return mixed
     */
    public function informix()
    {
        $this->dsn = (string) "informix:$this->informixPathToDb";
        
        return $this->pdoConnect(1);         
    }
    
    /**
     * MsSql driver
     * 
     * @return mixed
     */
    public function msSqlServer()
    {
        $this->dsn = (string) "msql:host=$this->host;port=$this->host;"
                . "dbname=$this->database";
        
        return $this->pdoConnect(1);       
    }
    
    /**
     * Odbc driver
     * 
     * @return mixed
     */
    public function odbc()
    {
        $this->dsn = (string) "odbc:$this->odbcPathToDb";
        
        return $this->pdoConnect(0);        
    }
    
    /**
     * 4D driver
     * 
     * @return mixed
     */
    public function fourD()
    {
        $this->dsn = (string) "4D:host=$this->host;charset=$this->charset";
        
        return $this->pdoConnect(1);         
    }
    
    /**
     * Connect to PDO
     * 
     * @param  integer $state Switch between one pdo connection and another
     * @return \PDO
     * @throws Exception
     */
    private function pdoConnect($state)
    {
        $dsn  = $this->dsn;
        $user = $this->user;
        $pass = $this->password;
        
        $pdo  = ($state === 1) ? new PDO($dsn, $user, $pass) : new PDO($dsn);
        
        if (!is_object($pdo)) {
            throw new Exception("unable to connect to database");
        }
        
        return $pdo;
    }
}
