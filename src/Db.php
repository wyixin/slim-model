<?php
namespace Slim\Models;

class Db {
    private $connect = null;

    public function __construct($pdo) {
        $this->connect = $pdo;
    }

    public function close() {
        if(!$this->connect) $this->connect = null;
    }

    public function query($str) {
        return $this->connect->query($str);
    }

    public function quote($str) {
        return $this->connect->quote($str);
    }

    public function exec($str) {
        return $this->connect->exec($str);
    }

    public function __call($table, $args) {
        $str = "class $table extends Slim\Models\Model {}";
        eval($str);
        array_push($args, $this, $table.'s');
        return new $table($args);
    }
}