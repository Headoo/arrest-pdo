<?php

namespace src\Abstracts;

use src\Interfaces\DatabaseInterface;

use \PDO;
use \Exception;
use \PDOException;

abstract class DatabaseAbstract implements DatabaseInterface
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
    private $db_configs;
    
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
     * 
     * 
     * @param array $db_configs Database params
     */
    public function connect() 
    {
        try {
            $database   = $this->db_configs['database'];
            $server     = $this->db_configs['server'];
            $username   = $this->db_configs['username'];
            $password   = $this->db_configs['password'];        
            $dns        = "mysql:host=$server;dbname=$database";
            $this->pdo  = new PDO( $dns, $username, $password);             
        } catch (Exception $e) {
            echo "Connection à MySQL impossible : ", $e->getMessage();
            die();
        }        
    }
    
    /**
     * Map the stucture of the MySQL db to an array
     *
     * @param string $database Name of the database
     * @return array Returns array of db structure
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

        foreach($this->db_structure as $table_name => $var){
            
            $q = $this->pdo->prepare("DESCRIBE $table_name");
            $q->execute();
            $tableFields = $q->fetchAll(PDO::FETCH_COLUMN);
            $this->db_structure[$tableName] = $tableFields;
        }
	
        return $this->db_structure;
    }
}