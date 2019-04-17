<?php
    /**
     * The SQL Statement Handler
    */
    namespace database;

    class SQLHandler{

        // database connection and table name
        private $conn;

        private $connClone;
        //Class Properties
        private $final_sql;

        public $temp_sql;

        private $columnValues = array();

        private $sql_clause = array();

        private $clause_exp = "AND";

        private $select_filters = null;

        private $cmd = null;


        public function __construct($db){
            $this->conn = $db;
        }
        //Load column values one after the other
        public function addColumnValues($column, $value){
            $this->columnValues[$column] = $value;
        }
        //load column values once from array
        public function addColumnValuesFromArray($array){
            $this->columnValues = $array;
        }
        //Load clause values one after the order
        public function addClause($column, $value){
            $this->sql_clause[$column] = $value;
        }
        //load clause values once from array
        public function addClauseFromArray($array){
            $this->sql_clause = $array;
        }

        public function changeException($new_exp){
            $this->clause_exp = $new_exp;
        }
        private function loadClause(){
            //Continue building string by adding clauses
            if(count($this->sql_clause) != 0){
                $this->temp_sql = $this->temp_sql . " WHERE ";
                if($this->clause_exp == "OR"){
                    $outer_count = 0;
                    foreach($this->sql_clause as $key => $value){
                        $outer_count++;
                        $count = 0;
                        foreach($this->sql_clause[$key] as $in_key => $in_value){
                            if($count != count($this->sql_clause[$key])-1){
                                $this->temp_sql = $this->temp_sql . "$key = :$in_key OR ";
                            }
                            else{
                                $this->temp_sql = $this->temp_sql . "$key = :$in_key";
                            }
                            $count++;
                        }
                        if($outer_count <= count($this->sql_clause)-1){
                            $this->temp_sql = $this->temp_sql . " OR ";
                        }
                    }
                }
                elseif($this->clause_exp == "AND"){
                    $count = 0;
                    foreach($this->sql_clause as $key => $value){
                        if($count != count($this->sql_clause)-1){
                            $this->temp_sql = $this->temp_sql . "$key = :$key AND ";
                        }
                        else{
                            $this->temp_sql = $this->temp_sql . "$key = :$key";
                        }
                        $count++;
                    }
                }
            }
            //check if cmd are set in form of associative array
            if(is_array($this->cmd) && !is_null($this->cmd)){
                //The variable is an array now check if 'order' query command is set
                
                if(isset($this->cmd["order_by"]) && isset($this->cmd["order_in"])){
                    $this->temp_sql = $this->temp_sql . " ORDER BY `" . $this->cmd["order_by"] . "` " . $this->cmd["order_in"];
                }
                if(isset($this->cmd["limit_start"]) && isset($this->cmd["limit_stop"])){
                    $this->temp_sql = $this->temp_sql . " LIMIT " . $this->cmd["limit_start"] . ", " . $this->cmd["limit_stop"];
                }
            }
            $this->final_sql = $this->temp_sql;
        }
        public function buildInsert($table){
            $this->temp_sql = "INSERT INTO `$table` ( ";
            //build Insert String
            $count = 0;
            foreach($this->columnValues as $key => $value){
                if($count != count($this->columnValues)-1){
                    $this->temp_sql = $this->temp_sql . $key . ",";
                }
                else{
                    $this->temp_sql = $this->temp_sql . $key . ")";
                }
                $count++;
            }

            //Continue building insert string by adding values
            $this->temp_sql = $this->temp_sql . " VALUES( ";
            $count = 0;
            foreach($this->columnValues as $key => $value){
                if($count != count($this->columnValues)-1){
                    $this->temp_sql = $this->temp_sql . ":". $key . ",";
                }
                else{
                    $this->temp_sql = $this->temp_sql . ":". $key . ")";
                }
                $count++;
            }
            $this->final_sql = $this->temp_sql;
        }
        
        public function buildUpdate($table){
            $this->temp_sql = "UPDATE `$table` SET ";

            //build Update String
            $count = 0;
            foreach($this->columnValues as $key => $value){
                if($count != count($this->columnValues)-1){
                    $this->temp_sql = $this->temp_sql . "$key = :$key,";
                }
                else{
                    $this->temp_sql = $this->temp_sql . "$key = :$key ";
                }
                $count++;
            }

            //Continue building update string by adding clauses
            $this->loadClause();
            
        }

        public function addSelectFilters($select_filters){
            $this->select_filters = $select_filters;
        }

        public function addSqlCmd($cmd = null){
            $this->cmd = $cmd;
        }
        public function buildSelect($table){
            if(!is_null($this->select_filters)){
                $this->temp_sql = "SELECT $this->select_filters FROM `$table` ";
            }
            else{
                $this->temp_sql = "SELECT * FROM `$table` ";
            }

            //Continue building select string by adding clauses
            $this->loadClause();
        }

        public function buildDelete($table){
            $this->temp_sql = "DELETE FROM `$table` ";

            //Continue building select string by adding clauses
            $this->loadClause();
        }

        public function execute(){
            //Bind palaceholder to values and execute SQL querry;
            //echo $this->final_sql . '</br>';
            try{

                $this->connClone = $this->conn->prepare($this->final_sql);
                foreach($this->columnValues as $key => $value){
                    $this->connClone->bindValue(":$key",$value);
                }
                if(count($this->sql_clause) != 0){
                    if($this->clause_exp == "OR"){
                        foreach($this->sql_clause as $key => $value){
                            foreach($this->sql_clause[$key] as $in_key => $in_value){
                                $this->connClone->bindValue(":$in_key",$in_value);
                            }
                        } 
                    }
                    elseif($this->clause_exp == "AND"){
                        foreach($this->sql_clause as $key => $value){
                            $this->connClone->bindValue(":$key",$value);
                        } 
                    }
                }
                $this->connClone->execute();
            }
            catch(PDOException $e){
                //die($e);
            }
        }
 
        public function fetchAll(){
            return $this->connClone->fetchAll();
        }
        public function fetch(){
            return $this->connClone->fetch();
        }

        //SQL raw querry function function for 
        public function rawQuery($query, $bind_values = null){
            try{
                $this->connClone = $this->conn->prepare($query);
                if(is_null($bind_values)){
                    $this->connClone->execute();
                }
                else{
                    $this->connClone->execute($bind_values);
                }
                return $this->connClone->fetchAll();
            }
            catch(PDOException $e){
                die($e);
            }
        }
        //close handler
        public function close(){
            unset($this->conn);
        }
    }
?>