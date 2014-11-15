<?php

namespace src\Traits;

trait JsonTrait {
    
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
        $error = array($status => array(
            'message' => $message,
            'code' => $code
        ));
        echo json_encode($error);        
    }    
}

