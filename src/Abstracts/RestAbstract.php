<?php

namespace src\Abstracts;

use src\Interfaces\RestInterface;


/**
 * Rest abstract
 */
abstract class RestAbstract implements RestInterface 
{
    
    /**
     *
     * @var string 
     */
    protected $postDatas;
    
    /**
     *
     * @var string 
     */
    protected $getDatas;
    
    
    /**
     * Return json errors
     * 
     * @param string  $status  Json Status return
     * @param string  $message Message returned in Json
     * @param integer $code    Http status code
     * 
     * @access protected
     */
    protected function createJsonError($status, $message, $code)
    {
        $error = array($status => array(
            'message' => $message,
            'code' => $code
        ));
        die(json_encode($error));        
    }
    
    /**
     * POST request
     * 
     * @param \src\Database $database Database object
     */
    public function post($database) 
    {
        if ($this->postDatas) {
            $this->db->insert($this->table, $this->postDatas)->query();
            $this->createJsonError('success', 'Success', 200);
        } else {
            $this->createJsonError('error', 'No content', 204);
        }        
    }
    
    /**
     * GET request
     * 
     * @param \src\Database $database Database object
     */
    public function get($database) 
    {
        $statement  = $pdo->prepare("SELECT * FROM table");
        $statement->execute();
        $results    = $statement->fetchAll(PDO::FETCH_ASSOC);
        $json       = json_encode($results);        
        
        $this->db->select('*')
        ->from($this->table)
        
        ->where('id', $this->id)
        ->order_by($this->getGetDatas('order_by'), $this->getGetDatas('order'))
        ->limit(intval($this->getGetDatas('limit')), 
                intval($this->getGetDatas('offset')))
        ->query();        
        
        $result = (!empty($this->index)) ? 
            $this->db->fetch_all() : $this->db->fetch_array() ;
        
        if($result){
            die(json_encode($result));
        } else {
            $this->createJsonError('error', 'No content', 204);
        }               
    }
    
    /**
     * Put method
     * 
     * @access public
     */
    public function put() 
    {
        $this->db->select('*')
        ->from($this->table)
        ->where($this->index, $this->id)
        ->query();
        
        $result = $this->db->fetch_array();
        
        if ($result) {
            $this->db->update($this->table)
            ->set($this->getPutDatas())
            ->where($this->index, $this->id)
            ->query();
            
            $this->createJsonError('success', 'Success', 200);

        } else {
            $this->createJsonError('error', 'No content', 204);
        }        
    }
    
    /**
     * Delete method
     * 
     * @access public
     */
    public function delete() 
    {
        $this->db->select('*')
        ->from($this->table)
        ->where($this->index, $this->id)
        ->query();
        
        $result = $this->db->fetch_array();
        
        if ($result) {
            $this->db->delete($this->table)
            ->where($this->index, $this->id)
            ->query();
            
            $this->createJsonError('success', 'Success', 200);

        } else {
            $this->createJsonError('error', 'No content', 204);
        }
    }
    
    
    /**
     * Get get datas
     * 
     * @param  string $index
     * @return string
     */
    private function getGetDatas($index = '')
    {
        $request = (string) filter_input(INPUT_GET, $index);
        return (!empty($request)) ? $request : false ; 
    }
    
    /**
     * Get post datas
     * 
     * @param  string $index
     * @return string
     */
    private function getPostDatas($index = '')
    {
        $request = (string) filter_input(INPUT_POST, $index);
        return (!empty($request)) ? $request : false ; 
    }
    
    /**
     * Get put datas
     * 
     * @return string
     */
    private function getPutDatas()
    {
        $output = array();
        parse_str(file_get_contents('php://input'), $output);
        return (string) $output;
    }    
}