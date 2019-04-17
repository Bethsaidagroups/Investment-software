<?php
//The Loan Application Database Accessor class

namespace database;

class LoanApplicationAccess{
    //define class constants
    private $db_table = DB_PREFIX . "loan_applications";

    //Class properties
    private $id = null;

    //Instance of SQLHandler class
    private $sql_handler;

    public function __construct($sql_handler, $id = null){
        $this->sql_handler = $sql_handler;
        $this->id = $id;
    }

    //Add new Login 
    public function add($obj){
        $db_handle = $this->sql_handler;
        $db_handle->addColumnValuesFromArray($obj->get_all_exclude_id());
        $db_handle->buildInsert($this->db_table);
        $db_handle->execute();
    }

    //Update Login details
    public function update($obj){
        $db_handle = $this->sql_handler;
        //$db_handle->addColumnValues("personal", $obj->get_personal());
        //$db_handle->addColumnValues("residential", $obj->get_residential());
        //$db_handle->addColumnValues("relative", $obj->get_relative());
        //$db_handle->addColumnValues("work", $obj->get_work());
        $db_handle->addColumnValues("loan", $obj->get_loan());
        $db_handle->addColumnValues("amount_approved", $obj->get_amountApproved());
        $db_handle->addColumnValues("status", $obj->get_status());
        $db_handle->addClause("id",$this->id);
        $db_handle->buildUpdate($this->db_table);
        $db_handle->execute();
    }
    //Column update provides an unconstrained udate of a single column
    public function column_update($array){
        $db_handle = $this->sql_handler;
        $db_handle->addColumnValues(key($array), $array[key($array)]);
        $db_handle->addClause("id",$this->id);
        $db_handle->buildUpdate($this->db_table);
        $db_handle->execute();
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
    //Select with using uncontrained rules returns 2-dimension array
    public function selectWithClause($filters = null, $clause, $exception = "AND",  $cmd = null){
        $db_handle = $this->sql_handler;
        $db_handle->addSelectFilters($filters);
        $db_handle->changeException($exception);
        $db_handle->addSqlCmd($cmd);
        foreach($clause as $key => $value){
            $db_handle->addClause($key,$value);
        }
        $db_handle->buildSelect($this->db_table);
        $db_handle->execute();
        $result = $db_handle->fetchAll();
        return $result;
    }
    //Delete the row with the preset id or use the one prvided by the clause
    public function delete($clause = null){
        $db_handle = $this->sql_handler;
        is_null($clause) ? $db_handle->addClause("id",$this->id) : $db_handle->addClause(key($clause), $clause[key($clause)]);
        $db_handle->buildDelete($this->db_table);
        $db_handle->execute();
    }
    //Close DB connection
    public function close(){
        $this->sql_handler->close();
    }
}