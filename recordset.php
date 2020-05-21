<?php

class Recordset
{
    
    private $query = ''; 
    private $data = array();
    
    public function setQuery($sql) {
      $this->query = $sql; 
    }
    
    public function execute() {
       return mysql_query($this->query);
    }
    
    public function fetch() {
       $res = mysql_query($this->query);
       $this->data = array();
       while($row = mysql_fetch_array($res))
       {
        $this->data[] = $row;
       }
       return $this->data;
    }
    
    
}








