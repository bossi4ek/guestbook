<?php

    class Mysql {

        //свойства
        private $db_table;              //таблицы базы данных
        private $db_host = false;
        private $db_name = false;
        private $db_user = false;
        private $db_pass = false;
        private $db_link;               //указатель на подключение к базе

        private $query_table;           //результат выполнения select
        private $query_time;            //время выполнения select
        private $query;                 //последний запрос
        public $system_log;

        private $debug = false;         //вывод ошибок: true - да, false - нет
        private $db_error;              //последняя ошибка. Массив: error - текст ошибки, errno - номер ошибки
        
        private $log_file;
//==============================================================================
//установка переменной
        public function setDebug($value) {
            $this->debug = $value;
        }
//==============================================================================
//получение ошибки
        public function getError() {
            $result = $this->db_error['errno'] . ': ' . $this->db_error['error'];
            $result.= "\n".$this->query;
            return $result;
        }
//==============================================================================
//вывод ошибки
        public function printError() {
            echo $this->db_error['errno'] . ': ' . $this->db_error['error'];
            echo "\n".$this->query;
        }
//==============================================================================
//обработка ошибки
        private function executeError() {
            $this->db_error = array('error' => mysql_error(), 'errno' => mysql_errno(), 'query' => $this->query);
            $this->system_log[] = array(
                'query' => $this->query,
                'time' => $this->query_time,
                'count' => count($this->query_table),
                'error' => "Ошибка: ".mysql_error()."\nНомер: ".mysql_errno()
            );
            $this->writeToFile($this->db_error);
            if ($this->debug) {
                $this->printError();
            }
        }
//==============================================================================        
//установка переменной
        public function setLogFile($log_file) {
            $this->log_file = $log_file;
        }
//==============================================================================        
        protected function writeToFile($data) {
            if (isset($this->log_file)) {
                if (is_array($data)) {
                    $str = "array(\n";
                    foreach ($data as $key => $value_log) {
                        $str.= "   ".sprintf("%-20s %s %s", $key,"=>",$value_log)."\n";
                    }
                    $str.= ")";
                    $data = $str;
                }
                $handel = fopen($this->log_file,'a');
                fwrite($handel, "TIME: ".date("d.m.Y H:i:s")."\n".
                        $data.
                        "\n-----------------------------------------------------------------------\n");
                fclose($handel);
            }
        }
//==============================================================================
//Доступ к настройкам базы
        public function setDbHost($value) {
            $this->db_host = $value;
        }

        public function setDbName($value) {
            $this->db_name = $value;
        }

        public function setDbUser($value) {
            $this->db_user = $value;
        }

        public function setDbPass($value) {
            $this->db_pass = $value;
        }

        public function getDbHost() {
            return $this->db_host;
        }

        public function getDbName() {
            return $this->db_name;
        }

        public function getDbUser() {
            return $this->db_user;
        }

        public function getDbPass() {
            return $this->db_pass;
        }

        public function getDbTable() {
            return $this->db_table;
        }
//==============================================================================
//получение количества записей в таблице, полученой в результате операции select
        public function getQueryCount() {
            return count($this->query_table);
        }

//получение таблицы, полученой в результате операции select
        public function getQueryTable() {
            return $this->query_table;
        }

//получение времени выполнения операции select
        public function getQueryTime() {
            return $this->query_time;
        }
        
        public function getRow($row_index = 0, $key = false, $value = false) {
            if ((!$key) & (!$value)) {
                try {
                    return $this->query_table[$row_index];
                } catch (Exception $exc) {
                    $this->db_error = $exc->getTraceAsString();
                    if ($this->debug) {
                        $this->printError();
                    }
                    return false;
                }                
            }
            else {
                try {
                    foreach ($this->query_table as $row) {
                        if ($row[$key] == $value) {
                            return $row;
                            break;
                        }
                    }
                } catch (Exception $exc) {
                    return false;
                }                
            }
        }
        
        public function getValue($key = false, $row_index = 0) {
            if (!$key) {          
                return false;
            }
            else {
                try {
                    return $this->query_table[$row_index ][$key];
                } catch (Exception $exc) {
                    return false;
                }                
            }
        }

//==============================================================================
//Подготовка запроса (удаление "лишней" информации)
        public function prepareQuery($value) {
            return trim(preg_replace("/\s{2,}/", " ", $value));
        }
//==============================================================================
//закрузка файла конфигурации
        public function loadConfig($path = false) {
            if (!$path){
                $this->db_host = DB_HOST;
                $this->db_user = DB_USER;
                $this->db_pass = DB_PASS;
                return true;
            }
            else {
                require_once $path;
                $this->db_host = DB_HOST;
                $this->db_user = DB_USER;
                $this->db_pass = DB_PASS;
                return true;
            }
            return false;
        }
        
//==============================================================================
//операция connect
        public function connect($db_host = false, $db_user = false, $db_pass = false) {
            if ((!$db_host) & (!$db_user) & (!$db_pass)) {
                if ((!$this->db_host) & (!$this->db_user) & (!$this->db_pass)) {
                    if ($this->debug) {
                        echo 'Не заполнены переменные доступа к базе';
                    }
                    else {
                        $this->db_error = array('error' => 'Не заполнены переменные доступа к базе', 'errno' => null);
                    }
                    return false;
                }
                else {
                    $db_host = $this->db_host;                    
                    $db_user = $this->db_user;
                    $db_pass = $this->db_pass;
                }
            }
            else {
                if ((!$db_host) | (!$db_user)) {
                    if ($this->debug) {
                        echo 'Не заполнены переменные доступа к базе';
                    }
                    else {
                        $this->db_error = array('error' => 'Не заполнены переменные доступа к базе', 'errno' => null);
                    }
                    return false;
                }
                else {
                    $this->db_host = $db_host;
                    $this->db_user = $db_user;
                    $this->db_pass = $db_pass;
                }
            }
            $return = true;
            $this->db_link = mysql_connect($db_host, $db_user, $db_pass);
            if (!$this->db_link) {
                $this->executeError();
                return false;
            }            

            // Обеспечиваем нормальную работу с русскими символами
            $result = mysql_query("SET NAMES utf8", $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            $result = mysql_query("SET CHARACTER SET utf8", $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            $result = mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'", $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            return $return;
        }

//==============================================================================  
//операция disconnect
        public function disconnect() {
            $return = true;
            $result = mysql_close($this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            return $return;
        }
        
//==============================================================================  
        public function select_db($db_name = false) {
            $result = mysql_select_db($db_name, $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            return true;
        }
        
//==============================================================================        
//выполнение запроса
        public function execute($query) {
            $query = $this->prepareQuery($query);
            $compare = strtolower(substr($query, 0, strpos($query, " ")));
            if ($compare == "select") {
                return $this->select($query);
            }
            if ($compare == "insert") {
                return $this->update($query);
            }
            if ($compare == "update") {
                return $this->update($query);
            }
            if ($compare == "delete") {
                return $this->update($query);
            }
            if ($compare == "truncate") {
                return $this->update($query);
            }
        }

//==============================================================================        
//операция select
        public function select($query) {
            $query = $this->prepareQuery($query);
            $return = true;
            $this->query = $query;
            $time = microtime(1);
            $result = mysql_query($query, $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            $this->query_time = microtime(1) - $time;
            $time = microtime(1) - $time;
            
            unset($this->query_table);
            while ($row = mysql_fetch_assoc($result)) {
                $this->query_table[] = $row;
            }
            $result = mysql_free_result($result);
            if (!$result) {
                $this->executeError();                
                return false;
            }
//------------------------------------------------------------------------------
            $this->writeToFile(array(
                'query' => $query,
                'time' => sprintf("%.10f", $this->query_time)
            ));
//------------------------------------------------------------------------------
            $this->system_log[] = array(
                'query' => $query,
                'time' => sprintf("%.10f", $this->query_time),
                'count' => count($this->query_table),
                'error' => $this->db_error
            );
            return $return;
        }

//операция update
        public function update($query) {
            $query = $this->prepareQuery($query);
            $return = true;
            $this->query = $query;
            $time = microtime(1);
            $result = mysql_query($query, $this->db_link);
            if (!$result) {
                $this->executeError();
                return false;
            }
            $this->query_time = microtime(1) - $time;
            $time = microtime(1) - $time;
//------------------------------------------------------------------------------
            $this->writeToFile(array(
                'query' => $query,
                'time' => sprintf("%.10f", $this->query_time)
            ));
//------------------------------------------------------------------------------
            $this->system_log[] = array(
                'query' => $query,
                'time' => sprintf("%.10f", $this->query_time),
                'error' => $this->db_error
            );
            return $return;
        }

        //операция delete
        public function delete($query) {
            $this->query = $query;
            return $this->update($query);
        }

        //операция insert
        public function insert($query) {
            $this->query = $query;
            return $this->update($query);
        }
        
        //операция truncate
        public function truncate($query) {
            $this->query = $query;
            return $this->update($query);
        }
        
        //операция получения индекса последнего добавленого элемента
        public function getLastId() {
            return mysql_insert_id($this->db_link);
        }
        
        //операция truncate
        public function prepare($value) {
            if (!isset($value)) {
                $value = "NULL";
            }
            if (($value == "") && !is_int($value)) {
                $value = "NULL";
            }
            if (($value == "true") && !is_int($value)) {
                $value = 1;
            }
            if (($value == "false") && !is_int($value)) {
                $value = 0;
            }
            $value = mysql_real_escape_string($value);
            if ($value != "NULL") {
                $value = "'".$value."'";
            }
            return $value;
        }
    }

?>