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

use src\Interfaces\DatabaseInterface;
use src\Abstracts\DatabaseDriverAbstract as Drivers;
use \PDO;
use \PDOException;

/**
 * Database Abstract
 *
 * @category Abstracts
 * @package  Arrest-PDO
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
abstract class DatabaseAbstract extends Drivers implements DatabaseInterface
{
    /**
     *
     * @var PDO
     */
    public $pdo;

    /**
     *
     * @var array
     */
    public $db_configs;

    /**
     *
     * @var string
     */
    public $table;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var mixed
     */
    public $params;

    /**
     *
     * @var array
     */
    public $db_structure = array();

    /**
     * Get database configuration datas
     *
     * @param  string $file File of the ini config file
     * @return array
     */
    public function parseDatabaseConfig($file)
    {
        return $this->db_configs = (array) parse_ini_file($file);
    }

    /**
     * Connect to database with pdo
     * 
     * @return mixed
     */
    public function connect()
    {
        $configs                = $this->db_configs;
        $this->database         = (string) $configs['database'];
        $this->host             = (string) $configs['server'];
        $this->user             = (string) $configs['username'];
        $this->password         = (string) $configs['password'];
        $this->port             = (string) $configs['port'];
        $this->sqlitePathToDb   = (string) $configs['sqlite_path_to_db'];
        $this->oraclePathToDb   = (string) $configs['oracle_path_to_db'];
        $this->ibmPathToDb      = (string) $configs['ibm_path_to_db'];
        $this->informixPathToDb = (string) $configs['informix_path_to_db'];        
        $this->firebirdPathToDb = (string) $configs['firebird_path_to_db'];
        $this->odbcPathToDb     = (string) $configs['odbc_path_to_db'];
        $this->charset          = (string) $configs['charset'];
        $driver                 = (string) $configs['pdo_driver'];
        
        return $this->pdo       = $this->{$driver}();
    }

    /**
     * Map the stucture of the MySQL db to an array
     *
     * @param  string $database Name of the database
     * @return array  Returns array of db structure
     * @access private
     */
    public function mapDatabase()
    {
        $this->db_structure = array();

        try {
            $result = $this->pdo->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tableName = $row[0];
                $this->db_structure[$tableName] = array();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($this->db_structure as $table_name => $var) {
            $q = $this->pdo->prepare("DESCRIBE $table_name");
            $q->execute();
            $tableFields = $q->fetchAll(PDO::FETCH_COLUMN);
            $this->db_structure[$tableName] = $tableFields;
        }

        return $this->db_structure;
    }
}
