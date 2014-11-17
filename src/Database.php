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

use src\Abstracts\DatabaseAbstract;

/**
 * Database Controller
 *
 * @category Src
 * @package  php-mysql-api
 * @author   Edouard Kombo <edouard.kombo@gmail.com>, tech@headoo.com
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://creativcoders.wordpress.com
 */
class Database extends DatabaseAbstract
{
    use \src\Traits\JsonTrait;
    
    /**
     *
     * @var integer 
     */
    public $max_queries = 30;
    
    /**
     *
     * @var array 
     */
    public $custom_pk = array();    
    
    /**
     * Constructor
     * 
     * @param string $file Database configuration files
     */
    public function __construct($file)
    {
       $this->parseDatabaseConfig($file); 
    }
    
    /**
     * Execute corresponding query
     * 
     * @return mixed
     */
    public function doQuery()
    {
        if ($this->params['start_query'] === 'select') {
            $result = $this->selectQuery();                   
        } else if ($this->params['start_query'] === 'insert') {
            $result = $this->insertQuery();
        } else if ($this->params['start_query'] === 'update') {
            $result = $this->updateQuery();
        } else if ($this->params['start_query'] === 'delete') {
            $result = $this->deleteQuery();
        }
        
        return $result;
    }
     
    /**
     * If pk != "id", set custom pk field
     * 
     * @param  array $values Table=>field array association
     * @return array
     */
    public function setCustomPkFieldsPerTable($values)
    {
        return (array) $this->custom_pk = $values;
    }
    
    /**
     * Build select query
     * 
     * @return mixed
     */
    private function selectQuery()
    {
        $sql    = "SELECT * FROM " . $this->table;
        $values = array();
        $customPk = (isset($this->custom_pk[$this->table])) ? $this->custom_pk[$this->table] : 'id';
        
        if (!empty($this->id)) {
            $sql .= " WHERE $customPk=$this->id";
        }      
        if (!empty($this->params['order_by'])) {
            $orderBy = $this->params['order_by'];
            $sql .= " ORDER BY $orderBy";
        }
        if (!empty($this->params['order'])) {
            $order = strtoupper($this->params['order']);
            $sql .= " $order";
        }
        if (!empty($this->params['limit'])) {
            $limit = intval($this->params['limit']);
            $sql .= " LIMIT 0,$limit";
        } else {
            $sql .= " LIMIT 0,$this->max_queries";
        }

        return $this->execute('select', $sql, $values);
    }
    
    /**
     * Build insert query
     * 
     * @return mixed
     */    
    private function insertQuery()
    {
        $sql  = "INSERT INTO " . $this->table . " (";
        
        $count  = array('0' => 0, '1' => 0);
        $values = array();
        foreach ($this->params as $key => $var) {
            if ($key !== 'start_query') {
                $count[0]++;
                $sql .= ((count($this->params)-1) === $count[0]) ? "$key":"$key,";
            }
        }
        $sql .= ") VALUES (";
        
        foreach ($this->params as $k => $v) {
            if ($k !== 'start_query') {
                $count[1]++;
                $sql .= ((count($this->params)-1) === $count[1]) ? ":$k":":$k,";
                $values[":$k"] = $v;
            }
        }
        $sql .= ")";
        
        return $this->execute('insert', $sql, $values);          
    } 
    
    /**
     * Build update query
     * 
     * @return mixed
     */     
    private function updateQuery()
    {
        $sql        = "UPDATE " . $this->table . " SET ";
        $customPk   = (isset($this->custom_pk[$this->table])) ? 
                $this->custom_pk[$this->table] : 'id';
        
        $count  = 0;
        $values = array();
        foreach ($this->params as $key => $var) {
            if ($key !== 'start_query') {
                $count++;
                $sql .= ((count($this->params)-1) === $count)
                        ? "$key=:$key":"$key=:$key,";
                $values[":$key"] = $var;
            }
        }
        $sql .= " WHERE $customPk=:id";
        
        $values[":id"] = $this->id;
        
        return $this->execute('update', $sql, $values);          
    }    
    
    /**
     * Build delete query
     * 
     * @return mixed
     */     
    private function deleteQuery()
    {
        $customPk   = (isset($this->custom_pk[$this->table])) ? 
                $this->custom_pk[$this->table] : 'id';        
        $sql    = "DELETE FROM " . $this->table . " WHERE $customPk=:id";
        $values = array(':id' => $this->id);
        
        return $this->execute('delete', $sql, $values);          
    }    
    
    /**
     * Execute query
     * 
     * @param  string $type   Request type (select, insert, update, delete)
     * @param  string $sql    Prepared statement
     * @param  array  $values Values to attach to the request 
     */
    private function execute($type, $sql, $values) 
    {
        $stmt   = $this->pdo->prepare($sql);
        
        foreach ($values as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $data   = $stmt->execute();       
        
        if ($type === 'select') {
            $data = (!empty($this->id)) ? $stmt->fetch() : $stmt->fetchAll();
        }
        
        if (is_bool($data)) {
            if (false === $data) {
                echo $this->createJsonMessage('error', 'Request error', 204);
            } else {
                echo $this->createJsonMessage('success', 'Request done', 200);
            }
        } else {
            echo json_encode($data);
        }
    }
}