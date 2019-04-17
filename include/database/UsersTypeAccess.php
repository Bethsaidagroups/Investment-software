<?php
//The users type database Accessor class

namespace database;

class UsersTypeAccess{
    //define class constants
    private $db_table = DB_PREFIX . "users_type";

    //Class properties
    private $id = null;

    //Instance of SQLHandler class
    private $sql_handler;

    public function __construct($sql_handler, $id = null){
        $this->sql_handler = $sql_handler;
        $this->id = $id;
    }

    //Select single row from the database
    public function select_single($filters = null, $clause = null){
        $db_handle = $this->sql_handler;
        $db_handle->addSelectFilters($filters);
        is_null($clause) ? $db_handle->addClause("id",$this->id) : $db_handle->addClause(key($clause), $clause[key($clause)]);
        $db_handle->buildSelect($this->db_table);
        $db_handle->execute();
        $result = $db_handle->fetch();
        return $result;
    }
    //select multiple row from the database
    public function select_multiple($filters = null, $clause = null, $cmd = null){
        $db_handle = $this->sql_handler;
        $db_handle->addSelectFilters($filters);
        $db_handle->addSqlCmd($cmd);
        is_null($clause) ?  : $db_handle->addClause(key($clause), $clause[key($clause)]);
        $db_handle->buildSelect($this->db_table);
        $db_handle->execute();
        $result = $db_handle->fetchAll();
        return $result;
    }
    //Close DB connection
    public function close(){
        $this->sql_handler->close();
    }
}