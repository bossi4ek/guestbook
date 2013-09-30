<?php

require_once $_SERVER['DOCUMENT_ROOT']."/libs/Mysql.php";

class Kernel {
    public $mysql;

    public function __construct() {
        //Инициация класса mysql
        $this->mysql = new Mysql();
        $this->mysql->loadConfig($_SERVER['DOCUMENT_ROOT']."/config.php");
        $this->mysql->connect();
        $this->mysql->select_db(DB_BASE);
        $this->mysql->setDebug(false);
    }
}