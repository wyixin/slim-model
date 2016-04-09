<?php

namespace Slim\Models;

class Model {
    private $index = 'id';
    private $index_val = 0;
    private $table = '';
    private $db = null;
    private $values = array();
    private $dirty_values = array('id' => 0);
    
    public function __construct($args) {
        $this->table = array_pop($args);
        $this->db = array_pop($args);
        // $args array $db->user();
        // array(1); $db->user(1);
        // array("email", "sss@sss.com"); $this->db("email", "sss@sss.com);
        $cnt = count($args);
        if($cnt === 0) {

        } else if($cnt === 1) {
            $this->index_val = $args[0];
        } else if($cnt === 2) {
            $this->index = $args[0];
            $this->index_val = $args[1];
        }
        $this->values[$this->index] = $this->index_val;
        $this->dirty_values[$this->index] = $this->index_val;
        
        $data = $this->findOne();
        if(is_array($data))
            $this->values = array_merge($this->values, $data);
    }

    public function findOne() {
        $val = is_integer($this->index_val) ? $this->index_val : $this->db->quote($this->index_val);
        $sql = "select * from ".$this->table." where ".
            $this->index . " = ". $val ." limit 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    public function __get($name) {
        return isset($this->values[$name]) ? $this->values[$name] : '';
    }
    
    public function __set($name, $value) {
        $pre_val = $this->values[$name];
        if($pre_val !== $value) {
            $this->values[$name] = $value;
            $this->dirty_values[$name] = $value;
        }
    }

    // todo: update when id is exists
    public function save() {
        if(count($this->dirty_values) > 1) {
            $cols = array_keys($this->dirty_values);
            $vals = array_map(function($n) {return is_integer($n) ? $n : "'".$n."'";}, array_values($this->dirty_values));
            $sql = "insert into ".$this->table . " (" . implode(',', $cols) . ")" .
                " values (". implode(',', $vals).")";
            return $this->db->exec($sql);
        }
    }

    public function delete() {
        $val = is_integer($this->index_val) ? $this->index_val : $this->db->quote($this->index_val);        
        $sql = "delete from ". $this->table. " where ".
            $this->index . " = ". $val ." limit 1";
        return $this->db->exec($sql);
    }
}